<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatorio de Saidas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Relatorio de Saidas</h1>

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
                <label>Categoria</label>
                <input name="categoria" class="form-control" value="{{ request('categoria') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Setor</label>
                <input name="setor" class="form-control" value="{{ request('setor') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Unidade</label>
                <input name="unidade" class="form-control" value="{{ request('unidade') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Responsavel</label>
                <input name="responsavel" class="form-control" value="{{ request('responsavel') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Data inicial</label>
                <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Data final</label>
                <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Hora inicial</label>
                <input type="time" name="hora_inicio" class="form-control" value="{{ request('hora_inicio') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Hora final</label>
                <input type="time" name="hora_fim" class="form-control" value="{{ request('hora_fim') }}">
            </div>
        </div>

        <button class="btn btn-primary mt-3">Filtrar</button>
        <a href="{{ route('relatorio.saidas') }}" class="btn btn-secondary mt-2">Limpar</a>
    </form>

    <div class="alert alert-info">
        Total encontrado: {{ $saidas->count() }}
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Produto</th>
                <th>Codigo</th>
                <th>Categoria</th>
                <th>Setor</th>
                <th>Unidade</th>
                <th>Responsavel</th>
                <th>Qtd</th>
                <th>Data</th>
                <th>Hora</th>
            </tr>
        </thead>

        <tbody>
        @foreach($saidas as $saida)
            <tr>
                <td>{{ $saida->produto }}</td>
                <td>{{ $saida->codigo }}</td>
                <td>{{ $saida->categoria }}</td>
                <td>{{ $saida->setor }}</td>
                <td>{{ $saida->unidade ?? '-' }}</td>
                <td>{{ $saida->responsavel ?? '-' }}</td>
                <td class="{{ $saida->quantidade > 10 ? 'text-danger' : 'text-dark' }}">
                    {{ $saida->quantidade }}
                </td>
                <td>{{ \Carbon\Carbon::parse($saida->created_at)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($saida->created_at)->format('H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('relatorio.index') }}" class="btn btn-secondary">Voltar</a>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





