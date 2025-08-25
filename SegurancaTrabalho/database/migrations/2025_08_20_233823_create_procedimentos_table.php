<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('procedimentos')) {
      Schema::create('procedimentos', function (Blueprint $t) {
        $t->id();
        $t->string('nome')->unique();
        $t->string('categoria')->nullable();          // Clínica, Laboratório, Imagem, Audiometria
        $t->string('prestador_padrao')->nullable();   // Clínica/Lab/Imagem/Audiometria
        $t->boolean('flag_audiometria')->default(false);
        $t->boolean('flag_rx_com_regiao')->default(false);
        $t->unsignedSmallInteger('periodicidade_meses')->nullable();
        $t->string('instrucoes_padrao')->nullable();
        $t->timestamps();
      });
    }
  }
  public function down(): void { Schema::dropIfExists('procedimentos'); }
};
