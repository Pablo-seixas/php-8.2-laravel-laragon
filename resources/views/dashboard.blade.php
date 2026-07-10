<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Dashboard</h1>

    <p>
        Usuário: <strong>{{ session('usuario_nome') ?? 'Sistema' }}</strong> |
        Perfil: <strong>{{ session('usuario_tipo') ?? '-' }}</strong>
    </p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-3">

        <a href="{{ route('produtos.index') }}" class="btn btn-primary">
            Produtos
        </a>

        @if(session('usuario_tipo') !== 'consulta')
            <a href="{{ route('entradas.index') }}" class="btn btn-success">
                Entradas
            </a>

            <a href="{{ route('saidas.index') }}" class="btn btn-danger">
                Saídas
            </a>
        @endif

        <a href="{{ route('relatorio.index') }}" class="btn btn-info">
            Relatórios
        </a>

        <a href="{{ route('analitico.index') }}" class="btn btn-warning">
            Central Analítica
        </a>

        @if(session('usuario_tipo') === 'admin')

            <a href="{{ route('usuarios.index') }}" class="btn btn-dark">
                Usuários
            </a>

            <a href="{{ route('categorias.index') }}" class="btn btn-outline-dark">
                Categorias
            </a>

            <a href="{{ route('logs.index') }}" class="btn btn-warning">
                Logs
            </a>

            <a href="{{ route('backups.index') }}" class="btn btn-secondary">
                Backups
            </a>

        @endif

        <a href="{{ route('sair') }}" class="btn btn-secondary">
            Sair
        </a>

    </div>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card card-body">
                <h5>Total de Produtos</h5>
                <h2>{{ $totalProdutos }}</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body">
                <h5>Total em Estoque</h5>
                <h2>{{ $totalEstoque }}</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body">
                <h5>Estoque Baixo</h5>

                <h2 class="{{ $classeEstoque }}">
                    {{ $estoqueBaixo }}
                </h2>

                <span class="{{ $classeEstoque }}">
                    {{ $statusEstoque }}
                </span>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body">
                <h5>Saídas Hoje</h5>

                <h2 class="{{ $saidasHoje > 0 ? 'text-danger' : 'text-success' }}">
                    {{ $saidasHoje }}
                </h2>
            </div>
        </div>

    </div>

    <h3 class="mt-4">Últimas Saídas</h3>

    <table class="table table-bordered table-striped">

        <thead class="table-dark">
            <tr>
                <th>Produto</th>
                <th>Setor</th>
                <th>Quantidade</th>
                <th>Data</th>
                <th>Hora</th>
            </tr>
        </thead>

        <tbody>

        @forelse($ultimasSaidas as $saida)

            <tr>

                <td>{{ $saida->produto->nome ?? 'N/A' }}</td>

                <td>{{ $saida->setor ?? '-' }}</td>

                <td class="{{ $saida->quantidade > 10 ? 'text-danger' : 'text-dark' }}">
                    {{ $saida->quantidade }}
                </td>

                <td>
                    {{ $saida->created_at?->format('d/m/Y') ?? '-' }}
                </td>

                <td>
                    {{ $saida->created_at?->format('H:i') ?? '-' }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="5" class="text-center">
                    Nenhuma saída encontrada.
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@include('modulos.acessibilidade.barra')

</body>
</html>