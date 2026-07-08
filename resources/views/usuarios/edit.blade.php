<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h1>Editar Usuario</h1>

    <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="card card-body">
        @csrf
        @method('PUT')

        <label>Nome</label>
        <input name="name" value="{{ $usuario->name }}" class="form-control mb-3" required>

        <label>Email</label>
        <input type="email" name="email" value="{{ $usuario->email }}" class="form-control mb-3" required>

        <label>Perfil</label>
        <select name="tipo" class="form-control mb-3">
            <option value="operador" {{ $usuario->tipo === 'operador' ? 'selected' : '' }}>Operador</option>
            <option value="consulta" {{ $usuario->tipo === 'consulta' ? 'selected' : '' }}>Consulta</option>
            <option value="admin" {{ $usuario->tipo === 'admin' ? 'selected' : '' }}>Administrador</option>
        </select>

        <label>Setor</label>
        <input name="setor" value="{{ $usuario->setor }}" class="form-control mb-3">

        <label>Unidade</label>
        <input name="unidade" value="{{ $usuario->unidade }}" class="form-control mb-3">

        <label>Nova senha opcional</label>
        <input type="password" name="password" class="form-control mb-3">

        <label>Confirmar nova senha</label>
        <input type="password" name="password_confirmation" class="form-control mb-3">

        @php
            $logado = \App\Models\User::find(session("usuario_id"));
            $adminPrincipal = session("usuario_id") === 0 || optional($logado)->is_super_admin;
        @endphp

        @if($adminPrincipal)
            <div class="card card-body mb-3 bg-light">
                <h5>Permissões especiais</h5>

                <label>
                    <input type="checkbox" name="is_super_admin" value="1" {{ $usuario->is_super_admin ? "checked" : "" }}>
                    Administrador principal
                </label>

                <label>
                    <input type="checkbox" name="can_manage_users" value="1" {{ $usuario->can_manage_users ? "checked" : "" }}>
                    Pode gerenciar usuários
                </label>

                <label>
                    <input type="checkbox" name="can_delete_users" value="1" {{ $usuario->can_delete_users ? "checked" : "" }}>
                    Pode excluir usuários
                </label>
            </div>
        @endif

        <label>Status</label>
        <select name="ativo" class="form-control mb-3">
            <option value="1" {{ $usuario->ativo ? 'selected' : '' }}>Ativo</option>
            <option value="0" {{ !$usuario->ativo ? 'selected' : '' }}>Bloqueado</option>
        </select>

        <button class="btn btn-warning">Atualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-2">Voltar</a>
    </form>

</div>





@include('modulos.acessibilidade.barra')
</body>
</html>






