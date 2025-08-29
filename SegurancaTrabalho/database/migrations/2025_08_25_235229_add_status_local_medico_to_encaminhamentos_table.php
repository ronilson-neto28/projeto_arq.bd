<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('encaminhamentos', function (Blueprint $table) {
            $table->string('status')->default('agendado')->after('previsao_retorno');
            $table->string('local_clinica_id')->nullable()->after('status');
            $table->string('medico_responsavel_id')->nullable()->after('local_clinica_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encaminhamentos', function (Blueprint $table) {
            $table->dropColumn(['status', 'local_clinica_id', 'medico_responsavel_id']);
        });
    }
};
