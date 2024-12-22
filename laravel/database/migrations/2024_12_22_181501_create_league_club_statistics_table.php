<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('league_club_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->foreignId('league_id')->constrained()->cascadeOnDelete();
            $table->foreignId('season_id')->constrained()->cascadeOnDelete();

            // Matchstatistik
            $table->integer('matches_played')->default(0);
            $table->integer('wins')->default(0);
            $table->integer('draws')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('goals_for')->default(0);
            $table->integer('goals_against')->default(0);
            $table->integer('points')->default(0);

            // Position
            $table->integer('current_position')->nullable();
            $table->integer('highest_position')->nullable();
            $table->integer('lowest_position')->nullable();

            // Extra statistik
            $table->integer('clean_sheets')->default(0);
            $table->integer('failed_to_score')->default(0);

            // Composite unique key för att säkerställa unik kombination
            $table->unique(['club_id', 'league_id', 'season_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('league_club_statistics');
    }
};
