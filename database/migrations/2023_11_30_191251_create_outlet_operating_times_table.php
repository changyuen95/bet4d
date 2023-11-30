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
        Schema::create('outlet_operating_times', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('outlet_id')->constrained();
            $table->enum('day',['monday','tuesday','wednesday','thursday','friday','saturday','sunday']);
            $table->time('from_time');
            $table->time('to_time');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlet_operating_times');
    }
};
