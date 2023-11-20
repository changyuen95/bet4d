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
        Schema::create('winner_list_displays', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('draw_id')->constrained();
            $table->foreignUlid('draw_result_id')->constrained();
            $table->foreignUlid('outlet_id')->constrained();
            $table->decimal('winning_amount',10,2);
            $table->integer('no_of_user');
            $table->integer('number');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('winner_list_displays');
    }
};
