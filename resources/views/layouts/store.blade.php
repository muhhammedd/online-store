<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.store.head')
</head>

<body class="@yield('body_class', 'goto-here')">
    @include('partials.store.navbar')

    @yield('content')

    @include('partials.store.footer')
</body>

</html>
