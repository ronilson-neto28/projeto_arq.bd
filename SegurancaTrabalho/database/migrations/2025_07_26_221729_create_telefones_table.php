<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telefones', function (Blueprint $table) {
            $table->id();

            // FK opcionais: telefone pode ser de empresa OU de funcionário
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('funcionario_id')->nullable()->constrained('funcionarios')->cascadeOnDelete();

            $table->string('numero'); // ex: 93999999999

            $table->timestamps();
        });

        // (opcional) restrição CHECK para garantir que pelo menos um dos FKs não seja nulo
        try {
            DB::statement("
                ALTER TABLE telefones
                ADD CONSTRAINT telefones_alvo_check
                CHECK ((empresa_id IS NOT NULL) OR (funcionario_id IS NOT NULL))
            ");
        } catch (\Throwable $e) {
            // alguns bancos podem não suportar CHECK aqui, então ignoramos
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('telefones');
    }
};
