<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saidas', function (Blueprint $table) {
            $table->foreignId('produto_id')->nullable()->constrained('produtos');
            $table->foreignId('categoria_id')->nullable()->constrained('categorias');
            $table->integer('quantidade')->default(0);
            $table->string('setor')->nullable();
            $table->string('responsavel')->nullable();
            $table->text('observacao')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('saidas', function (Blueprint $table) {
            $table->dropColumn([
                'produto_id',
                'categoria_id',
                'quantidade',
                'setor',
                'responsavel',
                'observacao'
            ]);
        });
    }
};