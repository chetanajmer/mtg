<!doctype html>
<html lang="en">

<head>

@include('web_frontend.partials.head')
@include('web_frontend.partials.site-chrome-head')
</head>

<body class="mat-typography mh-body">
@include('web_frontend.partials.site-loader')

@include('web_frontend.partials.home-header')

@yield('content')

@include('web_frontend.partials.home-footer')

@include('web_frontend.partials.save-to-collection')

@include('web_frontend.partials.scripts')

<script src="{{ asset('web_frontend/assets/js/home.js') }}?v={{ filemtime(public_path('web_frontend/assets/js/home.js')) }}"></script>
@yield('scripts')

@include('web_frontend.partials.welcome-modal')

</body>

</html>
