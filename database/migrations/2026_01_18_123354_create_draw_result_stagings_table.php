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
        Schema::create('draw_result_stagings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('draw_id')->constrained();
            $table->enum('type',['1st','2nd','3rd','special','consolation']);
            $table->integer('position');
            $table->string('number');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draw_result_stagings');
    }
};
