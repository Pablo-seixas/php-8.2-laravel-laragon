<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Central Analítica</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f4f6f9; }
        .kpi-card { border: 0; border-radius: 14px; }
        .chart-card { border: 0; border-radius: 14px; min-height: 360px; }
        .page-title { font-weight: 800; }
        canvas { max-height: 280px; }
    @media print {
    .btn, select, .badge, a { display: none !important; }
    body { background: #fff !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
    </style>
</head>

<body>

<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">Central Analítica</h1>
            <p class="text-muted mb-0">Painel gerencial com atualização automática dos dados do estoque.</p>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>
    </div>

    <div class="card card-body mb-4 shadow-sm">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="fw-bold">Período</label>
                <select id="periodo" class="form-control">
                    <option value="hoje">Hoje</option>
                    <option value="7">Últimos 7 dias</option>
                    <option value="30" selected>Últimos 30 dias</option>
                    <option value="ano">Ano atual</option>
                </select>
            </div>

            <div class="col-md-5 mt-3">
                <button class="btn btn-primary" onclick="carregarDados()">Atualizar agora</button>
                <button class="btn btn-outline-danger" id="btnTempoReal" onclick="alternarTempoReal()">Pausar tempo real</button>
                <button class="btn btn-outline-success" onclick="exportarGraficos()">Exportar PNG</button>
                <button class="btn btn-outline-dark" onclick="salvarPDF()">Salvar PDF</button>
            </div>

            <div class="col-md-4 mt-3 text-md-end">
                <span class="badge bg-success" id="statusTempoReal">Tempo real: ativo</span>
                <span class="badge bg-dark" id="ultimaAtualizacao">Aguardando...</span>
            </div>
        </div>
    </div>

    <div id="erroDados" class="alert alert-danger d-none">
        Erro ao carregar dados da Central Analítica.
    </div>

    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <div class="card card-body shadow-sm kpi-card border-start border-primary border-4">
                <h6>Produtos</h6>
                <h2 id="kpiProdutos">0</h2>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="card card-body shadow-sm kpi-card border-start border-success border-4">
                <h6>Estoque Total</h6>
                <h2 id="kpiEstoque">0</h2>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="card card-body shadow-sm kpi-card border-start border-danger border-4">
                <h6>Estoque Baixo</h6>
                <h2 id="kpiBaixo">0</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body shadow-sm kpi-card border-start border-info border-4">
                <h6>Entradas no Período</h6>
                <h2 id="kpiEntradas">0</h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body shadow-sm kpi-card border-start border-warning border-4">
                <h6>Saídas no Período</h6>
                <h2 id="kpiSaidas">0</h2>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-6">
            <div class="card card-body shadow-sm chart-card">
                <h5>Produtos por Categoria</h5>
                <canvas id="graficoCategoria"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-body shadow-sm chart-card">
                <h5>Top 10 Estoque por Produto</h5>
                <canvas id="graficoEstoque"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-body shadow-sm chart-card">
                <h5>Saídas por Setor</h5>
                <canvas id="graficoSetor"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-body shadow-sm chart-card">
                <h5>Entradas x Saídas por Dia</h5>
                <canvas id="graficoLinha"></canvas>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-body shadow-sm">
                <h5>Alertas de Estoque Baixo</h5>
                <div id="alertasEstoque" class="row"></div>
            </div>
        </div>

    </div>
</div>

<script>
let graficoCategoria, graficoEstoque, graficoSetor, graficoLinha;
let tempoRealAtivo = true;
let intervaloTempoReal = null;

function criarGrafico(id, tipo, labels, datasets) {
    return new Chart(document.getElementById(id), {
        type: tipo,
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 700 },
            plugins: { legend: { display: true } },
            scales: tipo === 'pie' ? {} : { y: { beginAtZero: true } }
        }
    });
}

function atualizarGrafico(grafico, labels, datasets) {
    grafico.data.labels = labels;
    grafico.data.datasets = datasets;
    grafico.update();
}

function montarAlertas(alertas) {
    const area = document.getElementById('alertasEstoque');

    if (!alertas.length) {
        area.innerHTML = '<div class="col-12"><div class="alert alert-success mb-0">Nenhum produto com estoque baixo.</div></div>';
        return;
    }

    area.innerHTML = alertas.map(item => `
        <div class="col-md-3 mb-3">
            <div class="alert alert-danger mb-0">
                <strong>${item.nome}</strong><br>
                Atual: ${item.quantidade}<br>
                Mínimo: ${item.estoque_minimo}
            </div>
        </div>
    `).join('');
}

