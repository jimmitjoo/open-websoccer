<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stadiums', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Kapacitet
            $table->integer('capacity_seats')->default(0);
            $table->integer('capacity_stands')->default(0);
            $table->integer('capacity_vip')->default(0);

            // Kvalitetsnivåer (1-5)
            $table->tinyInteger('level_pitch')->default(3);
            $table->tinyInteger('level_seats')->default(3);
            $table->tinyInteger('level_stands')->default(3);
            $table->tinyInteger('level_vip')->default(1);

            // Underhållsnivåer (1-5)
            $table->tinyInteger('maintenance_pitch')->default(3);
            $table->tinyInteger('maintenance_facilities')->default(3);

            // Priser
            $table->decimal('price_seats', 8, 2)->default(0);
            $table->decimal('price_stands', 8, 2)->default(0);
            $table->decimal('price_vip', 8, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stadiums');
    }
};
