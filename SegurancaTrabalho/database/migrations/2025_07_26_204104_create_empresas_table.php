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
    Schema::create('empresas', function (Blueprint $table) {
        $table->id();

        // Identificação
        //$table->string('nome', 255);                         // Se não usar 'nome', pode remover
        $table->string('razao_social', 255)->nullable();
        $table->string('nome_fantasia', 255)->nullable();
        $table->string('cnpj', 14)->unique();                // armazenar só dígitos

        // CNAE / risco
        $table->foreignId('cnae_id')->nullable()->constrained('cnaes')->nullOnDelete();
        $table->unsignedTinyInteger('grau_risco')->nullable(); // 1..4

        // Endereço/Contato (armazenar sem máscara)
        $table->string('cep', 8)->nullable();                // só dígitos (ex: 68000000)
        $table->string('endereco', 255)->nullable();
        $table->string('bairro', 120)->nullable();
        $table->string('cidade', 120)->nullable();
        $table->string('uf', 2)->nullable();
        $table->string('email', 255)->nullable();
        $table->string('telefone', 11)->nullable();          // só dígitos (10 ou 11)

        // Responsáveis
        $table->string('medico_pcmso_nome', 255)->nullable();
        $table->string('medico_pcmso_crm', 50)->nullable();
        $table->string('medico_pcmso_uf', 2)->nullable();
        $table->string('medico_pcmso_email', 255)->nullable();

        $table->string('resp_sst_nome', 255)->nullable();
        $table->string('resp_sst_registro', 120)->nullable();
        $table->string('resp_sst_email', 255)->nullable();

        $table->timestamps(); // created_at e updated_at (uma única vez)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
