<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Entrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Registrar Entrada</h1>

    <form method="POST" action="{{ route('entradas.store') }}" class="card card-body">
        @csrf

        <label>Produto</label>
        <select name="produto_id" class="form-control mb-3" required>
            @foreach($produtos as $produto)
                <option value="{{ $produto->id }}">
                    {{ $produto->nome }} | Estoque atual: {{ $produto->quantidade }}
                </option>
            @endforeach
        </select>

        <label>Quantidade</label>
        <input type="number" name="quantidade" class="form-control mb-3" min="1" required>

        <label>Fornecedor</label>
        <input name="fornecedor" class="form-control mb-3">

        <label>Responsavel</label>
        <input name="responsavel" class="form-control mb-3">

        <label>Unidade</label>
        <input name="unidade" class="form-control mb-3">

        <label>Observacao</label>
        <textarea name="observacao" class="form-control mb-3"></textarea>

        <button class="btn btn-success">Salvar Entrada</button>
        <a href="{{ route('entradas.index') }}" class="btn btn-secondary mt-2">Voltar</a>
    </form>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





