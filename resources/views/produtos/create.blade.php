<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-4">

    <h1>Novo produto</h1>

    <form method="POST"
          action="{{ route('produtos.store') }}"
          class="card card-body">

        @csrf

        <label>Categoria</label>
        <select name="categoria_id" class="form-control mb-2">
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">
                    {{ $categoria->nome }}
                </option>
            @endforeach
        </select>

        <label>Nome</label>
        <input name="nome"
               class="form-control mb-2"
               required>

        <label>Codigo</label>
        <input name="codigo"
               class="form-control mb-2"
               required>

        <label>Quantidade</label>
        <input type="number"
               name="quantidade"
               class="form-control mb-2"
               value="0">

        <label>Estoque minimo</label>
        <input type="number"
               name="estoque_minimo"
               class="form-control mb-2"
               value="1">

        <label>Localizacao</label>
        <input name="localizacao"
               class="form-control mb-2">

        <label>Observacoes</label>
        <textarea name="observacoes"
                  class="form-control mb-3"></textarea>

        <button class="btn btn-success">
            Salvar
        </button>

        <a href="{{ route('produtos.index') }}"
           class="btn btn-secondary mt-2">
            Voltar
        </a>

    </form>

</div>




@include('modulos.acessibilidade.barra')
</body>
</html>





