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
        Schema::create('admin_clear_credit_transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('admin_id');
            $table->decimal('amount',11,2);
            $table->string('reference_id')->unique()->nullable();
            $table->string('image_path');
            $table->date('date_clear_from');
            $table->date('date_clear_to');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_clear_credit_transactions');
    }
};
