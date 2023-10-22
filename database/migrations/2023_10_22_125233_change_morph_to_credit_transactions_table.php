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
        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->dropColumn('target_id');
            $table->dropColumn('target_type');
            $table->ulidMorphs('targetable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_transactions', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropMorphs('targetable');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        });
        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->ulid('target_id');
            $table->string('target_type');
        });
    }
};
