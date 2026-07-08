<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Acesso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5" style="max-width: 450px;">

    <h2 class="mb-4">Acesso ao Sistema</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/acesso" class="card card-body">
        @csrf

        <label>Email</label>
        <input name="email" class="form-control mb-3" required>

        <label>Senha</label>
        <input type="password" name="password" class="form-control mb-3" required>

        <button class="btn btn-primary">Entrar</button>
    </form>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





