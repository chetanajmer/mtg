{{-- Shown once, right after someone creates an account (session flash "account_created") --}}
@if(is_array($welcome = session('account_created')))
@php
  $onDashboard = request()->routeIs('corporate.portal');
  $team = $welcome['company'] ?? null;
@endphp
<div class="wm-backdrop" id="welcomeModal" role="dialog" aria-modal="true" aria-labelledby="welcomeTitle" aria-describedby="welcomeText">
  <div class="wm-box">
    <div class="wm-icon" aria-hidden="true"><i class="bi bi-check-lg"></i></div>
    @if($team)
      <h2 id="welcomeTitle">Welcome to {{ $team }}, {{ $welcome['name'] }}!</h2>
      <p id="welcomeText">Your login is ready. You're part of the {{ $team }} account as <strong>{{ $welcome['role'] ?? 'a team member' }}</strong>, so you'll see the same orders, quotes and collections as your colleagues.</p>
    @else
      <h2 id="welcomeTitle">Welcome to More Than Giftz{{ !empty($welcome['name']) ? ', ' . $welcome['name'] : '' }}!</h2>
      <p id="welcomeText">Your account is ready. You can now request quotes, save product collections and follow every order from your dashboard.</p>
    @endif
    <div class="wm-actions">
      @if($onDashboard)
        <button type="button" class="wm-btn wm-dark" data-close>Get started</button>
      @else
        <a href="{{ route('corporate.portal') }}" class="wm-btn wm-dark">Go to my dashboard</a>
        <button type="button" class="wm-btn wm-light" data-close>Continue browsing</button>
      @endif
    </div>
  </div>
</div>
<style>
  .wm-backdrop { position: fixed; inset: 0; background: rgba(15, 17, 20, .55); display: flex; align-items: center; justify-content: center; padding: 16px; z-index: 2000; animation: wm-fade .2s ease-out; }
  .wm-box { background: #fff; border-radius: 14px; width: 100%; max-width: 440px; padding: 30px 28px 24px; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,.25); animation: wm-pop .25s ease-out; }
  .wm-icon { width: 64px; height: 64px; margin: 0 auto 16px; border-radius: 50%; background: #e4f4e8; color: #17752c; display: flex; align-items: center; justify-content: center; font-size: 34px; }
  .wm-box h2 { font-family: Georgia, "Times New Roman", serif; font-size: 24px; line-height: 1.25; color: #15171a; margin: 0 0 10px; }
  .wm-box p { color: #4b5563; font-size: 15px; line-height: 1.6; margin: 0 0 22px; }
  .wm-actions { display: flex; flex-direction: column; gap: 10px; }
  .wm-btn { display: block; width: 100%; padding: 12px 16px; border-radius: 8px; font-weight: 600; font-size: 15px; text-decoration: none; cursor: pointer; border: 1px solid transparent; }
  .wm-dark { background: #15171a; color: #fff; }
  .wm-dark:hover { background: #2d3035; color: #fff; }
  .wm-light { background: #fff; color: #15171a; border-color: #d1d5db; }
  .wm-light:hover { border-color: #15171a; }
  @keyframes wm-fade { from { opacity: 0; } to { opacity: 1; } }
  @keyframes wm-pop { from { transform: translateY(12px) scale(.97); opacity: 0; } to { transform: none; opacity: 1; } }
  @media (prefers-reduced-motion: reduce) { .wm-backdrop, .wm-box { animation: none; } }
</style>
<script>
  (function () {
    var modal = document.getElementById('welcomeModal');
    var previous = document.activeElement;
    function close() {
      modal.remove();
      document.removeEventListener('keydown', onKey);
      if (previous && previous.focus) previous.focus();
    }
    function onKey(e) { if (e.key === 'Escape') close(); }
    modal.addEventListener('click', function (e) { if (e.target === modal || e.target.hasAttribute('data-close')) close(); });
    document.addEventListener('keydown', onKey);
    var first = modal.querySelector('.wm-btn');
    if (first) first.focus();
  })();
</script>
@endif
