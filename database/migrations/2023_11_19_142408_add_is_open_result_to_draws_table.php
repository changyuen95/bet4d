<?php

use App\Models\Draw;
use App\Models\DrawResult;
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
        Schema::table('draws', function (Blueprint $table) {
            $table->boolean('is_open_result')->after('year')->default(false);
        });

        $draws = Draw::all();
        foreach($draws as $draw){
            $firstPrice = $draw->results()->where('type', DrawResult::TYPE['1st'])->whereNotIn('number',['','-'])->first();
            if($firstPrice){
                $draw->update([
                    'is_open_result' => true
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('draws', function (Blueprint $table) {
            $table->dropColumn('is_open_result');
        });
    }
};
