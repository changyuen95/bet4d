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
        Schema::table('bank_receipt', function (Blueprint $table) {
            //
            $table->foreignUlid('top_up_id')->nullable()->constrained('top_ups');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_receipt', function (Blueprint $table) {
            //
            $table->dropForeign(['top_up_id']);
        });
    }
};
