<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('youth_academy_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('level'); // 1-20
            $table->decimal('monthly_cost', 12, 2);
            $table->decimal('upgrade_cost', 12, 2);
            $table->unsignedTinyInteger('max_youth_players');
            $table->unsignedTinyInteger('youth_player_generation_rate'); // Antal dagar mellan nya spelare
            $table->unsignedTinyInteger('min_potential_rating');
            $table->unsignedTinyInteger('max_potential_rating');
            $table->unsignedTinyInteger('training_efficiency_bonus'); // Procentuell bonus för träning
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('youth_academy_levels');
    }
};
