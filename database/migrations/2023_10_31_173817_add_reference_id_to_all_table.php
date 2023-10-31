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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('reference_id')->unique()->after('id')->nullable();
        });

        Schema::table('draws', function (Blueprint $table) {
            $table->string('reference_id')->unique()->after('id')->nullable();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->string('reference_id')->unique()->after('id')->nullable();
        });

        Schema::table('outlets', function (Blueprint $table) {
            $table->string('reference_id')->unique()->after('id')->nullable();
        });

        Schema::table('platforms', function (Blueprint $table) {
            $table->string('reference_id')->unique()->after('id')->nullable();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->string('reference_id')->unique()->after('id')->nullable();
        });

        Schema::table('top_ups', function (Blueprint $table) {
            $table->string('reference_id')->unique()->after('id')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('reference_id')->unique()->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });

        Schema::table('draws', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });

        Schema::table('outlets', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });

        Schema::table('platforms', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });

        Schema::table('top_ups', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });
    }
};
