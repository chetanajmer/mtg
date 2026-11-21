@extends('web_frontend.layouts.app')

@section('title', 'Employees | More Than Giftz')
@section('meta')
  <meta name="description" content="Your company's gift recipients and the team who can use this account.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/employees.css') }}">
@endsection

@section('content')
@php
  $userName = session()->get('fullname') ?? session()->get('username') ?? 'User';
  $fmt = function ($d) { return $d ? $d->format('d M Y') : '-'; };
  $employeeData = [];
  foreach ($employees as $e) {
    $employeeData[$e->id] = ['name' => $e->name, 'email' => $e->email, 'phone' => $e->phone, 'department' => $e->department, 'designation' => $e->designation,
      'address' => $e->address, 'city' => $e->city, 'birthday' => optional($e->birthday)->format('Y-m-d'), 'joined_on' => optional($e->joined_on)->format('Y-m-d'), 'notes' => $e->notes];
  }
@endphp

<div class="dashboard-wrapper">

<aside class="sidebar">
  <button class="company"><i class="bi bi-buildings"></i><span><strong>{{ $userName }}</strong><small>Switch Company <i class="bi bi-arrow-right"></i></small></span></button>
  <nav>
    <a href="{{ route('corporate.portal') }}"><i class="bi bi-gift"></i>Dashboard</a>
    <a href="/orders"><i class="bi bi-cart3"></i>Orders</a>
    <a href="/corporate-addresses"><i class="bi bi-geo-alt"></i>Addresses</a>
    <a href="{{ route('branding-assets.index') }}"><i class="bi bi-image"></i>Branding Assets</a>
    <a href="/collections-all"><i class="bi bi-bookmark"></i>Saved Collections</a>
    <a class="active" href="{{ route('corporate.employees') }}"><i class="bi bi-people-fill"></i>Employees</a>
    <a href="{{ route('corporate.reports') }}"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a href="{{ route('planner.index') }}"><i class="bi bi-calculator"></i>Budget Planner</a>
    <a href="/corporate-account-settings"><i class="bi bi-gear"></i>Account Settings</a>
    <a href="{{ route('corporate.logout') }}" style="color: #dc3545;"><i class="bi bi-box-arrow-right"></i>Logout</a>
  </nav>
  <div class="help"><i class="bi bi-headset"></i><div><strong>Need Help?</strong><p>Your dedicated account manager is here.</p></div><a class="outline full help-link" href="mailto:info@morethangiftz.com?subject=Help%20with%20my%20account">Contact Us <i class="bi bi-arrow-right"></i></a><a class="help-call" href="tel:+971557863450"><i class="bi bi-telephone"></i> +971 55 786 3450</a></div>
</aside>

