<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('encaminhamentos')) {
      Schema::create('encaminhamentos', function (Blueprint $t) {
        $t->id();
        $t->string('numero_guia')->nullable()->index();
        $t->date('data_emissao')->nullable();
        $t->string('medico_solicitante'); // Nome — CRM/UF

        $t->foreignId('funcionario_id')->constrained('funcionarios');
        $t->foreignId('empresa_id')->nullable()->constrained('empresas');
        $t->foreignId('cargo_id')->nullable()->constrained('cargos');

        $t->string('tipo_exame'); // Admissional/Periódico/...
        $t->date('data_atendimento')->nullable();
        $t->string('hora_atendimento', 5)->nullable();

        $t->json('riscos_extra_json')->nullable();
        $t->text('observacoes')->nullable();
        $t->date('previsao_retorno')->nullable();
        $t->string('responsavel_marcacao')->nullable();
        $t->string('escopo_registro')->default('solicitacao'); // solicitacao|solicitacao_conclusao
        $t->timestamps();
      });
    }
  }
  public function down(): void { Schema::dropIfExists('encaminhamentos'); }
};
