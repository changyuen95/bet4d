<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use App\Models\DrawResult;
use App\Models\Jackpot;
use App\Models\Marquee;
use App\Models\Platform;
use App\Models\PotentialWinningPriceList;
use Carbon\Carbon;

class LiveDrawController extends Controller
{
    public function index()
    {
        $platform = Platform::where('status', 'active')->first();

        $draw = Draw::when($platform, fn($q) => $q->where('platform_id', $platform->id))
            ->where('expired_at', '<', Carbon::now())
            ->orderBy('expired_at', 'DESC')
            ->first();

        $jackpot = Jackpot::first();
        $prizeList = PotentialWinningPriceList::where('type', 'straight')->first();

        $result = [
            'title' => 'WINNING RESULTS',
            'draw_no' => $draw ? str_pad($draw->draw_no, 3, '0', STR_PAD_LEFT) . '/' . $draw->year : '-',
            'date' => $draw ? Carbon::parse($draw->expired_at)->format('d/m/Y (D)') : '-',
            'first' => '-',
            'second' => '-',
            'third' => '-',
            'special' => [],
            'consolation' => [],
            'jackpot1' => $jackpot->jackpot1 ?? 0,
            'jackpot2' => $jackpot->jackpot2 ?? 0,
        ];

        if ($draw) {
            $firstPrize = $draw->results()->where('type', DrawResult::TYPE['1st'])->first();
            $secondPrize = $draw->results()->where('type', DrawResult::TYPE['2nd'])->first();
            $thirdPrize = $draw->results()->where('type', DrawResult::TYPE['3rd'])->first();
            $specialPrizes = $draw->results()->where('type', DrawResult::TYPE['special'])->orderBy('position')->pluck('number')->toArray();
            $consolationPrizes = $draw->results()->where('type', DrawResult::TYPE['consolation'])->orderBy('position')->pluck('number')->toArray();

            $result['first'] = $firstPrize ? $firstPrize->number : '-';
            $result['second'] = $secondPrize ? $secondPrize->number : '-';
            $result['third'] = $thirdPrize ? $thirdPrize->number : '-';
            $result['special'] = $specialPrizes;
            $result['consolation'] = $consolationPrizes;
        }

        return view('website.live-draw', compact('result', 'prizeList'));
    }

    /**
     * API endpoint for live draw polling (JSON)
     */
    public function latestResult()
    {
        $platform = Platform::where('status', 'active')->first();

        $draw = Draw::with('results')
            ->when($platform, fn($q) => $q->where('platform_id', $platform->id))
            ->where('expired_at', '<', Carbon::now())
            ->orderBy('expired_at', 'DESC')
            ->first();

        $jackpot = Jackpot::first();

        if (!$draw) {
            return response()->json(['data' => null]);
        }

        $firstPrize = $draw->results->where('type', '1st')->first();
        $secondPrize = $draw->results->where('type', '2nd')->first();
        $thirdPrize = $draw->results->where('type', '3rd')->first();
        $specialPrizes = $draw->results->where('type', 'special')->sortBy('position')->pluck('number')->values()->toArray();
        $consolationPrizes = $draw->results->where('type', 'consolation')->sortBy('position')->pluck('number')->values()->toArray();

        return response()->json([
            'data' => [
                [
                    'full_draw_no' => $draw->full_draw_no,
                    'draw_no' => str_pad($draw->draw_no, 3, '0', STR_PAD_LEFT) . '/' . $draw->year,
                    'date' => Carbon::parse($draw->expired_at)->format('d/m/Y (D)'),
                    'first' => $firstPrize ? $firstPrize->number : '-',
                    'second' => $secondPrize ? $secondPrize->number : '-',
                    'third' => $thirdPrize ? $thirdPrize->number : '-',
                    'special' => $specialPrizes,
                    'consolation' => $consolationPrizes,
                    'jackpot1' => $jackpot->jackpot1 ?? 0,
                    'jackpot2' => $jackpot->jackpot2 ?? 0,
                ]
            ]
        ]);
    }
}
