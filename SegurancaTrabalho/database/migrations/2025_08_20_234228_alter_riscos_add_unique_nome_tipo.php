<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('riscos', function (Blueprint $t) {
      if (Schema::hasColumn('riscos', 'nome') && Schema::hasColumn('riscos', 'tipo_de_risco_id')) {
        $t->unique(['nome','tipo_de_risco_id'], 'riscos_nome_tipo_unique');
      }
    });
  }
  public function down(): void {
    Schema::table('riscos', function (Blueprint $t) {
      try { $t->dropUnique('riscos_nome_tipo_unique'); } catch (\Throwable $e) {}
    });
  }
};
