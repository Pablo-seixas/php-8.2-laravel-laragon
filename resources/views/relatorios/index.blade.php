<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel de Relatorios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Painel de Relatorios</h1>

    <div class="row mt-4">

        <div class="col-md-4 mb-3">
            <div class="card card-body">
                <h4>Relatorio de Saidas</h4>
                <p>Saidas por produto, setor, unidade, responsavel, data e hora.</p>
                <a href="{{ route('relatorio.saidas') }}" class="btn btn-danger">Abrir Saidas</a>
            </div>
        </div>

        @if(session('usuario_tipo') !== 'consulta')
            <div class="col-md-4 mb-3">
                <div class="card card-body">
                    <h4>Relatorio de Entradas</h4>
                    <p>Entradas de materiais no estoque.</p>
                    <a href="{{ route('relatorio.entradas') }}" class="btn btn-success">Abrir Entradas</a>
                </div>
            </div>
        @endif

        <div class="col-md-4 mb-3">
            <div class="card card-body">
                <h4>Extrato Geral</h4>
                <p>Entradas e saidas juntas, no estilo extrato bancario.</p>
                <a href="{{ route('relatorio.extrato') }}" class="btn btn-primary">Abrir Extrato</a>
            </div>
        </div>

        @if(session('usuario_tipo') === 'admin')
            <div class="col-md-4 mb-3">
                <div class="card card-body">
                    <h4>Logs do Sistema</h4>
                    <p>Auditoria de acoes feitas no sistema.</p>
                    <a href="{{ route('logs.index') }}" class="btn btn-warning">Abrir Logs</a>
                </div>
            </div>
        @endif

    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Voltar</a>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>





