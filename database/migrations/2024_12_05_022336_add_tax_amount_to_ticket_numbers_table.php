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
            $table->decimal('tax_amount', 11, 2)->default(0);
            $table->decimal('actual_tax_amount', 11, 2)->default(0);
            $table->foreignUlid('top_up_id')->nullable()->constrained('top_ups');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_numbers', function (Blueprint $table) {
            //
            $table->dropColumn('tax_amount');
            $table->dropColumn('actual_tax_amount');
            $table->dropForeign(['top_up_id']);
        });
    }
};
