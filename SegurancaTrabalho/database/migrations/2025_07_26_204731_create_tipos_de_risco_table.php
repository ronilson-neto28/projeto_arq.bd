<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_de_risco', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique(); // Físico, Químico, Biológico, Ergonômico, Acidente
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_de_risco');
    }
};
