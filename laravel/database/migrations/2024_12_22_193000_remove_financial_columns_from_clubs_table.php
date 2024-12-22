<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn(['budget', 'income', 'expenses']);
        });
    }

    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->decimal('budget', 12, 2)->default(0);
            $table->decimal('income', 12, 2)->default(0);
            $table->decimal('expenses', 12, 2)->default(0);
        });
    }
};
