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
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // Optional title for the pop-up
            $table->text('description')->nullable(); // Optional description for additional details
            $table->string('image')->nullable(); // Path to the image file
            $table->enum('status', ['active', 'inactive'])->default('active'); // Pop-up status
            $table->timestamp('start_time')->nullable(); // When the pop-up should start showing
            $table->timestamp('end_time')->nullable(); // When the pop-up should stop showing
            $table->timestamps();
            $table->softDeletes(); // To allow soft-deletion
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popups');
    }
};
