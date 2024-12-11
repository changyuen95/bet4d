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
            $table->integer('permutation_type')->nullable()->after('permutation_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_numbers', function (Blueprint $table) {
            $table->dropColumn('permutation_type');
        });
    }
};