async function carregarDados() {
    try {
        const periodo = document.getElementById('periodo').value;
        const resposta = await fetch('/central-analitica/dados?periodo=' + periodo);

        if (!resposta.ok) {
            throw new Error('Erro na resposta do servidor');
        }

        const dados = await resposta.json();

        document.getElementById('erroDados').classList.add('d-none');

        document.getElementById('kpiProdutos').innerText = dados.kpis.produtos;
        document.getElementById('kpiEstoque').innerText = dados.kpis.estoque;
        document.getElementById('kpiBaixo').innerText = dados.kpis.estoque_baixo;
        document.getElementById('kpiEntradas').innerText = dados.kpis.entradas_periodo;
        document.getElementById('kpiSaidas').innerText = dados.kpis.saidas_periodo;

        document.getElementById('ultimaAtualizacao').innerText = 'Atualizado: ' + new Date().toLocaleTimeString();

        const catLabels = dados.produtosPorCategoria.map(i => i.nome);
        const catDataset = [{ label: 'Produtos', data: dados.produtosPorCategoria.map(i => i.total) }];

        const estLabels = dados.estoquePorProduto.map(i => i.nome);
        const estDataset = [{ label: 'Quantidade', data: dados.estoquePorProduto.map(i => i.quantidade) }];

        const setorLabels = dados.saidasPorSetor.map(i => i.nome);
        const setorDataset = [{ label: 'Saídas', data: dados.saidasPorSetor.map(i => i.total) }];

        const linhaLabels = dados.entradasSaidasPorDia.map(i => i.dia);
        const linhaDataset = [
            { label: 'Entradas', data: dados.entradasSaidasPorDia.map(i => i.entradas), tension: 0.3 },
            { label: 'Saídas', data: dados.entradasSaidasPorDia.map(i => i.saidas), tension: 0.3 }
        ];

        if (!graficoCategoria) {
            graficoCategoria = criarGrafico('graficoCategoria', 'pie', catLabels, catDataset);
            graficoEstoque = criarGrafico('graficoEstoque', 'bar', estLabels, estDataset);
            graficoSetor = criarGrafico('graficoSetor', 'bar', setorLabels, setorDataset);
            graficoLinha = criarGrafico('graficoLinha', 'line', linhaLabels, linhaDataset);
        } else {
            atualizarGrafico(graficoCategoria, catLabels, catDataset);
            atualizarGrafico(graficoEstoque, estLabels, estDataset);
            atualizarGrafico(graficoSetor, setorLabels, setorDataset);
            atualizarGrafico(graficoLinha, linhaLabels, linhaDataset);
        }

        montarAlertas(dados.alertas);

    } catch (erro) {
        document.getElementById('erroDados').classList.remove('d-none');
        console.error(erro);
    }
}

function alternarTempoReal() {
    tempoRealAtivo = !tempoRealAtivo;

    document.getElementById('btnTempoReal').innerText = tempoRealAtivo ? 'Pausar tempo real' : 'Retomar tempo real';
    document.getElementById('statusTempoReal').innerText = tempoRealAtivo ? 'Tempo real: ativo' : 'Tempo real: pausado';
    document.getElementById('statusTempoReal').className = tempoRealAtivo ? 'badge bg-success' : 'badge bg-secondary';
}

function exportarGraficos() {
    const graficos = [
        ['produtos-categoria.png', graficoCategoria],
        ['estoque-produto.png', graficoEstoque],
        ['saidas-setor.png', graficoSetor],
        ['entradas-saidas-dia.png', graficoLinha]
    ];

    graficos.forEach(([nome, grafico]) => {
        const link = document.createElement('a');
        link.href = grafico.toBase64Image();
        link.download = nome;
        link.click();
    });
}

function salvarPDF() {
    window.print();
}

document.getElementById('periodo').addEventListener('change', carregarDados);

carregarDados();

intervaloTempoReal = setInterval(() => {
    if (tempoRealAtivo) {
        carregarDados();
    }
}, 2000);
</script>

@include('modulos.acessibilidade.barra')

</body>
</html>
