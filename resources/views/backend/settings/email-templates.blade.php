@extends('layouts.app')
@section('content')
<style>
  .et-admin .et-status td { padding: 6px 10px; }
  .et-admin .et-status td:first-child { color: #6b7280; width: 170px; }
  .et-admin .et-list a.list-group-item.active { background: #15171a; border-color: #15171a; color: #fff; }
  .et-admin .et-list small { display: block; color: #6b7280; }
  .et-admin .et-list a.active small { color: #d1d5db; }
  .et-admin .et-tag { display: inline-block; padding: 0 7px; margin-left: 4px; border-radius: 8px; font-size: 11px; background: #eef0f3; color: #15171a; }
  .et-admin .et-tag.staff { background: #f6eedc; color: #7a5a14; }
  .et-admin .et-frame { width: 100%; height: 760px; border: 1px solid #e5e7eb; border-radius: 6px; background: #f6f1ed; }
  .et-admin .et-warn { color: #b45309; }
</style>
<div class="content-page">
  <div class="content">
    <div class="container et-admin">
      <div class="row">
        <div class="col-sm-12">
          <h4 class="page-title">Email Templates</h4>
          <ol class="breadcrumb"><li>Every email the website sends, shown with sample data. Wording is in <code>resources/views/mail</code>; company details in <code>config/company.php</code>.</li></ol>
        </div>
      </div>
      @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
      @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

      <div class="card-box">
        <h5 style="margin-top:0;font-weight:600;">Mail settings (from .env)</h5>
        <table class="et-status">
          <tr><td>Sending</td><td>
            @if($mail['mailer'] === 'smtp')<strong>SMTP</strong> via {{ $mail['host'] }}
            @elseif($mail['mailer'] === 'log')<strong class="et-warn">Log only</strong>: emails are written to storage/logs/laravel.log, not sent. Set MAIL_MAILER=smtp and the mailbox details to send for real.
            @else<strong>{{ $mail['mailer'] }}</strong>@endif
          </td></tr>
          <tr><td>From</td><td>{{ $mail['fromName'] }} &lt;{{ $mail['from'] ?: 'not set' }}&gt;</td></tr>
          <tr><td>Staff inbox</td><td>{{ $mail['staff'] ?: 'not set' }} <small class="text-muted">(quotes, contact messages and customer responses; QUOTE_REQUEST_EMAIL)</small></td></tr>
          <tr><td>Queue</td><td>{{ $mail['queue'] === 'sync' ? 'Sent straight away' : 'Queued (' . $mail['queue'] . '): needs the queue worker running' }}</td></tr>
        </table>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="list-group et-list" role="tablist">
            @foreach($templates as $key => $t)
              <a href="#" class="list-group-item {{ $loop->first ? 'active' : '' }}" data-key="{{ $key }}" data-src="{{ route('email-templates.show', $key) }}" data-subject="{{ $t['subject'] }}" data-test="{{ route('email-templates.test', $key) }}" role="tab">
                <strong>{{ $t['name'] }}</strong><span class="et-tag {{ $t['to'] === 'Staff' ? 'staff' : '' }}">{{ $t['to'] }}</span>
                <small>{{ $t['when'] }}</small>
              </a>
            @endforeach
          </div>
        </div>
        <div class="col-md-8">
          @php $first = reset($templates); $firstKey = array_key_first($templates); @endphp
          <div class="card-box">
            <p style="margin:0 0 4px;"><small class="text-muted">Subject</small><br><strong id="et-subject">{{ $first['subject'] }}</strong></p>
            <form method="post" id="et-test" action="{{ route('email-templates.test', $firstKey) }}" style="margin:10px 0 12px;">
              @csrf
              <button type="submit" class="btn btn-primary btn-sm" @if(!$adminEmail) disabled @endif>Send me a test</button>
              <small class="text-muted">to {{ $adminEmail ?: 'your admin email (not set)' }}, with sample data</small>
            </form>
            <iframe id="et-frame" class="et-frame" src="{{ route('email-templates.show', $firstKey) }}" title="Email preview" sandbox="allow-same-origin"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.querySelectorAll('.et-list a').forEach(function (link) {
    link.addEventListener('click', function (event) {
      event.preventDefault();
      document.querySelectorAll('.et-list a').forEach(function (a) { a.classList.remove('active'); });
      link.classList.add('active');
      document.getElementById('et-frame').src = link.dataset.src;
      document.getElementById('et-subject').textContent = link.dataset.subject;
      document.getElementById('et-test').action = link.dataset.test;
    });
  });
</script>
@endsection
