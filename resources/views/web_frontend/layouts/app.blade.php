<!doctype html>
<html lang="en">

<head>

@include('web_frontend.partials.head')
@include('web_frontend.partials.site-chrome-head')
</head>

<body class="mat-typography mtg-boxed">
@include('web_frontend.partials.site-loader')

@include('web_frontend.partials.home-header')

<div class="mtg-page">
@yield('content')
</div>

@include('web_frontend.partials.home-footer')

@include('web_frontend.partials.scripts')

<script src="{{ asset('web_frontend/assets/js/home.js') }}?v={{ filemtime(public_path('web_frontend/assets/js/home.js')) }}"></script>

@yield('scripts')

@include('web_frontend.partials.welcome-modal')

</body>

</html>
