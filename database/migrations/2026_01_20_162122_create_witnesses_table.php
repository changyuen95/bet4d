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
        Schema::create('witnesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ic')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('draw_witnesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draw_id')->constrained('draws')->onDelete('cascade');
            $table->foreignId('witness_id')->constrained('witnesses')->onDelete('cascade');
            $table->timestamp('selected_at')->useCurrent();
            $table->boolean('has_signed')->default(false);
            $table->timestamp('signed_at')->nullable();
            $table->string('signature_path')->nullable();
            $table->timestamps();
            
            $table->unique(['draw_id', 'witness_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draw_witnesses');
        Schema::dropIfExists('witnesses');
    }
};
