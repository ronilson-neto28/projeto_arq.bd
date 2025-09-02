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
            $table->string('codigo')->unique();   // ex: 0111-3/01
            $table->string('descricao');
            $table->unsignedTinyInteger('grau_risco'); // 1..4 (NR-4)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cnaes');
    }
};
