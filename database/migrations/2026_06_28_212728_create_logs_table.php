<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('usuario')->nullable();
            $table->string('tipo')->nullable();
            $table->string('acao');
            $table->string('tabela')->nullable();
            $table->unsignedBigInteger('registro_id')->nullable();
            $table->string('ip')->nullable();
            $table->text('descricao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
