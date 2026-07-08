<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // evita erro se a tabela já existir
        if (!Schema::hasTable('entradas')) {
            Schema::create('entradas', function (Blueprint $table) {
                $table->id();

                $table->foreignId('produto_id')->constrained()->onDelete('cascade');
                $table->integer('quantidade');
                $table->string('setor');
                $table->string('responsavel')->nullable();
                $table->text('observacao')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};