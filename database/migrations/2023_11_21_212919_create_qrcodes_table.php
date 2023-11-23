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
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->nullable();  // For admin to easily identify which qr code 
            $table->integer('scan_limit')->default(99);
            $table->decimal('credit',11,2);
            $table->tinyInteger('status')->default(1)->comment('0:inactive,1:active');
            $table->longText('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qrcodes');
    }
};
