<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name', 5);
            $table->string('logo_path')->nullable();

            // Ekonomi
            $table->decimal('budget', 12, 2)->default(0);
            $table->decimal('income', 12, 2)->default(0);
            $table->decimal('expenses', 12, 2)->default(0);

            // Relationer
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('stadium_id')
                ->nullable()
                ->references('id')
                ->on('stadiums')
                ->nullOnDelete();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('club_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->string('type'); // income/expense
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_transactions');
        Schema::dropIfExists('clubs');
    }
};
