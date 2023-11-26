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
        Schema::create('admin_credit_transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('admin_id');
            $table->foreignUlid('outlet_id');
            $table->decimal('amount',11,2);
            $table->decimal('before_amount',11,2);
            $table->decimal('after_amount',11,2);
            $table->enum('type', ['increase', 'decrease']); 
            $table->enum('transaction_type', ['topup', 'cleared']); 
            $table->string('reference_id')->unique()->nullable();
            $table->boolean('is_verified')->default(0);
            $table->ulidMorphs('targetable');
            $table->ulid('admin_clear_credit_transactions_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_credit_transactions');
    }
};
