<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Categorias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Categorias</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('categorias.store') }}" class="card card-body mb-4">
        @csrf

        <label>Nova categoria</label>
        <input name="nome" class="form-control mb-3" required>

        <button class="btn btn-success">Cadastrar</button>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Categoria</th>
                <th>Acoes</th>
            </tr>
        </thead>

        <tbody>
        @foreach($categorias as $categoria)
            <tr>
                <td>{{ $categoria->nome }}</td>
                <td>
                    <form method="POST" action="{{ route('categorias.destroy', $categoria) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Excluir categoria?')">Excluir</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





