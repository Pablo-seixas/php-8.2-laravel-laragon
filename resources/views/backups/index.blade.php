<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Backups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-4">

    <h1>Backups</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('backups.gerar') }}" class="mb-3">
        @csrf
        <button class="btn btn-success">Gerar Backup</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>
    </form>

    <form method="POST" action="{{ route('backups.retencao') }}" class="card card-body mb-3">
        @csrf

        <label class="fw-bold">Tempo de retenção dos backups</label>

        <select name="retencao_meses" class="form-control mb-3">
            <option value="3" {{ $config->retencao_meses == 3 ? 'selected' : '' }}>3 meses - padrão</option>
            <option value="6" {{ $config->retencao_meses == 6 ? 'selected' : '' }}>6 meses</option>
            <option value="12" {{ $config->retencao_meses == 12 ? 'selected' : '' }}>1 ano</option>
            <option value="24" {{ $config->retencao_meses == 24 ? 'selected' : '' }}>2 anos</option>
            <option value="0" {{ $config->retencao_meses == 0 ? 'selected' : '' }}>Ilimitado</option>
        </select>

        <button class="btn btn-primary">Salvar retenção</button>
    </form>

    <p><strong>Total armazenado:</strong> {{ $totalFormatado ?? '0 KB' }}</p>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Data</th>
                <th>Arquivo</th>
                <th>Tamanho</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($backups as $backup)
                <tr>
                    <td>{{ $backup->created_at }}</td>
                    <td>{{ $backup->nome_arquivo }}</td>
                    <td>{{ $backup->tamanho_formatado }}</td>
                    <td>{{ $backup->status }}</td>
                    <td>
                        <a href="{{ route('backups.baixar', $backup->id) }}" class="btn btn-primary btn-sm">Baixar</a>

                        <form method="POST" action="{{ route('backups.destruir', $backup->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Remover backup?')">Remover</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nenhum backup registrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

@include('modulos.acessibilidade.barra')
</body>
</html>
