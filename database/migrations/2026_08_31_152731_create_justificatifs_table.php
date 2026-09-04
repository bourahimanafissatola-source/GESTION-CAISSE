<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('justificatifs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('entree_id')
                ->nullable()
                ->constrained('entrees')
                ->nullOnDelete();

            $table->foreignId('sortie_id')
                ->nullable()
                ->constrained('sorties')
                ->nullOnDelete();

            $table->string('fichier');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('justificatifs');
    }
};