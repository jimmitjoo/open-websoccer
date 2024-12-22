<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // t.ex. "2024/25"
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            // Säkerställ att datum inte överlappar
            $table->unique(['start_date', 'end_date']);
        });

        // Kopplingstabell för ligor och säsonger
        Schema::create('league_season', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id')->constrained()->cascadeOnDelete();
            $table->foreignId('season_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            // En liga kan bara ha en aktiv säsong åt gången
            $table->unique(['league_id', 'season_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('league_season');
        Schema::dropIfExists('seasons');
    }
};
