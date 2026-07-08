<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Trocar Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5" style="max-width: 450px;">

    <h2>Trocar Senha</h2>

    <form method="POST" action="{{ route('senha.atualizar') }}" class="card card-body">
        @csrf

        <label>Nova senha</label>
        <input type="password" name="password" class="form-control mb-3" required>

        <button class="btn btn-success">Salvar nova senha</button>
    </form>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





