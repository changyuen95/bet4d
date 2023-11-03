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
            $table->dropColumn('status');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->enum('status',['active','inactive','disabled'])->after('remember_token');
        });

        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->enum('type',['increase','decrease'])->after('amount');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->enum('status',['active','inactive'])->after('name');
        });

        Schema::table('platforms', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('platforms', function (Blueprint $table) {
            $table->enum('status',['active','inactive'])->after('name');
        });

        Schema::table('point_transactions', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('point_transactions', function (Blueprint $table) {
            $table->enum('type',['increase','decrease'])->after('point');
        });

        Schema::table('ticket_numbers', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('ticket_numbers', function (Blueprint $table) {
            $table->enum('type',['straight','permutation'])->after('big_amount');
        });

        Schema::table('top_ups', function (Blueprint $table) {
            $table->dropColumn('top_up_with');
        });

        Schema::table('top_ups', function (Blueprint $table) {
            $table->enum('top_up_with',['outlet','qr'])->after('amount');
        });

        Schema::table('transfer_options', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('transfer_options', function (Blueprint $table) {
            $table->enum('type',['bank','ewallet'])->after('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('status',['active','inactive','disabled'])->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->integer('status')->after('remember_token');
        });

        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->integer('type')->after('amount');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->integer('status')->after('name');
        });

        Schema::table('platforms', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('platforms', function (Blueprint $table) {
            $table->integer('status')->after('name');
        });

        Schema::table('point_transactions', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('point_transactions', function (Blueprint $table) {
            $table->integer('type')->after('point');
        });

        Schema::table('ticket_numbers', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('ticket_numbers', function (Blueprint $table) {
            $table->integer('type')->after('big_amount');
        });

        Schema::table('top_ups', function (Blueprint $table) {
            $table->dropColumn('top_up_with');
        });

        Schema::table('top_ups', function (Blueprint $table) {
            $table->integer('top_up_with')->after('amount');
        });

        Schema::table('transfer_options', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('transfer_options', function (Blueprint $table) {
            $table->integer('type')->after('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('status')->after('remember_token');
        });
    }
};
