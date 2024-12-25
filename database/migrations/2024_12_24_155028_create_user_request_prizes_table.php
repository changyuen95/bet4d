<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_request_prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->constrained('users');
            $table->foreignUlid('ticket_id')->constrained('tickets');
            $table->foreignUlid('ticket_number_id')->constrained('ticket_numbers');
            $table->foreignUlid('winner_list_id')->constrained('winner_lists');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_request_prizes');
    }
};
