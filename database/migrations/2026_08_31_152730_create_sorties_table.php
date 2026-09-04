<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sorties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('categorie_sortie_id')
                ->constrained('categories_sorties')
                ->cascadeOnDelete();

            $table->string('libelle');
            $table->decimal('montant', 12, 2);
            $table->date('date_sortie');
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sorties');
    }
};