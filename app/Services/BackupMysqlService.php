<?php

namespace App\Services;

use PDO;
use Illuminate\Support\Facades\File;

class BackupMysqlService
{
    private function pdo(): PDO
    {
        return new PDO(
            'mysql:host=' . env('BACKUP_DB_HOST') . ';port=' . env('BACKUP_DB_PORT') . ';dbname=' . env('BACKUP_DB_DATABASE'),
            env('BACKUP_DB_USERNAME'),
            env('BACKUP_DB_PASSWORD'),
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public function garantirTabelas(): void
    {
        $db = $this->pdo();

        $db->exec("
            CREATE TABLE IF NOT EXISTS backup_configuracoes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                retencao_meses INT NOT NULL DEFAULT 3,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            )
        ");

        $db->exec("
            CREATE TABLE IF NOT EXISTS backup_registros (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome_arquivo VARCHAR(255) NOT NULL,
                caminho TEXT NOT NULL,
                tamanho_bytes BIGINT NOT NULL DEFAULT 0,
                tamanho_formatado VARCHAR(50) NULL,
                ano VARCHAR(10) NULL,
                mes VARCHAR(50) NULL,
                semana VARCHAR(50) NULL,
                data_backup DATE NULL,
                status VARCHAR(50) NOT NULL DEFAULT 'criado',
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            )
        ");

        $existe = $db->query("SELECT COUNT(*) FROM backup_configuracoes")->fetchColumn();

        if ((int) $existe === 0) {
            $db->exec("INSERT INTO backup_configuracoes (retencao_meses, created_at, updated_at) VALUES (3, NOW(), NOW())");
        }
    }

    public function listar(): array
    {
        $this->garantirTabelas();

        return $this->pdo()
            ->query("SELECT * FROM backup_registros ORDER BY id DESC")
            ->fetchAll(PDO::FETCH_OBJ);
    }

    public function configuracao(): object
    {
        $this->garantirTabelas();

        return $this->pdo()
            ->query("SELECT * FROM backup_configuracoes ORDER BY id ASC LIMIT 1")
            ->fetch(PDO::FETCH_OBJ);
    }

    public function atualizarRetencao(int $meses): void
    {
        $this->garantirTabelas();

        $stmt = $this->pdo()->prepare("UPDATE backup_configuracoes SET retencao_meses = ?, updated_at = NOW() WHERE id = 1");
        $stmt->execute([$meses]);
    }

    public function gerar(): void
    {
        $this->garantirTabelas();

        $data = now();

        $ano = $data->format('Y');
        $mes = $data->format('m-F');
        $semana = 'semana-' . ceil((int) $data->format('d') / 7);

        $pasta = storage_path("app/backups/mysql/{$ano}/{$mes}/{$semana}");
        File::ensureDirectoryExists($pasta);

        $nome = 'backup_' . $data->format('Y-m-d_H-i-s') . '.sql';
        $caminho = $pasta . DIRECTORY_SEPARATOR . $nome;

        $conteudo = "-- Backup leve registrado pelo sistema\n";
        $conteudo .= "-- Data: " . $data->format('Y-m-d H:i:s') . "\n";
        $conteudo .= "-- Projeto: Controle de Estoque Laravel\n";
        $conteudo .= "-- Registro armazenado no MySQL de backup\n";

        File::put($caminho, $conteudo);

        $tamanho = File::size($caminho);
        $formatado = $tamanho >= 1048576 ? round($tamanho / 1048576, 2) . ' MB' : round($tamanho / 1024, 2) . ' KB';

        $stmt = $this->pdo()->prepare("
            INSERT INTO backup_registros
            (nome_arquivo, caminho, tamanho_bytes, tamanho_formatado, ano, mes, semana, data_backup, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'criado', NOW(), NOW())
        ");

        $stmt->execute([
            $nome,
            $caminho,
            $tamanho,
            $formatado,
            $ano,
            $mes,
            $semana,
            $data->toDateString(),
        ]);

        $this->limparVencidos();
    }

    public function buscar(int $id): ?object
    {
        $this->garantirTabelas();

        $stmt = $this->pdo()->prepare("SELECT * FROM backup_registros WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);

        $backup = $stmt->fetch(PDO::FETCH_OBJ);

        return $backup ?: null;
    }

    public function remover(int $id): void
    {
        $backup = $this->buscar($id);

        if ($backup && File::exists($backup->caminho)) {
            File::delete($backup->caminho);
        }

        $stmt = $this->pdo()->prepare("DELETE FROM backup_registros WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function limparVencidos(): void
    {
        $config = $this->configuracao();

        $meses = (int) ($config->retencao_meses ?? 3);

        if ($meses === 0) {
            return;
        }

        $stmt = $this->pdo()->prepare("SELECT * FROM backup_registros WHERE created_at < DATE_SUB(NOW(), INTERVAL ? MONTH)");
        $stmt->execute([$meses]);

        foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $backup) {
            $this->remover((int) $backup->id);
        }
    }
}
