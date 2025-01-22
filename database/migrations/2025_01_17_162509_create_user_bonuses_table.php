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
        Schema::create('user_bonuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('amount', 10, 2); // Bonus amount
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();

            // Foreign keys
            $table->char('user_id', 26)->nullable(); // Match CHAR(26) of `users`
            $table->char('bonus_id', 26)->nullable(); // Match CHAR(26) of `bonuses`

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bonus_id')->references('id')->on('bonuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bonuses');
    }
};
