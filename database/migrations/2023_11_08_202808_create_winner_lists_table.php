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
        Schema::create('winner_lists', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('draw_result_id')->constrained();
            $table->foreignUlid('ticket_number_id')->constrained();
            $table->foreignUlid('user_id')->constrained();
            $table->foreignUlid('outlet_id')->constrained();
            $table->decimal('amount',11,2);
            $table->boolean('is_distribute')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('winner_lists');
    }
};
