<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Detalhes do Produto</h1>

    <div class="card card-body">
        <p><strong>Codigo:</strong> {{ $produto->codigo }}</p>
        <p><strong>Nome:</strong> {{ $produto->nome }}</p>
        <p><strong>Categoria:</strong> {{ $produto->categoria->nome ?? '-' }}</p>
        <p><strong>Quantidade:</strong> {{ $produto->quantidade }}</p>
        <p><strong>Estoque minimo:</strong> {{ $produto->estoque_minimo }}</p>
        <p><strong>Status:</strong> {{ $produto->quantidade <= $produto->estoque_minimo ? 'Baixo' : 'Normal' }}</p>
        <p><strong>Localizacao:</strong> {{ $produto->localizacao ?? '-' }}</p>
        <p><strong>Observacoes:</strong> {{ $produto->observacoes ?? '-' }}</p>
    </div>

    <a href="{{ route('produtos.index') }}" class="btn btn-secondary mt-3">Voltar</a>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





