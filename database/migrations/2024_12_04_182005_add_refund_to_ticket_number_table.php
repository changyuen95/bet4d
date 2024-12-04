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
        Schema::table('ticket_numbers', function (Blueprint $table) {
            //
            $table->decimal('actual_small_amount',11,2);
            $table->decimal('actual_big_amount',11,2);
            $table->decimal('refund_amount',11,2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_numbers', function (Blueprint $table) {
            //
            $table->dropColumn('actual_small_amount');
            $table->dropColumn('actual_big_amount');
            $table->dropColumn('refund_amount');

        });
    }
};
