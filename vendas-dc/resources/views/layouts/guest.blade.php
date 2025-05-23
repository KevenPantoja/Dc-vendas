<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Opcional: sua fonte personalizada -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts (Laravel Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body, html {
            height: 100%;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container h-100 d-flex flex-column justify-content-center align-items-center">
        <!-- Logo -->
        <div class="mb-4">
            <a href="/">
                <img src="{{ asset('lg.png') }}" alt="Logo" style="max-height: 100px;" class="img-fluid">
            </a>
        </div>

        <!-- Conteúdo central -->
        <div class="card shadow w-100" style="max-width: 400px;">
            <div class="card-body">
                {{ $slot }}
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
