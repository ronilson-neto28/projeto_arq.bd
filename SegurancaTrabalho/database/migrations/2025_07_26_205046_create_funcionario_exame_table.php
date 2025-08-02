<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('funcionario_exame', function (Blueprint $table) {
            $table->id();

            $table->foreignId('funcionario_id')->constrained('funcionarios')->onDelete('cascade');
            $table->foreignId('exame_id')->constrained('exames')->onDelete('cascade');

            $table->date('data')->nullable(); // Quando foi feito o exame
            $table->string('status')->default('pendente'); // pendente, realizado, vencido, etc.
            $table->text('observacoes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionario_exame');
    }
};
