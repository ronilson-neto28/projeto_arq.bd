<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargo_risco', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('risco_id')->constrained('riscos')->cascadeOnDelete();

            // Campos opcionais úteis do PGR
            $table->string('fonte_geradora')->nullable();
            $table->string('intensidade')->nullable();
            $table->string('medidas_controle')->nullable();

            $table->timestamps();

            $table->unique(['cargo_id','risco_id'], 'cargo_risco_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargo_risco');
    }
};
