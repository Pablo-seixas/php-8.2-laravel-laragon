<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PerfilMiddleware
{
    public function handle(Request $request, Closure $next, string $perfil)
    {
        $tipo = session('usuario_tipo');

        $permitido = match ($perfil) {
            'admin' => $tipo === 'admin',
            'operador' => in_array($tipo, ['admin', 'operador']),
            'consulta' => in_array($tipo, ['admin', 'operador', 'consulta']),
            default => false,
        };

        return $permitido
            ? $next($request)
            : redirect()->route('dashboard')->with('error', 'Voce nao tem permissao para acessar esta area.');
    }
}
