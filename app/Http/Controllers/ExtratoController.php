<?php

namespace App\Http\Controllers;

use App\Services\ExtratoService;
use Illuminate\Http\Request;

class ExtratoController extends Controller
{
    public function index(Request $request, ExtratoService $extrato)
    {
        $movimentacoes = $extrato->movimentacoes($request);

        return view('relatorios.extrato', compact('movimentacoes'));
    }
}
