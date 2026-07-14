<?php

namespace App\Http\Controllers;

use App\Services\MachineLearning\PrevisaoEstoqueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MachineLearningController extends Controller
{
    public function index(PrevisaoEstoqueService $service): View
    {
        $resultados = $service->carregarUltimosResultados();
        $arquivos = $service->listarArquivos();

        return view(
            'machine-learning.index',
            compact('resultados', 'arquivos')
        );
    }

    public function treinar(
        PrevisaoEstoqueService $service
    ): RedirectResponse {
        set_time_limit(300);

        $inicio = microtime(true);

        $resultados = $service->analisarTodos();

        $service->salvarResultados($resultados);

        $tempo = round(microtime(true) - $inicio, 2);

        return redirect()
            ->route('ml.index')
            ->with(
                'success',
                "Treinamento concluído em {$tempo} segundo(s)."
            );
    }

    public function baixar(
        string $arquivo
    ): BinaryFileResponse {
        abort_if(
            str_contains($arquivo, '..')
            || !preg_match('/^[A-Za-z0-9._-]+$/', $arquivo),
            400
        );

        $caminho = storage_path(
            'app/machine-learning/resultados/' . $arquivo
        );

        abort_unless(File::exists($caminho), 404);

        return response()->download($caminho, $arquivo);
    }
}