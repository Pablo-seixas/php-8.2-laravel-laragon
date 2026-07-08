<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AcessoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return session()->has('usuario_tipo')
            ? $next($request)
            : redirect('/');
    }
}
