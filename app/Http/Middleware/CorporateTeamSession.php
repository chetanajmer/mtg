<?php

namespace App\Http\Middleware;

use App\Models\CompanyTeamMember;
use Closure;
use Illuminate\Http\Request;

/**
 * Team logins for the corporate portal. When a team member logs in (by any
 * login path) the session is switched to their company's account: "userid"
 * becomes the owner's id, so orders, collections, cart and reports are
 * shared, and "member_id" / "member_role" keep who they really are.
 * The membership is checked on every request (removed members are logged
 * out) and the role is enforced on the server.
 */
class CorporateTeamSession
{
    private const MEMBER_KEYS = ['member_id', 'member_role', 'member_company', 'member_account'];

    public function handle(Request $request, Closure $next)
    {
        $session = $request->session();

        // Logging in or out, or a new login that replaced "userid": old member details no longer apply
        if ($session->has('member_id') && ($this->isLoginOrLogout($request) || (int) $session->get('userid') !== (int) $session->get('member_account'))) {
            $session->forget(self::MEMBER_KEYS);
        }

        if ($session->has('member_id')) {
            $member = CompanyTeamMember::where('user_id', $session->get('member_id'))->where('account_id', $session->get('userid'))
                ->where('status', 'active')->first();
            if (!$member) {
                $session->forget(array_merge(['userid', 'username', 'fullname'], self::MEMBER_KEYS));
                return redirect()->route('corporate.login')->with('error', 'Your access to this company account was removed. Please contact the account owner.');
            }
            $session->put('member_role', $member->role); // a role change applies straight away
        } elseif ($session->has('userid') && !$this->isLoginOrLogout($request)) {
            $member = CompanyTeamMember::where('user_id', $session->get('userid'))->where('status', 'active')->first();
            if ($member) {
                $owner = \DB::table('storeusers')->where('id', $member->account_id)->first();
                $session->put('member_id', $member->user_id);
                $session->put('member_role', $member->role);
                $session->put('member_company', $owner ? ($owner->company ?: trim($owner->fname . ' ' . $owner->lname)) : null);
                $session->put('member_account', $member->account_id);
                $session->put('userid', $member->account_id);
            }
        }

        if (($role = $session->get('member_role')) && ($message = $this->blocked($request, $role))) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 403)
                : redirect()->back()->with('error', $message);
        }

        return $next($request);
    }

    /** Why this member may not make this request, or null if allowed. */
    private function blocked(Request $request, $role)
    {
        if ($request->isMethodSafe() || $this->isLoginOrLogout($request)) {
            return null;
        }
        $label = CompanyTeamMember::ROLES[$role]['label'] ?? $role;
        if ($role === 'viewer') {
            return "Your role ($label) can view this account but not make changes. Ask the account owner if you need more access.";
        }
        if ($request->is('corporate-account-settings')) {
            return 'Only the account owner can change the account settings and password.';
        }
        if ($role === 'buyer' && $request->is('corporate-employees/team', 'corporate-employees/team/*')) {
            return "Your role ($label) cannot manage the team. Ask the account owner or an admin.";
        }
        return null;
    }

    private function isLoginOrLogout(Request $request)
    {
        return $request->is('corporate/logout', 'store/dologout', 'logout', 'corporate/auth/google/callback')
            || (!$request->isMethodSafe() && $request->is('corporate/login', 'corporate/register', 'store/dologin', 'store/doregister', 'cart/register'));
    }
}
