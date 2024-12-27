<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('youth_players', function (Blueprint $table) {
            $table->timestamp('last_training_at')->nullable()->after('leadership');
        });
    }

    public function down(): void
    {
        Schema::table('youth_players', function (Blueprint $table) {
            $table->dropColumn('last_training_at');
        });
    }
};
