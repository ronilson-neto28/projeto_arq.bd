<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();

            // vínculos
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();

            // dados pessoais
            $table->string('nome');
            $table->string('cpf', 11)->unique();           // apenas dígitos
            $table->string('rg')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('genero')->nullable();          // se quiser restringir, depois trocamos por enum
            $table->string('estado_civil')->nullable();
            $table->string('email')->nullable();

            // vínculo
            $table->date('data_admissao')->nullable();

            // setor opcional (criaremos a tabela depois, por ora deixo nullable sem FK dura)
            $table->unsignedBigInteger('setor_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
