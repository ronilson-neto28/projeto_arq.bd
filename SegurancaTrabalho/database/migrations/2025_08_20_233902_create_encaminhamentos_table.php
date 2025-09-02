<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encaminhamentos', function (Blueprint $table) {
            $table->id();

            // vínculos
            $table->foreignId('funcionario_id')->constrained('funcionarios')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();

            // dados do agendamento/guia
            $table->string('tipo_exame'); // Admissional, Periódico, Retorno, Mudança, Demissional
            $table->date('data_atendimento')->nullable();
            $table->string('hora_atendimento', 5)->nullable(); // HH:MM

            $table->string('status')->default('agendado'); // agendado/realizado/cancelado/...
            $table->string('local_clinica')->nullable();
            $table->string('medico_responsavel')->nullable(); // texto livre; pode virar FK no futuro

            // exceções/ajustes ao PGR
            $table->json('riscos_extra_json')->nullable();

            $table->text('observacoes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encaminhamentos');
    }
};
