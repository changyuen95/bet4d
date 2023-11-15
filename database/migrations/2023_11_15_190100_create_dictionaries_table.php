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
        $tableName = 'dictionaries'; // Replace with the actual name of your table

        if (!Schema::hasTable($tableName)) {
            Schema::create('dictionaries', function (Blueprint $table) {
                $table->id();
                $table->string('keyword_en',191)->nullable();
                $table->string('keyword_ch',191)->nullable();
                $table->string('number',191)->nullable();
                $table->text('image_path')->nullable();
                $table->timestamps();
            });
        }
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictionaries');
    }
};
