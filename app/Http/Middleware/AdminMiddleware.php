<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return session('usuario_tipo') === 'admin'
            ? $next($request)
            : redirect()->route('dashboard')->with('error', 'Acesso permitido somente para administrador.');
    }
}
