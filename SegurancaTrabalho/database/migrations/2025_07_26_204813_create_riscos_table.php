<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riscos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_de_risco_id')->constrained('tipos_de_risco')->cascadeOnDelete();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->timestamps();

            $table->unique(['nome','tipo_de_risco_id'], 'riscos_nome_tipo_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riscos');
    }
};
