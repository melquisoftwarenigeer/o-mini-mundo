<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mini Mundo')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/js/app.js'])
</head>

<body>
    @isset(auth()->user()->name)
        <x-header />
    @endisset
    <main class="container mt-5">
        @yield('content')
    </main>
</body>

@yield('script')

</html>