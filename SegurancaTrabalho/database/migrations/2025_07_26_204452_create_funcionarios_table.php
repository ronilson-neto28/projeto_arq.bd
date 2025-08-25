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

            // FKs (tabelas `empresas` e `cargos` já existem antes desta migration)
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('cargo_id')->constrained('cargos')->onDelete('cascade');

            // Dados do funcionário
            $table->string('nome', 255);
            $table->string('cpf', 11)->unique();            // 11 dígitos, sem máscara
            $table->string('email', 255)->nullable()->unique();
            $table->enum('genero', ['masculino', 'feminino', 'outro'])->nullable();
            $table->date('data_nascimento')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