<main class="content">
  <section class="hero" style="min-height: 150px;">
    <div style="width: 100%;">
      <nav style="margin-bottom: 10px; font-size: 14px; color: #6b7280;">
        <a href="{{ route('corporate.portal') }}" style="color: #6b7280; text-decoration: none;">Corporate Portal</a>
        <span style="margin: 0 8px;">›</span>
        <span style="color: #374151;">Employees</span>
      </nav>
      <h1>Employees</h1>
      <p>Keep your gift recipients in one place, and give colleagues their own login to this account.</p>
    </div>
  </section>

  @if(session('member_id'))
    <div class="em-flash info"><i class="bi bi-person-badge"></i> You are signed in as <strong>{{ $userName }}</strong> ({{ $roles[$role]['label'] ?? $role }}) on {{ session('member_company') ?: 'this company account' }}.</div>
  @endif
  @if(session('success'))<div class="em-flash ok"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>@endif
  @if(session('error'))
    <div class="em-flash err"><i class="bi bi-exclamation-circle"></i> {{ session('error') }}
      @if(session('invite_link'))<div class="em-link"><input type="text" readonly value="{{ session('invite_link') }}" onclick="this.select()" aria-label="Invitation link"></div>@endif
    </div>
  @endif
  @if(session('import_errors'))
    <div class="em-flash err"><strong>Some rows were not imported:</strong><ul>@foreach(session('import_errors') as $msg)<li>{{ $msg }}</li>@endforeach</ul></div>
  @endif
  @if($errors->any())
    <div class="em-flash err">@foreach($errors->all() as $msg)<div>{{ $msg }}</div>@endforeach</div>
  @endif

  <div class="em-tabs" role="tablist">
    <a href="{{ route('corporate.employees') }}" class="{{ $tab === 'employees' ? 'active' : '' }}" role="tab"><i class="bi bi-gift"></i> Gift recipients <span>{{ $total }}</span></a>
    <a href="{{ route('corporate.employees', ['tab' => 'team']) }}" class="{{ $tab === 'team' ? 'active' : '' }}" role="tab"><i class="bi bi-person-lock"></i> Team logins <span>{{ $team->count() + 1 }}</span></a>
  </div>

  @if($tab === 'employees')
    {{-- ================= Gift recipients ================= --}}
    @if($upcoming->count())
      <section class="em-panel em-upcoming">
        <h2><i class="bi bi-calendar-heart"></i> Coming up in the next 30 days</h2>
        <ul>
          @foreach($upcoming->take(8) as $o)
            <li>
              <span class="em-when">{{ $o['date']->isToday() ? 'Today' : $o['date']->format('d M') }}</span>
              <span><strong>{{ $o['employee']->name }}</strong><small>{{ $o['type'] }}{{ $o['years'] ? ' · ' . $o['years'] . ' year' . ($o['years'] == 1 ? '' : 's') : '' }}{{ $o['employee']->department ? ' · ' . $o['employee']->department : '' }}</small></span>
            </li>
          @endforeach
        </ul>
        <p class="em-hint">Planning a gift? <a href="{{ url('/') }}">Browse products</a> or <a href="/collections-all">use a saved collection</a>.</p>
      </section>
    @endif

    <section class="em-toolbar">
      <form method="GET" action="{{ route('corporate.employees') }}" class="em-search">
        <i class="bi bi-search"></i>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search name, email, department, city" aria-label="Search employees">
        @if($departments->count())
          <select name="department" onchange="this.form.submit()" aria-label="Department">
            <option value="">All departments</option>
            @foreach($departments as $d)<option value="{{ $d }}" {{ $department === $d ? 'selected' : '' }}>{{ $d }}</option>@endforeach
          </select>
        @endif
        <button type="submit" class="em-btn outline">Search</button>
        @if($search !== '' || $department !== '')<a href="{{ route('corporate.employees') }}" class="em-clear">Clear</a>@endif
      </form>
      <div class="em-actions">
        @if($canEdit)
          <button type="button" class="em-btn dark" onclick="openEmployee()"><i class="bi bi-person-plus"></i> Add employee</button>
          <button type="button" class="em-btn outline" onclick="document.getElementById('importBox').classList.toggle('open')"><i class="bi bi-upload"></i> Import CSV</button>
        @endif
        @if($total)<a class="em-btn outline" href="{{ route('corporate.employees.export') }}"><i class="bi bi-download"></i> Export</a>@endif
      </div>
    </section>

    @if($canEdit)
      <section class="em-panel em-import {{ $errors->has('file') ? 'open' : '' }}" id="importBox">
        <h2>Import employees from a CSV file</h2>
        <p class="em-hint">Columns: name (required), email, phone, department, designation, address, city, birthday, joined_on, notes. Dates as YYYY-MM-DD. People whose email is already on your list are skipped.
          <a href="{{ route('corporate.employees.template') }}">Download the template</a></p>
        <form method="POST" action="{{ route('corporate.employees.import') }}" enctype="multipart/form-data" class="em-import-form">@csrf
          <input type="file" name="file" accept=".csv,text/csv" required aria-label="CSV file">
          <button class="em-btn dark">Import</button>
        </form>
      </section>
    @endif

    <section class="em-panel">
      @if($employees->count())
        <div class="em-table-wrap">
          <table class="em-table">
            <thead><tr><th>Name</th><th>Department</th><th>Contact</th><th>City</th><th>Birthday</th><th>Joined</th>@if($canEdit)<th><span class="sr-only">Actions</span></th>@endif</tr></thead>
            <tbody>
              @foreach($employees as $e)
                <tr id="employee-{{ $e->id }}">
                  <td data-label="Name"><strong>{{ $e->name }}</strong>@if($e->designation)<small>{{ $e->designation }}</small>@endif</td>
                  <td data-label="Department">{{ $e->department ?: '-' }}</td>
                  <td data-label="Contact">@if($e->email)<a href="mailto:{{ $e->email }}">{{ $e->email }}</a>@endif @if($e->phone)<small>{{ $e->phone }}</small>@endif @if(!$e->email && !$e->phone)-@endif</td>
                  <td data-label="City">{{ $e->city ?: '-' }}</td>
                  <td data-label="Birthday">{{ $e->birthday ? $e->birthday->format('d M') : '-' }}</td>
                  <td data-label="Joined">{{ $fmt($e->joined_on) }}</td>
                  @if($canEdit)
                    <td class="em-row-actions">
                      <button type="button" class="em-icon" onclick="openEmployee({{ $e->id }})" aria-label="Edit {{ $e->name }}"><i class="bi bi-pencil"></i></button>
                      <button type="button" class="em-icon danger" onclick="deleteEmployee({{ $e->id }})" aria-label="Remove {{ $e->name }}"><i class="bi bi-trash"></i></button>
                    </td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @if($employees->hasPages())<div class="em-pages">{{ $employees->links() }}</div>@endif
      @elseif($search !== '' || $department !== '')
        <div class="em-empty"><i class="bi bi-search"></i><p>No employees match your search. <a href="{{ route('corporate.employees') }}">Show everyone</a></p></div>
      @else
        <div class="em-empty">
          <i class="bi bi-people"></i>
          <h2>No employees yet</h2>
          <p>Add the people you send gifts to, with their department, delivery address, birthday and work anniversary. You'll see whose occasion is coming up.</p>
          @if($canEdit)<button type="button" class="em-btn dark" onclick="openEmployee()"><i class="bi bi-person-plus"></i> Add your first employee</button>@endif
        </div>
      @endif
    </section>
  @else
    {{-- ================= Team logins ================= --}}
    <section class="em-panel">
      <h2>People who can log in to this account</h2>
      <p class="em-hint">Everyone here sees the same orders, collections, quote cart and reports. Each person signs in with their own email and password.</p>
      <div class="em-table-wrap">
        <table class="em-table">
          <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th>@if($canManageTeam)<th><span class="sr-only">Actions</span></th>@endif</tr></thead>
          <tbody>
            <tr>
              <td data-label="Name"><strong>{{ trim($account->fname . ' ' . $account->lname) ?: $account->email }}</strong></td>
              <td data-label="Email">{{ $account->email }}</td>
              <td data-label="Role"><span class="em-role owner">Owner</span></td>
              <td data-label="Status"><span class="em-status active">Active</span></td>
              @if($canManageTeam)<td></td>@endif
            </tr>
            @foreach($team as $m)
              @php $self = $m->user_id && (int) $m->user_id === (int) session('member_id'); @endphp
              <tr>
                <td data-label="Name"><strong>{{ $m->name }}</strong>@if($self)<small>(you)</small>@endif</td>
                <td data-label="Email">{{ $m->email }}</td>
                <td data-label="Role">
                  @if($canManageTeam && !$self)
                    <form method="POST" action="{{ route('team.role', $m->id) }}" class="em-inline">@csrf
                      <select name="role" onchange="this.form.submit()" aria-label="Role for {{ $m->name }}">
                        @foreach($roles as $key => $r)<option value="{{ $key }}" {{ $m->role === $key ? 'selected' : '' }}>{{ $r['label'] }}</option>@endforeach
                      </select>
                    </form>
                  @else
                    <span class="em-role {{ $m->role }}">{{ $m->roleLabel() }}</span>
                  @endif
                </td>
                <td data-label="Status">
                  @if($m->status === 'active')
                    <span class="em-status active">Active</span><small>since {{ $fmt($m->accepted_at) }}</small>
                  @elseif($m->inviteExpired())
                    <span class="em-status expired">Invitation expired</span>
                  @else
                    <span class="em-status invited">Invited</span><small>link valid until {{ $fmt($m->expires_at) }}</small>
                  @endif
                </td>
                @if($canManageTeam)
                  <td class="em-row-actions">
                    @if($m->status === 'invited')
                      <form method="POST" action="{{ route('team.resend', $m->id) }}" class="em-inline">@csrf<button class="em-btn small outline">Send again</button></form>
                    @endif
                    @if(!$self)
                      <form method="POST" action="{{ route('team.remove', $m->id) }}" class="em-inline" onsubmit="return confirm(@json($m->status === 'invited' ? 'Cancel the invitation to ' . $m->email . '?' : 'Remove ' . $m->name . '? They will lose access to this account straight away.'))">@csrf
                        <button class="em-btn small danger">{{ $m->status === 'invited' ? 'Cancel' : 'Remove' }}</button>
                      </form>
                    @endif
                  </td>
                @endif
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>

    @if($canManageTeam)
      <section class="em-panel">
        <h2>Invite a colleague</h2>
        <form method="POST" action="{{ route('team.invite') }}" class="em-invite">@csrf
          <label>Name<input type="text" name="name" value="{{ old('name') }}" required maxlength="120"></label>
          <label>Work email<input type="email" name="email" value="{{ old('email') }}" required maxlength="150"></label>
          <label>Role
            <select name="role" required>
              @foreach($roles as $key => $r)<option value="{{ $key }}" {{ old('role', 'buyer') === $key ? 'selected' : '' }}>{{ $r['label'] }}</option>@endforeach
            </select>
          </label>
          <button class="em-btn dark"><i class="bi bi-send"></i> Send invitation</button>
        </form>
        <p class="em-hint">They get an email with a link (valid {{ \App\Models\CompanyTeamMember::INVITE_DAYS }} days) to set their name and password.</p>
      </section>
    @else
      <div class="em-flash info"><i class="bi bi-info-circle"></i> Only the account owner or an admin can invite or remove people.</div>
    @endif

    <section class="em-panel">
      <h2>What each role can do</h2>
      <ul class="em-roles">
        <li><span class="em-role owner">Owner</span> Everything, including the account settings and password.</li>
        @foreach($roles as $key => $r)<li><span class="em-role {{ $key }}">{{ $r['label'] }}</span> {{ $r['hint'] }}.</li>@endforeach
      </ul>
    </section>
  @endif
