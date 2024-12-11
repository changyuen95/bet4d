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
        Schema::table('potential_winning_price_lists', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('potential_winning_price_lists', function (Blueprint $table) {
            $table->enum('type',['straight','box','e-box'])->after('id');
        });
        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('potential_winning_price_lists', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('potential_winning_price_lists', function (Blueprint $table) {
            $table->enum('type',['straight','permutation'])->after('id');
        });
    }
};
