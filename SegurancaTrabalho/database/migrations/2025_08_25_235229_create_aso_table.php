<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asos', function (Blueprint $table) {
            $table->id();

            // vínculo 1:1 com encaminhamento
            $table->foreignId('encaminhamento_id')->constrained('encaminhamentos')->cascadeOnDelete();

            // resultado final
            $table->string('aptidao'); // 'apto', 'inapto', 'apto_com_restricao'

            $table->date('data_emissao')->nullable();

            // médico emissor (texto livre; pode virar FK no futuro)
            $table->string('medico')->nullable();    // Nome — CRM/UF
            $table->string('arquivo_pdf_path')->nullable(); // caminho do PDF, se gerar arquivo

            $table->timestamps();

            // garante 1:1
            $table->unique('encaminhamento_id', 'asos_encaminhamento_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asos');
    }
};
