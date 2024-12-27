<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('youth_player_development_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('youth_player_id')->constrained()->cascadeOnDelete();
            $table->string('attribute_name');
            $table->tinyInteger('old_value');
            $table->tinyInteger('new_value');
            $table->string('development_type'); // 'training', 'natural', 'event'
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('youth_player_development_logs');
    }
};
