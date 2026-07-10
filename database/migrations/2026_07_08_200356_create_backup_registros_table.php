<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backup_registros', function (Blueprint $table) {
            $table->id();
            $table->string('nome_arquivo');
            $table->string('caminho');
            $table->unsignedBigInteger('tamanho_bytes')->default(0);
            $table->string('tamanho_formatado')->default('0 KB');
            $table->string('ano');
            $table->string('mes');
            $table->string('semana');
            $table->date('data_backup');
            $table->string('status')->default('criado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backup_registros');
    }
};