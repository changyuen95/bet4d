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
            $table->string('permutation_image')->nullable()->after('type');
            $table->string('permutation_number')->nullable()->after('permutation_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_numbers', function (Blueprint $table) {
            $table->dropColumn('permutation_image');
            $table->dropColumn('permutation_number');
        });
    }
};
