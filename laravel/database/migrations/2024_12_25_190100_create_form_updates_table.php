<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->integer('old_value');
            $table->integer('new_value');
            $table->string('reason')->nullable();
            $table->foreignId('adjusted_by')->nullable()->constrained('users');
            $table->foreignId('match_id')->nullable()->constrained('matches');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_updates');
    }
};
