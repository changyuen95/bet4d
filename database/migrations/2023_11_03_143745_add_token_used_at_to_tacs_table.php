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
            $table->dateTime('token_used_at')->after('verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tacs', function (Blueprint $table) {
            $table->dropColumn('token_used_at');
        });
    }
};
