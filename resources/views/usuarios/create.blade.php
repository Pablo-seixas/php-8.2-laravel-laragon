<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Novo Usuario</h1>

    <form method="POST" action="{{ route('usuarios.store') }}" class="card card-body">
        @csrf

        <label>Nome</label>
        <input name="name" class="form-control mb-3" required>

        <label>Email</label>
        <input type="email" name="email" class="form-control mb-3" required>

        <label>Perfil</label>
        <select name="tipo" class="form-control mb-3">
            <option value="operador">Operador</option>
            <option value="consulta">Consulta</option>
            <option value="admin">Administrador</option>
        </select>

        <label>Senha inicial</label>
        <input type="password" name="password" class="form-control mb-3" required>

        <label>Confirmar senha</label>
        <input type="password" name="password_confirmation" class="form-control mb-3" required>

        <label>Setor</label>
        <input name="setor" class="form-control mb-3">

        <label>Unidade</label>
        <input name="unidade" class="form-control mb-3">

        @php
            $logado = \App\Models\User::find(session("usuario_id"));
            $adminPrincipal = session("usuario_id") === 0 || optional($logado)->is_super_admin;
        @endphp

        @if($adminPrincipal)
            <div class="card card-body mb-3 bg-light">
                <h5>Permissões especiais</h5>

                <label>
                    <input type="checkbox" name="is_super_admin" value="1">
                    Administrador principal
                </label>

                <label>
                    <input type="checkbox" name="can_manage_users" value="1">
                    Pode gerenciar usuários
                </label>

                <label>
                    <input type="checkbox" name="can_delete_users" value="1">
                    Pode excluir usuários
                </label>
            </div>
        @endif

        <button class="btn btn-success">Salvar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-2">Voltar</a>
    </form>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>







