<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encaminhamento_itens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('encaminhamento_id')->constrained('encaminhamentos')->cascadeOnDelete();

            // preferencialmente referenciar o catálogo:
            $table->foreignId('exame_id')->nullable()->constrained('exames')->nullOnDelete();

            // alternativa para congelar texto do exame (snapshot)
            $table->string('nome_snapshot')->nullable();

            $table->date('data')->nullable();
            $table->string('hora', 5)->nullable();
            $table->string('prestador')->nullable();

            $table->string('status')->default('solicitado'); // solicitado/agendado/realizado/recebido
            $table->string('laudo_path')->nullable();
            $table->string('resultado')->nullable();
            $table->string('justificativa')->nullable();

            // campos específicos úteis
            $table->boolean('referencia')->default(false); // audiometria de referência
            $table->string('regiao')->nullable();          // exemplo: "Coluna dorsal AP/Perfil"

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encaminhamento_itens');
    }
};
