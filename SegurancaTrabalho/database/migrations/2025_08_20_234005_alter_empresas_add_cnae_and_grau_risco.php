<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('empresas', function (Blueprint $t) {
      if (!Schema::hasColumn('empresas', 'cnae_id')) {
        $t->foreignId('cnae_id')->nullable()->after('cnpj')->constrained('cnaes');
      }
      if (!Schema::hasColumn('empresas', 'grau_risco')) {
        $t->unsignedTinyInteger('grau_risco')->nullable()->after('cnae_id');
      }
    });
  }
  public function down(): void {
    Schema::table('empresas', function (Blueprint $t) {
      if (Schema::hasColumn('empresas', 'grau_risco')) $t->dropColumn('grau_risco');
      if (Schema::hasColumn('empresas', 'cnae_id'))    $t->dropConstrainedForeignId('cnae_id');
    });
  }
};
