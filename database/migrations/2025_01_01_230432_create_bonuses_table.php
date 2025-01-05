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
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Bonus name, e.g., "First Top-Up Bonus"
            $table->enum('type', ['fixed', 'percentage']); // Bonus type: fixed or percentage
            $table->decimal('value', 10, 2); // Bonus value (e.g., RM10 for fixed or 10% for percentage)
            $table->decimal('min_topup_amount', 10, 2)->nullable(); // Minimum top-up amount required
            $table->decimal('max_bonus', 10, 2)->nullable(); // Maximum bonus amount (for percentage-based bonuses)
            $table->enum('target', ['first_topup', 'registration', 'general'])->default('general'); // Bonus applicability
            $table->dateTime('valid_from')->nullable(); // Start of bonus validity
            $table->dateTime('valid_until')->nullable(); // End of bonus validity
            $table->enum('status', ['active', 'inactive'])->default('active'); // Bonus status
            $table->text('description')->nullable(); // Optional bonus description
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonuses');
    }
};
