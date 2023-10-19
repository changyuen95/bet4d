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
        Schema::create('potential_winning_price_lists', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->integer('type_id');
            $table->decimal('big1st', 11,2)->nullable();
            $table->decimal('big2nd', 11,2)->nullable();
            $table->decimal('big3rd', 11,2)->nullable();
            $table->decimal('big_special', 11,2)->nullable();
            $table->decimal('big_consolation', 11,2)->nullable();
            $table->decimal('small1st', 11,2)->nullable();
            $table->decimal('small2nd', 11,2)->nullable();
            $table->decimal('small3rd', 11,2)->nullable();
            $table->decimal('small_special', 11,2)->nullable();
            $table->decimal('small_consolation', 11,2)->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potential_winning_price_lists');
    }
};
