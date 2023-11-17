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
        Schema::create('draw_calendars', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('platform_id')->constrained();
            $table->date('date');
            $table->enum('type',['normal','special']);
            $table->string('color');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draw_calendars');
    }
};
