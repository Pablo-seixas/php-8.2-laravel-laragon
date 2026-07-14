<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f4f6f9;
        }

        .dashboard-title {
            font-weight: 800;
        }

        .menu-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .kpi-card {
            border: 0;
            border-radius: 14px;
            min-height: 145px;
        }

        .kpi-number {
            font-size: 2rem;
            font-weight: 800;
        }

        .table-card {
            border: 0;
            border-radius: 14px;
        }
    </style>
</head>

<body>

<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="dashboard-title mb-1">Dashboard</h1>

            <p class="text-muted mb-0">
                Usuário:
                <strong>{{ session('usuario_nome') ?? 'Sistema' }}</strong>
                |
                Perfil:
                <strong>{{ session('usuario_tipo') ?? '-' }}</strong>
            </p>
        </div>
    </div>

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

    <div class="card card-body shadow-sm border-0 mb-4">
        <div class="menu-actions">

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

                <a href="{{ route('ml.index') }}" class="btn btn-primary">
                    Machine Learning
                </a>

            @endif

            <a href="{{ route('sair') }}" class="btn btn-outline-danger">
                Sair
            </a>

        </div>
    </div>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card card-body shadow-sm kpi-card border-start border-primary border-4">
                <h5>Total de Produtos</h5>

                <div class="kpi-number text-primary">
                    {{ $totalProdutos }}
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body shadow-sm kpi-card border-start border-success border-4">
                <h5>Total em Estoque</h5>

                <div class="kpi-number text-success">
                    {{ $totalEstoque }}
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body shadow-sm kpi-card border-start border-warning border-4">
                <h5>Estoque Baixo</h5>

                <div class="kpi-number {{ $classeEstoque }}">
                    {{ $estoqueBaixo }}
                </div>

                <span class="{{ $classeEstoque }}">
                    {{ $statusEstoque }}
                </span>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body shadow-sm kpi-card border-start border-danger border-4">
                <h5>Saídas Hoje</h5>

                <div class="kpi-number {{ $saidasHoje > 0 ? 'text-danger' : 'text-success' }}">
                    {{ $saidasHoje }}
                </div>
            </div>
        </div>

    </div>

    <div class="card card-body shadow-sm table-card mt-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Últimas Saídas</h3>

            <a href="{{ route('relatorio.saidas') }}" class="btn btn-outline-primary btn-sm">
                Ver relatório
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">

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

                        <td>
                            {{ $saida->produto->nome ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $saida->setor ?? '-' }}
                        </td>

                        <td class="{{ $saida->quantidade > 10 ? 'text-danger fw-bold' : 'text-dark' }}">
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
                        <td colspan="5" class="text-center text-muted py-4">
                            Nenhuma saída encontrada.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>
        </div>

    </div>

</div>

@include('modulos.acessibilidade.barra')

</body>
</html>