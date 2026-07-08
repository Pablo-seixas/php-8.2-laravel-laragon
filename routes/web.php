<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcessoController;
use App\Http\Controllers\AnaliticoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\RelatorioPainelController;
use App\Http\Controllers\SaidaController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\ExtratoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\CategoriaController;

Route::get('/', [AcessoController::class, 'index'])->name('login');
Route::post('/acesso', [AcessoController::class, 'entrar'])->name('acesso.entrar');
Route::get('/sair', [AcessoController::class, 'sair'])->name('sair');

Route::middleware(['acesso', 'nocache'])->group(function () {

    Route::get('/trocar-senha', [AcessoController::class, 'editarSenha'])->name('senha.editar');
    Route::post('/trocar-senha', [AcessoController::class, 'atualizarSenha'])->name('senha.atualizar');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/central-analitica', [AnaliticoController::class, 'index'])->name('analitico.index');
    Route::get('/central-analitica/dados', [AnaliticoController::class, 'dados'])->name('analitico.dados');

    Route::middleware('perfil:consulta')->group(function () {
        Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
        Route::get('/produtos/create', [ProdutoController::class, 'create'])->name('produtos.create');
        Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
        Route::get('/produtos/{produto}', [ProdutoController::class, 'show'])->name('produtos.show');
        Route::get('/produtos/{produto}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
        Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.update');
        Route::patch('/produtos/{produto}', [ProdutoController::class, 'update']);
        Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');

        Route::get('/relatorio', [RelatorioPainelController::class, 'index'])->name('relatorio.index');
        Route::get('/relatorio/saidas', [RelatorioController::class, 'saidas'])->name('relatorio.saidas');
        Route::get('/relatorio/entradas', [RelatorioController::class, 'entradas'])->name('relatorio.entradas');
        Route::get('/relatorio/extrato', [ExtratoController::class, 'index'])->name('relatorio.extrato');
    });

    Route::middleware('perfil:operador')->group(function () {
        Route::resource('saidas', SaidaController::class);
        Route::resource('entradas', EntradaController::class);
        Route::post('/produtos/{produto}/saida', [SaidaController::class, 'storeFromProduto'])->name('produtos.saida');
    });

    Route::middleware('perfil:admin')->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::post('/usuarios/{usuario}/resetar-senha', [UsuarioController::class, 'resetarSenha'])->name('usuarios.resetar');

        Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
        Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    });
});