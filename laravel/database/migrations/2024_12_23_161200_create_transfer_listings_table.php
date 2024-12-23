<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->integer('asking_price');
            $table->enum('status', ['active', 'completed', 'cancelled']);
            $table->timestamp('deadline')->nullable();
            $table->timestamps();
        });

        Schema::create('transfer_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bidding_club_id')->constrained('clubs');
            $table->integer('amount');
            $table->foreignId('exchange_player_1_id')->nullable()->constrained('players');
            $table->foreignId('exchange_player_2_id')->nullable()->constrained('players');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'cancelled']);
            $table->timestamps();
        });

        Schema::create('transfer_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained();
            $table->foreignId('from_club_id')->nullable()->constrained('clubs');
            $table->foreignId('to_club_id')->constrained('clubs');
            $table->integer('fee');
            $table->foreignId('exchange_player_1_id')->nullable()->constrained('players');
            $table->foreignId('exchange_player_2_id')->nullable()->constrained('players');
            $table->enum('type', ['transfer', 'free_agent']);
            $table->timestamps();
        });
    }
};
