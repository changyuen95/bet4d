<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use App\Models\DrawResult;
use App\Models\Jackpot;
use App\Models\Marquee;
use App\Models\Outlet;
use App\Models\Platform;
use App\Models\PotentialWinningPriceList;
use App\Models\WinnerListDisplay;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Get active platform (STC)
        $platform = Platform::where('status', 'active')->first();

        // Latest draw with results (scoped to active platform)
        $latestDraw = Draw::with('results')
            ->when($platform, fn($q) => $q->where('platform_id', $platform->id))
            ->where('is_open_result', true)
            ->orderBy('expired_at', 'DESC')
            ->first();

        // Parse results into structured format
        $results = $this->parseDrawResults($latestDraw);

        // Jackpot amounts
        $jackpot = Jackpot::first();

        // Next draw countdown (scoped to platform)
        $nextDraw = Draw::when($platform, fn($q) => $q->where('platform_id', $platform->id))
            ->where('expired_at', '>', Carbon::now())
            ->orderBy('expired_at', 'ASC')
            ->first();

        // Outlet count (scoped to platform)
        $outletCount = Outlet::when($platform, fn($q) => $q->where('platform_id', $platform->id))->count();

        // Prize structure from DB (straight type for display)
        $prizeList = PotentialWinningPriceList::where('type', 'straight')->first();

        // Marquee announcement
        $marquee = Marquee::first();
        if ($marquee && $jackpot) {
            $marquee->message = str_replace(
                ['%jackpot1%', '%jackpot2%'],
                [number_format($jackpot->jackpot1 ?? 0, 2), number_format($jackpot->jackpot2 ?? 0, 2)],
                $marquee->message
            );
        }

        // Recent winners for display
        $recentWinners = WinnerListDisplay::with(['draw', 'outlet'])
            ->orderBy('created_at', 'DESC')
            ->take(10)
            ->get();

        return view('website.home', compact(
            'latestDraw',
            'results',
            'jackpot',
            'nextDraw',
            'outletCount',
            'prizeList',
            'marquee',
            'recentWinners',
            'platform'
        ));
    }

    /**
     * Parse draw results into structured array
     */
    private function parseDrawResults(?Draw $draw): array
    {
        if (!$draw) {
            return [
                'first_prize' => '-',
                'second_prize' => '-',
                'third_prize' => '-',
                'special' => [],
                'consolation' => [],
            ];
        }

        $data = [
            'first_prize' => '-',
            'second_prize' => '-',
            'third_prize' => '-',
            'special' => [],
            'consolation' => [],
        ];

        foreach ($draw->results as $result) {
            if ($result->number == '' || $result->number == '-') continue;

            switch ($result->type) {
                case DrawResult::TYPE['1st']:
                    $data['first_prize'] = $result->number;
                    break;
                case DrawResult::TYPE['2nd']:
                    $data['second_prize'] = $result->number;
                    break;
                case DrawResult::TYPE['3rd']:
                    $data['third_prize'] = $result->number;
                    break;
                case DrawResult::TYPE['special']:
                    $data['special'][$result->position] = $result->number;
                    break;
                case DrawResult::TYPE['consolation']:
                    $data['consolation'][$result->position] = $result->number;
                    break;
            }
        }

        ksort($data['special']);
        ksort($data['consolation']);
        $data['special'] = array_values($data['special']);
        $data['consolation'] = array_values($data['consolation']);

        return $data;
    }
}
