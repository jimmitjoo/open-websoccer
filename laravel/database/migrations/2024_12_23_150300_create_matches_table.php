<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();

            // Grundläggande matchinformation
            $table->foreignId('league_id')->constrained('leagues');
            $table->foreignId('season_id')->constrained('seasons');
            $table->foreignId('home_club_id')->constrained('clubs');
            $table->foreignId('away_club_id')->constrained('clubs');
            $table->foreignId('stadium_id')->nullable()->constrained('stadiums');

            // Matchdetaljer
            $table->integer('matchday')->unsigned();
            $table->dateTime('scheduled_at');
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();

            // Matchstatus
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])
                  ->default('scheduled');

            // Matchtyp (för framtida cupmatcher etc)
            $table->enum('type', ['league', 'cup', 'friendly'])
                  ->default('league');

            $table->timestamps();

            // Index för prestanda
            $table->index(['league_id', 'season_id', 'matchday']);
            $table->index(['scheduled_at', 'status']);
            $table->index(['home_club_id', 'status']);
            $table->index(['away_club_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
