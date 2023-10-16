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
        Schema::create('ticket_numbers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ticket_id');
            $table->string('number');
            $table->decimal('small_amount',11,2);
            $table->decimal('big_amount',11,2);
            $table->integer('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_numbers');
    }
};
