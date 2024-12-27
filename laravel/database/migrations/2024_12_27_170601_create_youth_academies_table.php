<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('youth_academies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->foreignId('youth_academy_level_id')->constrained()->restrictOnDelete();
            $table->timestamp('next_youth_player_available_at')->nullable();
            $table->decimal('total_investment', 12, 2)->default(0);
            $table->timestamps();

            $table->unique('club_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('youth_academies');
    }
};
