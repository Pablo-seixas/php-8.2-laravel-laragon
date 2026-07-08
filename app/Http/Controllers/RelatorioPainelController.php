<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RelatorioPainelController extends Controller
{
    public function index()
    {
        return view('relatorios.index');
    }
}