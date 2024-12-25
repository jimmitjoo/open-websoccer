<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('injured'); // eller vad kolumnen nu heter
            $table->dropColumn('injury_days');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->boolean('injured')->default(false);
            $table->integer('injury_days')->default(0);
        });
    }
};