</main>
</div>

@if($tab === 'employees' && $canEdit)
<div id="employeeModal" class="em-modal" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="employeeTitle">
  <form class="em-modal-box" onsubmit="saveEmployee(event)">
    <div class="em-modal-head"><h2 id="employeeTitle">Add employee</h2><button type="button" class="em-close" onclick="closeEmployee()" aria-label="Close">&times;</button></div>
    <div class="em-modal-body">
      <div class="em-grid">
        <label class="full">Full name *<input type="text" id="f-name" required maxlength="255"></label>
        <label>Email<input type="email" id="f-email" maxlength="150"></label>
        <label>Phone<input type="text" id="f-phone" maxlength="40"></label>
        <label>Department<input type="text" id="f-department" maxlength="100" list="departmentList"></label>
        <label>Designation<input type="text" id="f-designation" maxlength="100"></label>
        <label class="full">Delivery address<input type="text" id="f-address" maxlength="500"></label>
        <label>City<input type="text" id="f-city" maxlength="100"></label>
        <label>Birthday<input type="date" id="f-birthday"></label>
        <label>Joined on<input type="date" id="f-joined_on"></label>
        <label class="full">Notes<textarea id="f-notes" rows="2" maxlength="2000" placeholder="Sizes, preferences, allergies…"></textarea></label>
      </div>
      <datalist id="departmentList">@foreach($departments as $d)<option value="{{ $d }}">@endforeach</datalist>
      <p class="em-error" id="employeeError" role="alert"></p>
    </div>
    <div class="em-modal-actions">
      <button type="button" class="em-btn outline" onclick="closeEmployee()">Cancel</button>
      <button type="submit" class="em-btn dark" id="employeeSave">Save</button>
    </div>
  </form>
