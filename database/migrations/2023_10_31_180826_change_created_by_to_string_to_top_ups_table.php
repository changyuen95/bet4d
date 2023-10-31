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
        Schema::table('top_ups', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
        });

        Schema::table('top_ups', function (Blueprint $table) {
            $table->ulidMorphs('creatable');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('top_ups', function (Blueprint $table) {
            $table->dropMorphs('creatable');
        });

        Schema::table('top_ups', function (Blueprint $table) {
            $table->foreignUlid('created_by')->nullable()->after('remark')->constrained('users');
        });
    }
};
