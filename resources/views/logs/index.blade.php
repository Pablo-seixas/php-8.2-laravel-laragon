<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Logs do Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Logs do Sistema</h1>

    <form method="GET" class="card card-body mb-4">
        <div class="row">

            <div class="col-md-3 mb-2">
                <label>Usuario</label>
                <input name="usuario" class="form-control" value="{{ request('usuario') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Acao</label>
                <input name="acao" class="form-control" value="{{ request('acao') }}">
            </div>

            <div class="col-md-3 mb-2">
                <label>Tabela</label>
                <input name="tabela" class="form-control" value="{{ request('tabela') }}">
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
        <a href="{{ route('logs.index') }}" class="btn btn-secondary mt-2">Limpar</a>
    </form>

    <div class="alert alert-info">
        Total encontrado: {{ $logs->count() }}
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Data</th>
                <th>Hora</th>
                <th>Usuario</th>
                <th>Tipo</th>
                <th>Acao</th>
                <th>Tabela</th>
                <th>ID</th>
                <th>IP</th>
                <th>Descricao</th>
            </tr>
        </thead>

        <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at ? $log->created_at->format('d/m/Y') : '-' }}</td>
                <td>{{ $log->created_at ? $log->created_at->format('H:i') : '-' }}</td>
                <td>{{ $log->usuario ?? '-' }}</td>
                <td>{{ $log->tipo ?? '-' }}</td>
                <td>{{ $log->acao }}</td>
                <td>{{ $log->tabela ?? '-' }}</td>
                <td>{{ $log->registro_id ?? '-' }}</td>
                <td>{{ $log->ip ?? '-' }}</td>
                <td>{{ $log->descricao ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





