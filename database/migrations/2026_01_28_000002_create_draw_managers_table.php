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
        Schema::create('draw_managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draw_id')->constrained('draws')->onDelete('cascade');
            $table->foreignId('recorded_by_id')->nullable()->constrained('managers')->onDelete('set null');
            $table->foreignId('certified_by_id')->nullable()->constrained('managers')->onDelete('set null');
            $table->timestamps();
            
            $table->unique('draw_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draw_managers');
    }
};
