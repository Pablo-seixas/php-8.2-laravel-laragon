<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Saida</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Registrar Saida</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('saidas.store') }}" class="card card-body">
        @csrf

        <div class="mb-3">
            <label>Produto</label>
            <select name="produto_id" class="form-control" required>
                @foreach($produtos as $produto)
                    <option value="{{ $produto->id }}">
                        {{ $produto->nome }} | Estoque: {{ $produto->quantidade }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Quantidade</label>
            <input type="number" name="quantidade" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label>Setor</label>
            <input name="setor" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Unidade</label>
            <input name="unidade" class="form-control">
        </div>

        <div class="mb-3">
            <label>Responsavel</label>
            <input name="responsavel" class="form-control">
        </div>

        <div class="mb-3">
            <label>Observacao</label>
            <textarea name="observacao" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary">Salvar Saida</button>
        <a href="{{ route('saidas.index') }}" class="btn btn-secondary mt-2">Voltar</a>
    </form>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





