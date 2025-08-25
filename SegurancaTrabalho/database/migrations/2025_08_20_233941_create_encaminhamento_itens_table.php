<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('encaminhamento_itens')) {
      Schema::create('encaminhamento_itens', function (Blueprint $t) {
        $t->id();
        $t->foreignId('encaminhamento_id')->constrained('encaminhamentos')->cascadeOnDelete();
        $t->string('procedimento');           // snapshot do catálogo
        $t->string('justificativa')->nullable();
        $t->date('data')->nullable();
        $t->string('hora', 5)->nullable();
        $t->string('prestador')->nullable();
        $t->string('status')->default('solicitado'); // solicitado/agendado/realizado/recebido
        $t->string('instrucoes')->nullable();
        $t->string('laudo_path')->nullable();
        $t->string('resultado')->nullable();
        $t->boolean('referencia')->default(false); // audiometria referência
        $t->string('regiao')->nullable();          // RX: Coluna dorsal AP/Perfil
        $t->timestamps();
      });
    }
  }
  public function down(): void { Schema::dropIfExists('encaminhamento_itens'); }
};
