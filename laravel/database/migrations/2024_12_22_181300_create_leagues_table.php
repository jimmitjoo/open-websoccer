<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country_code', 2);
            $table->string('level')->default('national'); // national, continental, etc
            $table->integer('rank')->default(1); // 1 = högsta divisionen etc
            $table->boolean('has_relegation')->default(true);
            $table->boolean('has_promotion')->default(true);
            $table->integer('max_teams')->default(16);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leagues');
    }
};
