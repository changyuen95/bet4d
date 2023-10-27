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
        Schema::table('platforms', function (Blueprint $table) {
            //
            $table->string('primary_colour',50)->after('label');
            $table->ulid('secondary_colour',50)->after('primary_colour');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platform', function (Blueprint $table) {
            //
            $table->dropColumn('primary_colour');
            $table->dropColumn('secondary_colour');

        });
    }
};
