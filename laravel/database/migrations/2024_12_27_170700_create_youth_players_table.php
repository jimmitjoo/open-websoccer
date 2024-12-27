<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('youth_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('youth_academy_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedTinyInteger('age'); // 15-19
            $table->string('nationality', 2);
            $table->string('preferred_position');
            $table->unsignedTinyInteger('potential_rating');
            $table->unsignedTinyInteger('current_ability');
            $table->unsignedTinyInteger('development_progress')->default(0);
            $table->timestamp('promotion_available_at')->nullable();

            // Grundläggande attribut (samma som vanliga spelare)
            $table->unsignedTinyInteger('strength')->default(0);
            $table->unsignedTinyInteger('speed')->default(0);
            $table->unsignedTinyInteger('technique')->default(0);
            $table->unsignedTinyInteger('passing')->default(0);
            $table->unsignedTinyInteger('shooting')->default(0);
            $table->unsignedTinyInteger('heading')->default(0);
            $table->unsignedTinyInteger('tackling')->default(0);
            $table->unsignedTinyInteger('ball_control')->default(0);
            $table->unsignedTinyInteger('stamina')->default(0);
            $table->unsignedTinyInteger('keeper_ability')->default(0);

            // Personlighetsattribut
            $table->unsignedTinyInteger('determination')->default(0);
            $table->unsignedTinyInteger('work_rate')->default(0);
            $table->unsignedTinyInteger('leadership')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('youth_players');
    }
};
