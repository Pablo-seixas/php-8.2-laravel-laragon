<?php

namespace App\Http\Controllers;

use App\Services\BackupMysqlService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BackupController extends Controller
{
    public function index(BackupMysqlService $service): View
    {
        $backups = collect($service->listar());
        $config = $service->configuracao();

        $totalBytes = $backups->sum('tamanho_bytes');

        $totalFormatado = $totalBytes >= 1048576
            ? round($totalBytes / 1048576, 2) . ' MB'
            : round($totalBytes / 1024, 2) . ' KB';

        return view('backups.index', compact('backups', 'totalFormatado', 'config'));
    }

    public function gerar(BackupMysqlService $service): RedirectResponse
    {
        $service->gerar();

        return back()->with('success', 'Backup gerado com sucesso.');
    }

    public function atualizarRetencao(BackupMysqlService $service): RedirectResponse
    {
        $service->atualizarRetencao((int) request('retencao_meses', 3));

        return back()->with('success', 'Tempo de retenção atualizado.');
    }

    public function baixar(int $backup, BackupMysqlService $service)
    {
        $registro = $service->buscar($backup);

        if (!$registro || !file_exists($registro->caminho)) {
            return back()->with('error', 'Arquivo não encontrado.');
        }

        return response()->download($registro->caminho, $registro->nome_arquivo);
    }

    public function destruir(int $backup, BackupMysqlService $service): RedirectResponse
    {
        $service->remover($backup);

        return back()->with('success', 'Backup removido com sucesso.');
    }
}
