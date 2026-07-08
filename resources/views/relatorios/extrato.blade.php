<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Extrato de Movimentacoes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Extrato de Movimentacoes</h1>

    <form method="GET" class="card card-body mb-4">
        <div class="row">

            <div class="col-md-3 mb-2">
                <label>Produto</label>
                <input name="produto" class="form-control" value="{{ request('produto') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Codigo</label>
                <input name="codigo" class="form-control" value="{{ request('codigo') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Tipo</label>
                <select name="tipo" class="form-control">
                    <option value="">Todos</option>
                    <option value="entrada" {{ request('tipo') === 'entrada' ? 'selected' : '' }}>Entrada</option>
                    <option value="saida" {{ request('tipo') === 'saida' ? 'selected' : '' }}>Saida</option>
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <label>Responsavel</label>
                <input name="responsavel" class="form-control" value="{{ request('responsavel') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Unidade</label>
                <input name="unidade" class="form-control" value="{{ request('unidade') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Data inicial</label>
                <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Data final</label>
                <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
            </div>

        </div>

        <button class="btn btn-primary mt-3">Filtrar</button>
        <a href="{{ route('relatorio.extrato') }}" class="btn btn-secondary mt-2">Limpar</a>
    </form>

    <div class="alert alert-info">
        Total encontrado: {{ $movimentacoes->count() }}
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Data</th>
                <th>Hora</th>
                <th>Tipo</th>
                <th>Produto</th>
                <th>Codigo</th>
                <th>Quantidade</th>
                <th>Responsavel</th>
                <th>Unidade</th>
            </tr>
        </thead>

        <tbody>
        @foreach($movimentacoes as $mov)
            <tr>
                <td>{{ \Carbon\Carbon::parse($mov->created_at)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($mov->created_at)->format('H:i') }}</td>
                <td class="{{ $mov->tipo === 'entrada' ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                    {{ strtoupper($mov->tipo) }}
                </td>
                <td>{{ $mov->produto }}</td>
                <td>{{ $mov->codigo }}</td>
                <td class="{{ $mov->tipo === 'entrada' ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                    {{ $mov->sinal }}{{ $mov->quantidade }}
                </td>
                <td>{{ $mov->responsavel ?? '-' }}</td>
                <td>{{ $mov->unidade ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





