<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('injury_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('min_days');
            $table->integer('max_days');
            $table->enum('severity', ['minor', 'moderate', 'severe']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('injury_types');
    }
};
