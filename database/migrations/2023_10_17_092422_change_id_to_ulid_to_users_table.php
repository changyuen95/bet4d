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
        Schema::table('users', function (Blueprint $table) {
            $table->ulid('id')->change();
        });

        Schema::table('draws', function (Blueprint $table) {
            $table->ulid('id')->change();
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->ulid('model_id')->change();
        });

        Schema::table('outlets', function (Blueprint $table) {
            $table->ulid('id')->change();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->ulid('id')->change();
            $table->ulid('outlet_id')->change();
            $table->ulid('platform_id')->change();
            $table->ulid('game_id')->change();
            $table->ulid('draw_id')->change();
        });

        Schema::table('ticket_numbers', function (Blueprint $table) {
            $table->ulid('id')->change();
            $table->ulid('ticket_id')->change();
        });

        Schema::table('user_credits', function (Blueprint $table) {
            $table->ulid('id')->change();
            $table->ulid('user_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        
        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
            $table->bigInteger('user_id')->change();
            $table->bigInteger('target_id')->change();
        });

        Schema::table('draws', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->bigInteger('model_id')->change();
        });

        Schema::table('outlets', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
            $table->bigInteger('outlet_id')->change();
            $table->bigInteger('platform_id')->change();
            $table->bigInteger('game_id')->change();
            $table->bigInteger('draw_id')->change();
        });

        Schema::table('ticket_numbers', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
            $table->bigInteger('ticket_id')->change();
        });

        Schema::table('user_credits', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
            $table->bigInteger('user_id')->change();
        });
    }
};
