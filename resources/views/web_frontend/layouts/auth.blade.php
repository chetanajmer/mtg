<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'More Than Giftz')</title>
  @yield('meta')

  <!-- Bootstrap 5 + Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('web_frontend/assets/css/reset.css') }}">
  <link rel="stylesheet" href="{{ asset('web_frontend/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('web_frontend/assets/css/font-awesome.css') }}">
  @yield('styles')
</head>

<body class="mat-typography">

@yield('content')

@include('web_frontend.partials.scripts')

@yield('scripts')

</body>

</html>
