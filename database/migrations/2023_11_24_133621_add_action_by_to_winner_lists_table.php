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
        Schema::table('winner_lists', function (Blueprint $table) {
            $table->foreignUlid('action_by')->nullable()->after('user_id')->constrained('admins');
            $table->string('distribute_attachment')->nullable()->after('is_distribute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('winner_lists', function (Blueprint $table) {
            $table->dropConstrainedForeignId('action_by');
            $table->dropColumn('distribute_attachment');
        });
    }
};
