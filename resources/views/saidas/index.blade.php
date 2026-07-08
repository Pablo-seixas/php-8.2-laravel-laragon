<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Saidas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Saidas</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('saidas.create') }}" class="btn btn-success mb-3">Nova Saida</a>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Voltar</a>

    <div class="alert alert-info">
        Total de saidas: {{ $saidas->count() }}
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Produto</th>
                <th>Setor</th>
                <th>Unidade</th>
                <th>Responsavel</th>
                <th>Quantidade</th>
                <th>Data</th>
                <th>Hora</th>
            </tr>
        </thead>

        <tbody>
        @foreach($saidas as $saida)
            <tr>
                <td>{{ $saida->produto->nome ?? 'N/A' }}</td>
                <td>{{ $saida->setor }}</td>
                <td>{{ $saida->unidade ?? '-' }}</td>
                <td>{{ $saida->responsavel ?? '-' }}</td>
                <td class="{{ $saida->quantidade > 10 ? 'text-danger' : 'text-dark' }}">
                    {{ $saida->quantidade }}
                </td>
                <td>{{ $saida->created_at ? $saida->created_at->format('d/m/Y') : '-' }}</td>
                <td>{{ $saida->created_at ? $saida->created_at->format('H:i') : '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





