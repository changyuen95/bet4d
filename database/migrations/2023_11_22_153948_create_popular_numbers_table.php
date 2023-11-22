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
        Schema::create('popular_numbers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('number');
            $table->string('last_prize');
            $table->date('date');
            $table->integer('no_of_times_drawn');
            $table->integer('position');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popular_numbers');
    }
};
