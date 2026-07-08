<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Entradas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .card-box {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .title {
            font-weight: 700;
        }
    </style>
</head>

<body>

<div class="container mt-4">

    <div class="card-box">

        <h3 class="title mb-4">📥 Relatório de Entradas</h3>

        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Setor</th>
                        <th>Solicitante</th>
                        <th>Quantidade</th>
                        <th>Data</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($entradas as $entrada)
                        <tr>
                            <td>{{ $entrada->nome }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $entrada->categoria }}
                                </span>
                            </td>
                            <td>{{ $entrada->setor }}</td>

                            <td>
                                @if($entrada->responsavel)
                                    <span class="badge bg-success">
                                        {{ $entrada->responsavel }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $entrada->quantidade }}</strong>
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($entrada->created_at)->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Nenhuma entrada encontrada
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




