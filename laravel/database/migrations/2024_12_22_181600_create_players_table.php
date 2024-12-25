<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->foreignId('club_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('strength')->default(50);

            $table->integer('form')->default(60);

            $table->integer('stamina')->default(50);
            $table->integer('speed')->default(50);
            $table->integer('technique')->default(50);
            $table->integer('passing')->default(50);
            $table->integer('goalkeeper')->default(50);
            $table->integer('defense')->default(50);
            $table->integer('midfield')->default(50);
            $table->integer('striker')->default(50);
            $table->date('birth_date');
            $table->string('position'); // GK, DEF, MID, FWD
            $table->boolean('injured')->default(false);
            $table->integer('injury_days')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
