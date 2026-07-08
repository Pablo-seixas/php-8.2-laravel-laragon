<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::hasTable('saidas')
            ? Schema::table('saidas', function (Blueprint $table) {
                Schema::hasColumn('saidas', 'responsavel') ? null : $table->string('responsavel')->nullable();
                Schema::hasColumn('saidas', 'unidade') ? null : $table->string('unidade')->nullable();
                Schema::hasColumn('saidas', 'observacao') ? null : $table->text('observacao')->nullable();
            })
            : null;

        Schema::hasTable('entradas')
            ? Schema::table('entradas', function (Blueprint $table) {
                Schema::hasColumn('entradas', 'fornecedor') ? null : $table->string('fornecedor')->nullable();
                Schema::hasColumn('entradas', 'responsavel') ? null : $table->string('responsavel')->nullable();
                Schema::hasColumn('entradas', 'unidade') ? null : $table->string('unidade')->nullable();
                Schema::hasColumn('entradas', 'observacao') ? null : $table->text('observacao')->nullable();
            })
            : null;
    }

    public function down(): void
    {
        Schema::hasTable('saidas')
            ? Schema::table('saidas', function (Blueprint $table) {
                Schema::hasColumn('saidas', 'responsavel') ? $table->dropColumn('responsavel') : null;
                Schema::hasColumn('saidas', 'unidade') ? $table->dropColumn('unidade') : null;
                Schema::hasColumn('saidas', 'observacao') ? $table->dropColumn('observacao') : null;
            })
            : null;

        Schema::hasTable('entradas')
            ? Schema::table('entradas', function (Blueprint $table) {
                Schema::hasColumn('entradas', 'fornecedor') ? $table->dropColumn('fornecedor') : null;
                Schema::hasColumn('entradas', 'responsavel') ? $table->dropColumn('responsavel') : null;
                Schema::hasColumn('entradas', 'unidade') ? $table->dropColumn('unidade') : null;
                Schema::hasColumn('entradas', 'observacao') ? $table->dropColumn('observacao') : null;
            })
            : null;
    }
};
