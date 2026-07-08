<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Produtos</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-3">
        @if(session('usuario_tipo') !== 'consulta')
            <a href="{{ route('produtos.create') }}" class="btn btn-success">Novo produto</a>
            <button type="button" id="editorManual" class="btn btn-danger">Editor Manual: OFF</button>
        @endif

        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>
    </div>

    <form method="GET" class="card card-body mb-4">
        <div class="row">
            <div class="col-md-4 mb-2">
                <label>Buscar</label>
                <input name="busca" class="form-control" value="{{ request('busca') }}" placeholder="Nome ou codigo">
            </div>

            <div class="col-md-4 mb-2">
                <label>Categoria</label>
                <select name="categoria_id" class="form-control">
                    <option value="">Todas</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-2">
                <label>Estoque</label>
                <select name="estoque" class="form-control">
                    <option value="">Todos</option>
                    <option value="baixo" {{ request('estoque') === 'baixo' ? 'selected' : '' }}>Baixo</option>
                    <option value="normal" {{ request('estoque') === 'normal' ? 'selected' : '' }}>Normal</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary mt-3">Filtrar</button>
        <a href="{{ route('produtos.index') }}" class="btn btn-secondary mt-2">Limpar</a>
    </form>

    <div class="alert alert-info">
        Total encontrado: {{ $produtos->count() }}
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Codigo</th>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Qtd</th>
                <th>Minimo</th>
                <th>Status</th>
                <th>Localizacao</th>
                <th>Acoes</th>
            </tr>
        </thead>

        <tbody>
        @foreach($produtos as $produto)
            <tr>
                <td>
                    <form id="produto-form-{{ $produto->id }}" method="POST" action="{{ route('produtos.update', $produto) }}">
                        @csrf
                        @method('PUT')
                        <input name="codigo" value="{{ $produto->codigo }}" class="form-control campo-manual" disabled>
                    </form>
                </td>

                <td>
                    <input form="produto-form-{{ $produto->id }}" name="nome" value="{{ $produto->nome }}" class="form-control campo-manual" disabled>
                </td>

                <td>
                    <select form="produto-form-{{ $produto->id }}" name="categoria_id" class="form-control campo-manual" disabled>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ $produto->categoria_id == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <input form="produto-form-{{ $produto->id }}" type="number" name="quantidade" value="{{ $produto->quantidade }}" class="form-control campo-manual" disabled>
                </td>

                <td>
                    <input form="produto-form-{{ $produto->id }}" type="number" name="estoque_minimo" value="{{ $produto->estoque_minimo }}" class="form-control campo-manual" disabled>
                </td>

                <td class="{{ $produto->quantidade <= $produto->estoque_minimo ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                    {{ $produto->quantidade <= $produto->estoque_minimo ? 'Baixo' : 'Normal' }}
                </td>

                <td>
                    <input form="produto-form-{{ $produto->id }}" name="localizacao" value="{{ $produto->localizacao }}" class="form-control campo-manual" disabled>
                    <input form="produto-form-{{ $produto->id }}" type="hidden" name="observacoes" value="{{ $produto->observacoes }}">
                </td>

                <td>
                    <a href="{{ route('produtos.show', $produto) }}" class="btn btn-info btn-sm">Ver</a>

                    @if(session('usuario_tipo') !== 'consulta')
                        <button form="produto-form-{{ $produto->id }}" type="submit" class="btn btn-success btn-sm salvar-manual d-none">
                            Salvar
                        </button>

                        <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-warning btn-sm editar-normal">
                            Editar
                        </a>

                        <form action="{{ route('produtos.destroy', $produto) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Excluir produto?')">
                                Excluir
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

@if(session('usuario_tipo') !== 'consulta')
<script>
    let ativo = false;

    const botao = document.getElementById('editorManual');

    botao.addEventListener('click', () => {
        ativo = !ativo;

        document.querySelectorAll('.campo-manual').forEach(campo => campo.disabled = !ativo);

        document.querySelectorAll('.salvar-manual').forEach(botaoSalvar =>
            botaoSalvar.classList.toggle('d-none', !ativo)
        );

        document.querySelectorAll('.editar-normal').forEach(botaoEditar =>
            botaoEditar.classList.toggle('d-none', ativo)
        );

        botao.innerText = ativo ? 'Editor Manual: ON' : 'Editor Manual: OFF';

        botao.classList.toggle('btn-success', ativo);
        botao.classList.toggle('btn-danger', !ativo);
    });
</script>
@endif





@include('modulos.acessibilidade.barra')
</body>
</html>






