<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use App\Models\DrawResult;
use App\Models\DrawResultStaging;
use App\Models\Jackpot;
use App\Models\Marquee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = [];
        $draw = Draw::where('expired_at', '<', Carbon::now())->orderBy('expired_at', 'DESC')->first();
        $jackpotResult = Jackpot::first();
        $firstPrize = $draw->results()->where('type',DrawResult::TYPE['1st'])->first();
        $secondPrize = $draw->results()->where('type',DrawResult::TYPE['2nd'])->first();
        $thirdPrize = $draw->results()->where('type',DrawResult::TYPE['3rd'])->first();
        $specialPrizes = $draw->results()->where('type',DrawResult::TYPE['special'])->orderBy('position')->pluck('number')->toArray();
        $consolationPrizes = $draw->results()->where('type',DrawResult::TYPE['consolation'])->orderBy('position')->pluck('number')->toArray();
        $marquee = Marquee::first();
        $result = [
                'title' => 'WINNING RESULTS',
                'draw_no' => $draw?str_pad($draw->draw_no, 3, '0', STR_PAD_LEFT).'/'.$draw->year:'-|-',
                'date' => $draw?Carbon::parse($draw->expired_at)->format('d/m/Y (D)'):'-',
                'first' => $firstPrize?$firstPrize->number:'-',
                'second' => $secondPrize?$secondPrize->number:'-',
                'third' => $thirdPrize?$thirdPrize->number:'-',
                'special' => $specialPrizes,
                'consolation' => $consolationPrizes,
                'jackpot1' => $jackpotResult->jackpot1,
                'jackpot2' => $jackpotResult->jackpot2,
                'marquee' => str_replace('%jackpot2%', number_format($jackpotResult->jackpot2, 2), str_replace('%jackpot1%', number_format($jackpotResult->jackpot1, 2), $marquee?$marquee->message:''))
        ];
        return view('scoreboard', ['result' => $result]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
