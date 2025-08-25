<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cnaes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();   // Ex: 46.32-0-03
            $table->string('descricao');          // Ex: Comércio atacadista
            $table->tinyInteger('grau_risco');    // Ex: 1, 2, 3 ou 4
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cnaes');
    }
};
