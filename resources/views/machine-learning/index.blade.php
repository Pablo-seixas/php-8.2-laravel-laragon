<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Machine Learning do Estoque</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f4f6f9;
        }

        .page-title {
            font-weight: 800;
        }

        .card-painel {
            border: 0;
            border-radius: 14px;
        }

        .kpi {
            font-size: 2rem;
            font-weight: 800;
        }

        .arquivo-card {
            min-height: 150px;
        }

        .resultado-card {
            border-radius: 12px;
            overflow: hidden;
        }

        .metricas-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
        }
    </style>
</head>

<body>

<div class="container mt-4 mb-5">

    <div
        class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4"
    >
        <div>
            <h1 class="page-title mb-1">
                Machine Learning do Estoque
            </h1>

            <p class="text-muted mb-0">
                Previsão de consumo, risco de ruptura e métricas científicas.
            </p>
        </div>

        <a
            href="{{ route('dashboard') }}"
            class="btn btn-secondary"
        >
            Voltar ao Dashboard
        </a>
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

    @php
        $resultados = $resultados ?? [];
        $arquivos = $arquivos ?? [];

        $analisados = collect($resultados)
            ->where('status', 'analisado')
            ->values();

        $historicoInsuficiente = collect($resultados)
            ->where('status', 'historico_insuficiente')
            ->values();

        $criticos = $analisados
            ->where('risco', 'critico');

        $atencao = $analisados
            ->where('risco', 'atencao');

        $regulares = $analisados
            ->where('risco', 'regular');

        $rmseMedio = $analisados->isNotEmpty()
            ? round((float) $analisados->avg('rmse'), 4)
            : 0;

        $r2Medio = $analisados->isNotEmpty()
            ? round((float) $analisados->avg('r2'), 4)
            : 0;
    @endphp

    <div class="card card-body shadow-sm card-painel mb-4">

        <div
            class="d-flex flex-wrap justify-content-between align-items-center gap-3"
        >
            <div>
                <h4 class="mb-1">
                    Treinamento do modelo
                </h4>

                <p class="text-muted mb-0">
                    O sistema usa o histórico de saídas sem alterar o estoque.
                </p>
            </div>

            <form
                method="POST"
                action="{{ route('ml.treinar') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="btn btn-primary btn-lg"
                    onclick="
                        this.disabled = true;
                        this.innerText = 'Treinando...';
                        this.form.submit();
                    "
                >
                    Treinar e gerar previsões
                </button>
            </form>
        </div>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div
                class="card card-body shadow-sm card-painel border-start border-primary border-4"
            >
                <h6>Produtos analisados</h6>

                <div class="kpi text-primary">
                    {{ $analisados->count() }}
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div
                class="card card-body shadow-sm card-painel border-start border-danger border-4"
            >
                <h6>Risco crítico</h6>

                <div class="kpi text-danger">
                    {{ $criticos->count() }}
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div
                class="card card-body shadow-sm card-painel border-start border-warning border-4"
            >
                <h6>Em atenção</h6>

                <div class="kpi text-warning">
                    {{ $atencao->count() }}
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div
                class="card card-body shadow-sm card-painel border-start border-secondary border-4"
            >
                <h6>Histórico insuficiente</h6>

                <div class="kpi text-secondary">
                    {{ $historicoInsuficiente->count() }}
                </div>
            </div>
        </div>

    </div>

    @if($analisados->isNotEmpty())

        <div class="row g-3 mb-4">

            <div class="col-md-6">
                <div class="card card-body shadow-sm card-painel">
                    <h5>RMSE médio</h5>

                    <div class="kpi">
                        {{ $rmseMedio }}
                    </div>

                    <small class="text-muted">
                        Quanto menor o valor, menor o erro médio do modelo.
                    </small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-body shadow-sm card-painel">
                    <h5>R² médio</h5>

                    <div class="kpi">
                        {{ $r2Medio }}
                    </div>

                    <small class="text-muted">
                        Quanto mais próximo de 1, melhor a capacidade explicativa.
                    </small>
                </div>
            </div>

        </div>

        <div class="card card-body shadow-sm card-painel mb-4">

            <h4 class="mb-4">
                Resultados das previsões
            </h4>

            <div class="row g-3">

                @foreach($analisados as $resultado)

                    @php
                        $risco = $resultado['risco'] ?? 'sem_consumo';

                        $classeRisco = match($risco) {
                            'critico' => 'danger',
                            'atencao' => 'warning',
                            'regular' => 'success',
                            default => 'secondary',
                        };

                        $textoRisco = match($risco) {
                            'critico' => 'Crítico',
                            'atencao' => 'Atenção',
                            'regular' => 'Regular',
                            default => 'Sem consumo',
                        };
                    @endphp

                    <div class="col-md-6 col-xl-4">

                        <div
                            class="card h-100 resultado-card border-{{ $classeRisco }}"
                        >

                            <div
                                class="card-header bg-{{ $classeRisco }} text-white"
                            >
                                <strong>
                                    {{ $resultado['produto'] ?? 'Produto' }}
                                </strong>
                            </div>

                            <div class="card-body">

                                <div class="row mb-2">
                                    <div class="col-7">
                                        Estoque atual:
                                    </div>

                                    <div class="col-5 text-end fw-bold">
                                        {{ $resultado['estoque_atual'] ?? 0 }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-7">
                                        Amostras:
                                    </div>

                                    <div class="col-5 text-end">
                                        {{ $resultado['amostras'] ?? 0 }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-7">
                                        Previsão em 30 dias:
                                    </div>

                                    <div class="col-5 text-end fw-bold">
                                        {{ number_format(
                                            (float) (
                                                $resultado['consumo_previsto_30_dias']
                                                ?? 0
                                            ),
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-7">
                                        Média diária:
                                    </div>

                                    <div class="col-5 text-end">
                                        {{ number_format(
                                            (float) (
                                                $resultado['media_diaria_prevista']
                                                ?? 0
                                            ),
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-7">
                                        Dias restantes:
                                    </div>

                                    <div class="col-5 text-end">
                                        {{ $resultado['dias_restantes'] ?? '-' }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-7">
                                        Ruptura prevista:
                                    </div>

                                    <div class="col-5 text-end">
                                        {{
                                            $resultado['data_provavel_ruptura']
                                            ?? '-'
                                        }}
                                    </div>
                                </div>

                                <hr>

                                <p class="mb-2">
                                    <strong>Tendência:</strong>

                                    {{ ucfirst(
                                        $resultado['tendencia']
                                        ?? 'indefinida'
                                    ) }}
                                </p>

                                <p class="mb-3">
                                    <strong>Risco:</strong>

                                    <span
                                        class="badge bg-{{ $classeRisco }}"
                                    >
                                        {{ $textoRisco }}
                                    </span>
                                </p>

                                <div class="row metricas-box text-center">

                                    <div class="col-4">
                                        <small class="text-muted">
                                            MAE
                                        </small>

                                        <br>

                                        <strong>
                                            {{ $resultado['mae'] ?? 0 }}
                                        </strong>
                                    </div>

                                    <div class="col-4">
                                        <small class="text-muted">
                                            RMSE
                                        </small>

                                        <br>

                                        <strong>
                                            {{ $resultado['rmse'] ?? 0 }}
                                        </strong>
                                    </div>

                                    <div class="col-4">
                                        <small class="text-muted">
                                            R²
                                        </small>

                                        <br>

                                        <strong>
                                            {{ $resultado['r2'] ?? 0 }}
                                        </strong>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @else

        <div class="alert alert-info">
            Nenhum resultado disponível.

            Clique em
            <strong>Treinar e gerar previsões</strong>
            para iniciar a análise.
        </div>

    @endif

    @if($historicoInsuficiente->isNotEmpty())

        <div class="card card-body shadow-sm card-painel mb-4">

            <h4 class="mb-3">
                Produtos sem histórico suficiente
            </h4>

            <div class="row g-3">

                @foreach($historicoInsuficiente as $resultado)

                    <div class="col-md-4">

                        <div class="alert alert-secondary h-100 mb-0">

                            <strong>
                                {{ $resultado['produto'] ?? 'Produto' }}
                            </strong>

                            <br>

                            {{
                                $resultado['mensagem']
                                ?? 'Não existem dados suficientes.'
                            }}

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @endif

    <div class="card card-body shadow-sm card-painel">

        <h4>
            Arquivos científicos gerados
        </h4>

        <p class="text-muted">
            Os arquivos contêm previsões e métricas para análise científica.
        </p>

        <div class="row g-3">

            @forelse($arquivos as $arquivo)

                <div class="col-md-4">

                    <div class="card card-body arquivo-card h-100">

                        <strong class="mb-2">
                            {{ $arquivo['nome'] }}
                        </strong>

                        <small class="text-muted">
                            Tamanho:
                            {{ $arquivo['tamanho'] }}
                        </small>

                        <small class="text-muted mb-3">
                            Gerado em:
                            {{ $arquivo['data'] }}
                        </small>

                        <a
                            href="{{ route(
                                'ml.baixar',
                                $arquivo['nome']
                            ) }}"
                            class="btn btn-outline-primary btn-sm mt-auto"
                        >
                            Baixar arquivo
                        </a>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-secondary mb-0">
                        Nenhum arquivo científico foi gerado.
                    </div>

                </div>

            @endforelse

        </div>

    </div>

</div>

@include('modulos.acessibilidade.barra')

</body>
</html>