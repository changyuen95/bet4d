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
        Schema::create('number_frequencies_details', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('number_frequency_id')->constrained();
            $table->integer('number');
            $table->integer('frequencies');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('number_frequencies_details');
    }
};
