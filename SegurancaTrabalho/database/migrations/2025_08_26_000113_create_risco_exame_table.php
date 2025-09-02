<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risco_exame', function (Blueprint $table) {
            $table->id();

            $table->foreignId('risco_id')->constrained('riscos')->cascadeOnDelete();
            $table->foreignId('exame_id')->constrained('exames')->cascadeOnDelete();

            // regra auxiliar (opcional) para automação/sugestão
            $table->unsignedSmallInteger('periodicidade_meses')->nullable();

            $table->timestamps();

            $table->unique(['risco_id','exame_id'], 'risco_exame_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risco_exame');
    }
};
