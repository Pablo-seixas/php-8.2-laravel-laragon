<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tipo')->default('usuario');
            $table->string('setor')->nullable();
            $table->string('unidade')->nullable();
            $table->boolean('trocar_senha')->default(true);
            $table->boolean('ativo')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tipo',
                'setor',
                'unidade',
                'trocar_senha',
                'ativo',
            ]);
        });
    }
};
