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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Schema::table('credit_transactions', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
            Schema::table('credit_transactions', function (Blueprint $table) {
                $table->foreignUlid('user_id')->after('id')->constrained();
            });

            Schema::table('draws', function (Blueprint $table) {
                $table->dropColumn('platform_id');
            });
            Schema::table('draws', function (Blueprint $table) {
                $table->foreignUlid('platform_id')->after('id')->constrained();
            });

            Schema::table('games', function (Blueprint $table) {
                $table->dropColumn('platform_id');
            });
            Schema::table('games', function (Blueprint $table) {
                $table->foreignUlid('platform_id')->after('id')->constrained();
            });

            Schema::table('outlets', function (Blueprint $table) {
                $table->dropColumn('platform_id');
            });
            Schema::table('outlets', function (Blueprint $table) {
                $table->foreignUlid('platform_id')->after('id')->constrained();
            });

            Schema::table('tickets', function (Blueprint $table) {
                $table->dropColumn('user_id');
                $table->dropColumn('outlet_id');
                $table->dropColumn('platform_id');
                $table->dropColumn('game_id');
                $table->dropColumn('draw_id');
            });
            Schema::table('tickets', function (Blueprint $table) {
                $table->foreignUlid('user_id')->after('id')->constrained();
                $table->foreignUlid('outlet_id')->after('user_id')->constrained();
                $table->foreignUlid('platform_id')->after('outlet_id')->constrained();
                $table->foreignUlid('game_id')->after('platform_id')->constrained();
                $table->foreignUlid('draw_id')->after('game_id')->constrained();
            });

            Schema::table('ticket_numbers', function (Blueprint $table) {
                $table->dropColumn('ticket_id');
            });
            Schema::table('ticket_numbers', function (Blueprint $table) {
                $table->foreignUlid('ticket_id')->after('id')->constrained();
            });

            Schema::table('user_credits', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
            Schema::table('user_credits', function (Blueprint $table) {
                $table->foreignUlid('user_id')->after('id')->constrained();
            });
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_transactions', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropConstrainedForeignId('user_id');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        });
        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->ulid('user_id')->after('id');
        });

        Schema::table('draws', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropConstrainedForeignId('platform_id');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        });
        Schema::table('draws', function (Blueprint $table) {
            $table->ulid('platform_id')->after('id');
        });

        Schema::table('games', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropConstrainedForeignId('platform_id');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        });
        Schema::table('games', function (Blueprint $table) {
            $table->ulid('platform_id')->after('id');
        });

        Schema::table('outlets', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropConstrainedForeignId('platform_id');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        });
        Schema::table('outlets', function (Blueprint $table) {
            $table->ulid('platform_id')->after('id');
        });

        Schema::table('tickets', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('outlet_id');
            $table->dropConstrainedForeignId('platform_id');
            $table->dropConstrainedForeignId('game_id');
            $table->dropConstrainedForeignId('draw_id');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->ulid('user_id')->after('id');
            $table->ulid('outlet_id')->after('id');
            $table->ulid('platform_id')->after('id');
            $table->ulid('game_id')->after('id');
            $table->ulid('draw_id')->after('id');
        });

        Schema::table('ticket_numbers', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropConstrainedForeignId('ticket_id');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        });
        Schema::table('ticket_numbers', function (Blueprint $table) {
            $table->ulid('ticket_id')->after('id');
        });

        Schema::table('user_credits', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropConstrainedForeignId('user_id');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        });
        Schema::table('user_credits', function (Blueprint $table) {
            $table->ulid('user_id')->after('id');
        });
    }
};
