<!doctype html>
<html lang="en">

<head>
@include('web_frontend.partials.head')
</head>

<body class="mat-typography">

@yield('content')

@include('web_frontend.partials.scripts')

@yield('scripts')

</body>

</html>
