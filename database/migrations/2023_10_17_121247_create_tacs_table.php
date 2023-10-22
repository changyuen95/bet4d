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
        Schema::create('tacs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('phone_e164');
            $table->bigInteger('verify_code');
            $table->nullableUlidMorphs('ownerable');
            $table->longText('response')->nullable();
            $table->string('ref')->nullable();
            $table->dateTime('available_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tacs');
    }
};
