<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();

            // Identificação
            $table->string('razao_social');
            $table->string('nome_fantasia')->nullable();
            $table->string('cnpj', 14)->unique(); // somente dígitos

            // CNAE / risco (snapshot opcional)
            $table->foreignId('cnae_id')->nullable()->constrained('cnaes')->nullOnDelete();
            $table->unsignedTinyInteger('grau_risco')->nullable(); // 1..4 (snapshot)

            // Endereço / contato
            $table->string('cep', 8)->nullable(); // somente dígitos
            $table->string('endereco')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('email')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
