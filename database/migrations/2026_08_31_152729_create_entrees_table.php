<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrees', function (Blueprint $table) {

            $table->id();

            $table->foreignId('categorie_entree_id')
                  ->constrained('categories_entrees')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('libelle');

            $table->decimal('montant',15,2);

            $table->date('date_operation');

            $table->text('description')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrees');
    }
};