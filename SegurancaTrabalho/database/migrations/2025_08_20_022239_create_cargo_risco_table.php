<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cargo_risco', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('risco_id')->constrained('riscos')->cascadeOnDelete();

            // Campos úteis do PGR (opcionais)
            $table->string('fonte_geradora')->nullable();   // ex.: Máquina X, Processo Y
            $table->string('intensidade')->nullable();      // ex.: 88 dB, 50 ppm
            $table->string('medidas_controle')->nullable(); // ex.: EPI: CA xxxx; EPC: enclausuramento

            $table->timestamps();

            $table->unique(['cargo_id','risco_id']); // evita duplicados
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargo_risco');
    }
};
