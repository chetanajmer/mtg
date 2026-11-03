<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CompanyEmployee;
use App\Models\CompanyTeamMember;
use App\Models\Storeuser;
use App\Services\OrderReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Customer portal "Employees" page: the company's gift recipients and the
 * team members who can log in to the same account. "userid" in the session
 * is always the company account (CorporateTeamSession switches members to it).
 */
class CorporateEmployeeController extends Controller
{
    private const CSV_COLUMNS = ['name', 'email', 'phone', 'department', 'designation', 'address', 'city', 'birthday', 'joined_on', 'notes'];

    public function index(Request $request)
    {
        $account = $this->account();
        $tab = $request->query('tab') === 'team' ? 'team' : 'employees';
        $search = trim((string) $request->query('q', ''));
        $department = trim((string) $request->query('department', ''));

        $all = CompanyEmployee::where('account_id', $account->id)->orderBy('name')->get();
        $employees = CompanyEmployee::where('account_id', $account->id)
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $q->where(function ($w) use ($like) {
                    foreach (['name', 'email', 'phone', 'department', 'designation', 'city'] as $col) {
                        $w->orWhere($col, 'like', $like);
                    }
                });
            })
            ->when($department !== '', function ($q) use ($department) { $q->where('department', $department); })
            ->orderBy('name')->paginate(25)->withQueryString();

        $today = now()->startOfDay();
        $upcoming = $all->flatMap(function ($e) use ($today) {
            return array_map(function ($o) use ($e) { return ['employee' => $e] + $o; }, $e->occasions($today, 30));
        })->sortBy(function ($o) { return $o['date']->timestamp; })->values();

        return view('web_frontend.employees', [
            'tab' => $tab,
            'account' => $account,
            'employees' => $employees,
            'total' => $all->count(),
            'departments' => $all->pluck('department')->filter()->unique()->sort()->values(),
            'upcoming' => $upcoming,
            'search' => $search,
            'department' => $department,
            'team' => CompanyTeamMember::where('account_id', $account->id)->where('status', '!=', 'removed')->orderBy('status')->orderBy('name')->get(),
            'roles' => CompanyTeamMember::ROLES,
            'canEdit' => $this->role() !== 'viewer',
            'canManageTeam' => in_array($this->role(), ['owner', 'admin'], true),
            'role' => $this->role(),
        ]);
    }

    /* ---------- Gift recipients ---------- */

    public function store(Request $request)
    {
        $employee = new CompanyEmployee($this->validateEmployee($request));
        $employee->account_id = $this->account()->id;
        $employee->save();
        return response()->json(['success' => true, 'message' => $employee->name . ' added.']);
    }

    public function update(Request $request, $id)
    {
        $employee = $this->employee($id);
        $employee->fill($this->validateEmployee($request, $employee->id))->save();
        return response()->json(['success' => true, 'message' => $employee->name . ' saved.']);
    }

    public function destroy($id)
    {
        $employee = $this->employee($id);
        $employee->delete();
        return response()->json(['success' => true, 'message' => $employee->name . ' removed.']);
    }

    public function export(OrderReports $reports)
    {
        $rows = CompanyEmployee::where('account_id', $this->account()->id)->orderBy('name')->get()->map(function ($e) {
            return [$e->name, $e->email, $e->phone, $e->department, $e->designation, $e->address, $e->city,
                optional($e->birthday)->format('Y-m-d'), optional($e->joined_on)->format('Y-m-d'), $e->notes];
        });
        return $reports->csv('employees-' . now()->format('Y-m-d') . '.csv', self::CSV_COLUMNS, $rows);
    }

    public function template(OrderReports $reports)
    {
        return $reports->csv('employees-template.csv', self::CSV_COLUMNS, [
            ['Aisha Khan', 'aisha.khan@yourcompany.com', '+971 50 123 4567', 'Sales', 'Account Manager', 'Office 1201, Business Bay', 'Dubai', '1990-04-18', '2021-09-01', 'Prefers dark colours'],
        ]);
    }

    /** CSV upload: header row with the template's columns; rows with an email already on the list are skipped. */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt|max:2048'], ['file.mimes' => 'Please upload the list as a .csv file (use the template).']);
        $accountId = $this->account()->id;
        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $header = array_map(function ($h) {
            $h = strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', (string) $h)));
            $h = str_replace([' ', '-'], '_', $h);
            return ['full_name' => 'name', 'mobile' => 'phone', 'date_of_birth' => 'birthday', 'dob' => 'birthday',
                'joining_date' => 'joined_on', 'joined' => 'joined_on', 'work_anniversary' => 'joined_on', 'title' => 'designation'][$h] ?? $h;
        }, (array) $header);
        if (!in_array('name', $header, true)) {
            fclose($handle);
            return redirect()->route('corporate.employees')->with('error', 'The file needs a header row with at least a "name" column. Download the template to see the columns.');
        }

        $existing = CompanyEmployee::where('account_id', $accountId)->whereNotNull('email')->pluck('email')
            ->map(function ($email) { return strtolower($email); })->flip();
        $added = 0; $skipped = 0; $errors = []; $line = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $line++;
            if ($line > 2001) {
                $errors[] = 'Only the first 2,000 rows were read.';
                break;
            }
            if (!array_filter($row, function ($v) { return trim((string) $v) !== ''; })) {
                continue;
            }
            $data = [];
            foreach ($header as $i => $key) {
                if (in_array($key, self::CSV_COLUMNS, true)) {
                    $data[$key] = trim((string) ($row[$i] ?? ''));
                }
            }
            $problem = null;
            if (($data['name'] ?? '') === '') {
                $problem = 'name is missing';
            } elseif (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $problem = '"' . $data['email'] . '" is not a valid email';
            }
            foreach (['birthday', 'joined_on'] as $dateKey) {
                if (!$problem && !empty($data[$dateKey])) {
                    $date = $this->parseDate($data[$dateKey]);
                    if (!$date) {
                        $problem = $dateKey . ' "' . $data[$dateKey] . '" is not a date (use YYYY-MM-DD)';
                    }
                    $data[$dateKey] = $date;
                }
            }
            if ($problem) {
                $errors[] = 'Row ' . $line . ': ' . $problem . '.';
                continue;
            }
            if (!empty($data['email']) && isset($existing[strtolower($data['email'])])) {
                $skipped++;
                continue;
            }
            $employee = new CompanyEmployee(array_map(function ($v) { return $v === '' ? null : $v; }, array_map(function ($v) {
                return is_string($v) ? mb_substr($v, 0, 500) : $v;
            }, $data)));
            $employee->account_id = $accountId;
            $employee->save();
            if (!empty($data['email'])) {
                $existing[strtolower($data['email'])] = true;
            }
            $added++;
        }
        fclose($handle);

        $message = $added . ' employee' . ($added == 1 ? '' : 's') . ' added.' . ($skipped ? ' ' . $skipped . ' already on your list (same email) skipped.' : '');
        return redirect()->route('corporate.employees')->with($added || $skipped ? 'success' : 'error', $added || $skipped ? $message : 'Nothing was imported.')
            ->with('import_errors', array_slice($errors, 0, 20));
    }

    /* ---------- Team logins ---------- */

    public function invite(Request $request)
    {
        $account = $this->account();
        $this->mustManageTeam();
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'role' => ['required', Rule::in(array_keys(CompanyTeamMember::ROLES))],
        ]);
        $email = strtolower(trim($data['email']));
        if (Storeuser::whereRaw('LOWER(email) = ?', [$email])->exists()) {
            return redirect()->route('corporate.employees', ['tab' => 'team'])->withInput()
                ->with('error', $email === strtolower($account->email) ? 'That is your own email.' : 'This email already has its own More Than Giftz account, so it cannot join this one. Ask them to use a different (work) email.');
        }
        $member = CompanyTeamMember::where('account_id', $account->id)->whereRaw('LOWER(email) = ?', [$email])->first();
        if ($member && $member->status !== 'removed') {
            return redirect()->route('corporate.employees', ['tab' => 'team'])->withInput()->with('error', 'This person is already on your team list.');
        }
        $member = $member ?: new CompanyTeamMember(['account_id' => $account->id]);
        $member->fill(['name' => $data['name'], 'email' => $email, 'role' => $data['role'], 'status' => 'invited', 'user_id' => null,
            'invited_by' => session('member_id') ?: $account->id, 'accepted_at' => null]);
        $link = $this->newInviteLink($member);
        $member->save();

        return $this->sendInvite($member, $account, $link, 'Invitation sent to ' . $email . '.');
    }

    public function resend($id)
    {
        $account = $this->account();
        $this->mustManageTeam();
        $member = $this->member($id);
        if ($member->status !== 'invited') {
            return redirect()->route('corporate.employees', ['tab' => 'team'])->with('error', 'Only pending invitations can be sent again.');
        }
        $link = $this->newInviteLink($member);
        $member->save();
        return $this->sendInvite($member, $account, $link, 'Invitation sent again to ' . $member->email . '.');
    }

    public function updateRole(Request $request, $id)
    {
        $this->mustManageTeam();
        $member = $this->member($id);
        $data = $request->validate(['role' => ['required', Rule::in(array_keys(CompanyTeamMember::ROLES))]]);
        if ($member->user_id && (int) $member->user_id === (int) session('member_id')) {
            return redirect()->route('corporate.employees', ['tab' => 'team'])->with('error', 'You cannot change your own role.');
        }
        $member->update(['role' => $data['role']]);
        return redirect()->route('corporate.employees', ['tab' => 'team'])->with('success', $member->name . ' is now ' . $member->roleLabel() . '.');
    }

    public function remove($id)
    {
        $this->mustManageTeam();
        $member = $this->member($id);
        if ($member->user_id && (int) $member->user_id === (int) session('member_id')) {
            return redirect()->route('corporate.employees', ['tab' => 'team'])->with('error', 'You cannot remove yourself.');
        }
        $wasInvite = $member->status === 'invited';
        $member->update(['status' => 'removed', 'token_hash' => null]);
        return redirect()->route('corporate.employees', ['tab' => 'team'])
            ->with('success', $wasInvite ? 'Invitation to ' . $member->email . ' cancelled.' : $member->name . ' no longer has access to this account.');
    }

    /** GET /corporate-team/accept/{token}: the invited person sets up their login. */
    public function acceptForm($token)
    {
        [$member, $problem] = $this->invitation($token);
        $company = $member ? DB::table('storeusers')->where('id', $member->account_id)->first() : null;
        return view('web_frontend.team-accept', compact('member', 'problem', 'company', 'token'));
    }

    public function accept(Request $request, $token)
    {
        [$member, $problem] = $this->invitation($token);
        if ($problem) {
            return redirect()->route('team.accept', $token);
        }
        $data = $request->validate([
            'fname' => 'required|string|max:100',
            'lname' => 'nullable|string|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $owner = DB::table('storeusers')->where('id', $member->account_id)->first();

        $user = DB::transaction(function () use ($member, $data, $owner) {
            $user = new Storeuser();
            $user->fname = $data['fname'];
            $user->lname = $data['lname'] ?? '';
            $user->email = $member->email;
            $user->company = optional($owner)->company;
            $user->password = Hash::make($data['password']);
            $user->save();
            $member->update(['user_id' => $user->id, 'name' => trim($data['fname'] . ' ' . ($data['lname'] ?? '')), 'status' => 'active',
                'accepted_at' => now(), 'token_hash' => null]);
            return $user;
        });

        $request->session()->regenerate();
        session()->put('userid', $user->id); // CorporateTeamSession switches this to the company account
        session()->put('username', $user->email);
        session()->put('fullname', trim($user->fname . ' ' . $user->lname));
        return redirect()->route('corporate.portal')
            ->with('success', 'Welcome! You now have access to ' . (optional($owner)->company ?: 'the company account') . '.')
            ->with('account_created', ['name' => $user->fname, 'company' => optional($owner)->company ?: trim(optional($owner)->fname . ' ' . optional($owner)->lname), 'role' => $member->roleLabel()]);
    }

    /* ---------- Helpers ---------- */

    private function account()
    {
        $account = DB::table('storeusers')->where('id', session('userid'))->first();
        if (!$account) {
            abort(redirect()->route('corporate.login'));
        }
        return $account;
    }

    /** owner | admin | buyer | viewer */
    private function role()
    {
        return session('member_id') ? (string) session('member_role') : 'owner';
    }

    private function mustManageTeam()
    {
        if (!in_array($this->role(), ['owner', 'admin'], true)) {
            abort(redirect()->route('corporate.employees', ['tab' => 'team'])->with('error', 'Only the account owner or an admin can manage the team.'));
        }
    }

    private function employee($id)
    {
        return CompanyEmployee::where('account_id', $this->account()->id)->findOrFail($id);
    }

    private function member($id)
    {
        return CompanyTeamMember::where('account_id', $this->account()->id)->where('status', '!=', 'removed')->findOrFail($id);
    }

    private function validateEmployee(Request $request, $ignoreId = null)
    {
        $accountId = $this->account()->id;
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:150', Rule::unique('company_employees', 'email')->where('account_id', $accountId)->ignore($ignoreId)],
            'phone' => 'nullable|string|max:40',
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'birthday' => 'nullable|date|before:today',
            'joined_on' => 'nullable|date|before_or_equal:today',
            'notes' => 'nullable|string|max:2000',
        ], ['email.unique' => 'Someone on your list already has this email.']);
    }

    private function parseDate($value)
    {
        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'm/d/Y'] as $format) {
            $date = \DateTime::createFromFormat('!' . $format, $value);
            if ($date && $date->format($format) === $value) {
                return $date->format('Y-m-d');
            }
        }
        return null;
    }

    /** New invitation token (only its hash is stored); returns the link. */
    private function newInviteLink(CompanyTeamMember $member)
    {
        $token = Str::random(48);
        $member->token_hash = hash('sha256', $token);
        $member->invited_at = now();
        $member->expires_at = now()->addDays(CompanyTeamMember::INVITE_DAYS);
        return route('team.accept', $token);
    }

    /** [member, problem] for an invitation token. */
    private function invitation($token)
    {
        $member = CompanyTeamMember::where('token_hash', hash('sha256', (string) $token))->first();
        if (!$member || $member->status !== 'invited') {
            return [null, 'This invitation link is not valid any more. It may have been used already or cancelled.'];
        }
        if ($member->inviteExpired()) {
            return [$member, 'This invitation has expired. Ask the person who invited you to send it again.'];
        }
        if (Storeuser::whereRaw('LOWER(email) = ?', [strtolower($member->email)])->exists()) {
            return [$member, 'An account with ' . $member->email . ' already exists, so this invitation cannot be used. Ask for an invitation to a different email.'];
        }
        return [$member, null];
    }

    private function sendInvite(CompanyTeamMember $member, $account, $link, $sentMessage)
    {
        $company = $account->company ?: trim($account->fname . ' ' . $account->lname);
        try {
            Mail::send('mail.team_invite', ['member' => $member, 'company' => $company, 'inviter' => session('fullname'), 'link' => $link,
                'role' => $member->roleLabel(), 'days' => CompanyTeamMember::INVITE_DAYS], function ($m) use ($member, $company) {
                $m->to($member->email, $member->name)->subject('You are invited to ' . $company . ' on More Than Giftz');
            });
            return redirect()->route('corporate.employees', ['tab' => 'team'])->with('success', $sentMessage);
        } catch (\Throwable $e) {
            Log::error('Team invitation email to ' . $member->email . ' failed: ' . $e->getMessage());
            return redirect()->route('corporate.employees', ['tab' => 'team'])
                ->with('error', 'The invitation was saved but the email could not be sent. Share this link with ' . $member->name . ' (valid ' . CompanyTeamMember::INVITE_DAYS . ' days):')
                ->with('invite_link', $link);
        }
    }
}