</div>
@endif
@endsection

@section('scripts')
@if($tab === 'employees' && $canEdit)
<script>
const employees = @json($employeeData);
const fields = ['name', 'email', 'phone', 'department', 'designation', 'address', 'city', 'birthday', 'joined_on', 'notes'];
const baseUrl = '{{ url('corporate-employees') }}';
let editing = null;

function openEmployee(id) {
  editing = id || null;
  const data = id ? employees[id] : {};
  fields.forEach(f => document.getElementById('f-' + f).value = data[f] || '');
  document.getElementById('employeeTitle').textContent = id ? 'Edit employee' : 'Add employee';
  document.getElementById('employeeError').textContent = '';
  document.getElementById('employeeModal').style.display = 'flex';
  document.getElementById('f-name').focus();
}

function closeEmployee() {
  document.getElementById('employeeModal').style.display = 'none';
}

document.getElementById('employeeModal').addEventListener('click', function (e) { if (e.target === this) closeEmployee(); });
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeEmployee(); });

function send(url, method, body) {
  return fetch(url, { method: method, headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: body ? JSON.stringify(body) : undefined })
    .then(r => r.json().catch(() => ({})).then(d => ({ ok: r.ok && d.success !== false, data: d })));
}

function firstError(d, fallback) {
  if (d && d.errors) { const k = Object.keys(d.errors)[0]; return d.errors[k][0]; }
  return (d && d.message) || fallback;
}

function saveEmployee(event) {
  event.preventDefault();
  const body = {};
  fields.forEach(f => body[f] = document.getElementById('f-' + f).value.trim() || null);
  const button = document.getElementById('employeeSave');
  button.disabled = true;
  send(editing ? baseUrl + '/' + editing : baseUrl, editing ? 'PUT' : 'POST', body).then(({ ok, data }) => {
    button.disabled = false;
    if (!ok) { document.getElementById('employeeError').textContent = firstError(data, 'Could not save. Please check the details.'); return; }
    location.reload();
  }).catch(() => { button.disabled = false; document.getElementById('employeeError').textContent = 'Could not save. Please check your connection.'; });
}

function deleteEmployee(id) {
  if (!confirm('Remove ' + employees[id].name + ' from your employee list?')) return;
  send(baseUrl + '/' + id, 'DELETE').then(({ ok, data }) => {
    if (!ok) { alert(firstError(data, 'Could not remove.')); return; }
    document.getElementById('employee-' + id).remove();
  });
}
</script>
@endif
@endsection
