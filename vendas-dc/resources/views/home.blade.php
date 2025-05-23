<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bem-vindo ao DC Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light d-flex flex-column justify-content-center align-items-center vh-100">

    <div class="text-center">
        <h1 class="mb-4">Bem-vindo ao Sistema DC Vendas</h1>
        <p class="mb-4">Gerencie suas vendas de forma simples e rápida.</p>

        <a href="{{ route('login') }}" class="btn btn-primary me-2">Fazer Login</a>
        <a href="{{ route('register') }}" class="btn btn-success">Criar Conta</a>
    </div>

</body>
</html>
