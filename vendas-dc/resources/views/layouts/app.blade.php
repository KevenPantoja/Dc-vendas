<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonte opcional -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Laravel Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body, html {
            height: 100%;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navegação -->
    @include('layouts.navigation')

    <!-- Header opcional -->
    @isset($header)
        <header class="bg-white shadow py-4">
            <div class="container">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Conteúdo principal -->
    <main class="d-flex flex-column justify-content-center align-items-center py-5" style="min-height: calc(100vh - 100px);">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
