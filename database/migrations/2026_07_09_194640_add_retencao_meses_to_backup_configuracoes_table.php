<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backup_configuracoes', function (Blueprint $table) {
            $table->integer('retencao_meses')->default(3);
        });
    }

    public function down(): void
    {
        Schema::table('backup_configuracoes', function (Blueprint $table) {
            $table->dropColumn('retencao_meses');
        });
    }
};