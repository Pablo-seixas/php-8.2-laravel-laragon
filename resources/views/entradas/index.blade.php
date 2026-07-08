<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entradas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Entradas</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('entradas.create') }}" class="btn btn-success mb-3">Nova entrada</a>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Voltar</a>

    <div class="alert alert-info">
        Total de entradas: {{ $entradas->count() }}
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Fornecedor</th>
                <th>Responsavel</th>
                <th>Unidade</th>
                <th>Data</th>
                <th>Hora</th>
                <th>Acoes</th>
            </tr>
        </thead>

        <tbody>
        @foreach($entradas as $entrada)
            <tr>
                <td>{{ $entrada->produto->nome ?? 'N/A' }}</td>
                <td class="{{ $entrada->quantidade > 10 ? 'text-success fw-bold' : 'text-dark' }}">
                    {{ $entrada->quantidade }}
                </td>
                <td>{{ $entrada->fornecedor ?? '-' }}</td>
                <td>{{ $entrada->responsavel ?? '-' }}</td>
                <td>{{ $entrada->unidade ?? '-' }}</td>
                <td>{{ $entrada->created_at ? $entrada->created_at->format('d/m/Y') : '-' }}</td>
                <td>{{ $entrada->created_at ? $entrada->created_at->format('H:i') : '-' }}</td>
                <td>
                    <form method="POST" action="{{ route('entradas.destroy', $entrada) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Excluir entrada?')">Excluir</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





