<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('sorties', function (Blueprint $table) {
        $table->string('beneficiaire')->nullable()->after('date_sortie');
    });
}

public function down(): void
{
    Schema::table('sorties', function (Blueprint $table) {
        $table->dropColumn('beneficiaire');
    });
}
};
