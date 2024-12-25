<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->float('form_trend')->default(0.0);
            $table->integer('matches_played_recently')->default(0);
            $table->timestamp('last_form_update')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['form_trend', 'matches_played_recently', 'last_form_update']);
        });
    }
};
