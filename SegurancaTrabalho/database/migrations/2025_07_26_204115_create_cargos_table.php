<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();

            // cada cargo pertence a uma empresa
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();

            // nome/descrição do cargo dentro da empresa
            $table->string('nome');

            $table->timestamps();

            // evita cargos duplicados com o mesmo nome dentro da mesma empresa
            $table->unique(['empresa_id', 'nome'], 'cargos_empresa_nome_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
