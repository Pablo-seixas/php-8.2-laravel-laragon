<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    @php
        $logado = \App\Models\User::find(session('usuario_id'));
        $adminPrincipal = session('usuario_id') === 0 || optional($logado)->is_super_admin;
        $podeGerenciar = $adminPrincipal || optional($logado)->can_manage_users || session('usuario_tipo') === 'admin';
        $podeExcluir = $adminPrincipal || optional($logado)->can_delete_users;
    @endphp

    <h1>Usuários</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($podeGerenciar)
        <a href="{{ route('usuarios.create') }}" class="btn btn-success mb-3">Novo usuário</a>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Voltar</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Setor</th>
                <th>Unidade</th>
                <th>Status</th>
                @if($adminPrincipal)
                    <th>Permissões</th>
                @endif
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
        @foreach($usuarios as $usuario)
            @php
                $alvoEhAdmin = $usuario->tipo === 'admin';
                $ehProprioUsuario = $usuario->id === session('usuario_id');

                $podeEditarUsuario = $podeGerenciar && ($adminPrincipal || !$alvoEhAdmin);

                $podeResetarSenha = $podeGerenciar && ($adminPrincipal || !$alvoEhAdmin);

                $podeExcluirUsuario = $podeExcluir
                    && !$ehProprioUsuario
                    && ($adminPrincipal || !$alvoEhAdmin);
            @endphp

            <tr>
                <td>
                    {{ $usuario->name }}

                    @if($usuario->is_super_admin)
                        <span class="badge bg-danger">Admin principal</span>
                    @endif
                </td>

                <td>{{ $usuario->email }}</td>

                <td>
                    <span class="{{ $usuario->tipo === 'admin' ? 'text-danger fw-bold' : ($usuario->tipo === 'operador' ? 'text-primary fw-bold' : 'text-secondary fw-bold') }}">
                        {{ $usuario->perfil() }}
                    </span>
                </td>

                <td>{{ $usuario->setor ?? '-' }}</td>
                <td>{{ $usuario->unidade ?? '-' }}</td>

                <td class="{{ $usuario->ativo ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                    {{ $usuario->status() }}
                </td>

                @if($adminPrincipal)
                    <td>
                        @if($usuario->can_manage_users)
                            <span class="badge bg-primary">Gerencia usuários</span>
                        @endif

                        @if($usuario->can_delete_users)
                            <span class="badge bg-warning text-dark">Pode excluir</span>
                        @endif

                        @if(!$usuario->can_manage_users && !$usuario->can_delete_users && !$usuario->is_super_admin)
                            <span class="text-muted">Sem permissões especiais</span>
                        @endif
                    </td>
                @endif

                <td>
                    @if($podeEditarUsuario)
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm">Editar</a>
                    @endif

                    @if($podeResetarSenha)
                        <form method="POST" action="{{ route('usuarios.resetar', $usuario) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-info btn-sm">Resetar senha</button>
                        </form>
                    @endif

                    @if($podeExcluirUsuario)
                        <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Excluir usuário?')">Excluir</button>
                        </form>
                    @endif

                    @if(!$podeEditarUsuario && !$podeResetarSenha && !$podeExcluirUsuario)
                        <span class="text-muted">Sem ações disponíveis</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

@include('modulos.acessibilidade.barra')
</body>
</html>
