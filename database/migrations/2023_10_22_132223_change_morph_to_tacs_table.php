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
        Schema::table('tacs', function (Blueprint $table) {
            $table->dropColumn('ownerable_id');
            $table->dropColumn('ownerable_type');
        });
        Schema::table('tacs', function (Blueprint $table) {
            $table->nullableUlidMorphs('ownerable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tacs', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropMorphs('ownerable');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        });
        Schema::table('tacs', function (Blueprint $table) {
            $table->string('ownerable_id')->nullable()->after('verify_code');
            $table->string('ownerable_type')->nullable()->after('ownerable_id');
        });
    }
};
