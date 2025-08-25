<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('cargos', function (Blueprint $t) {
      if (!Schema::hasColumn('cargos', 'empresa_id')) {
        $t->foreignId('empresa_id')->after('id')->constrained('empresas')->cascadeOnDelete();
      }
      // índice em descricao + empresa, se fizer sentido:
      if (!Schema::hasColumn('cargos', 'descricao')) {
        $t->string('descricao')->after('empresa_id');
      }
    });
  }
  public function down(): void {
    Schema::table('cargos', function (Blueprint $t) {
      if (Schema::hasColumn('cargos', 'empresa_id')) $t->dropConstrainedForeignId('empresa_id');
      // não derrubo descricao aqui para não perder dados sem querer
    });
  }
};
