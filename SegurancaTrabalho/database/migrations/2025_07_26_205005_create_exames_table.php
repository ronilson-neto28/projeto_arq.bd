<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exames', function (Blueprint $table) {
            $table->id();

            // catálogo de exames/procedimentos
            $table->string('nome');                             // Ex.: Exame clínico ocupacional, Audiometria, Hemograma
            $table->string('categoria')->nullable();            // Ex.: Clínica, Laboratório, Imagem, Audiometria
            $table->unsignedSmallInteger('periodicidade_meses')->nullable(); // Ex.: 12, 24 (se quiser usar)
            $table->string('instrucoes_padrao')->nullable();    // Ex.: Jejum 8h, levar RX anterior
            $table->text('observacoes')->nullable();

            $table->timestamps();

            // (opcional) se quiser evitar duplicados de nome
            // $table->unique('nome');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exames');
    }
};
