<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use App\Models\DrawResult;
use App\Models\Jackpot;
use App\Models\Platform;
use App\Models\PotentialWinningPriceList;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * List all past draw results (paginated)
     */
    public function index(Request $request)
    {
        $platform = Platform::where('status', 'active')->first();

        $draws = Draw::with('results')
            ->when($platform, fn($q) => $q->where('platform_id', $platform->id))
            ->where('is_open_result', true)
            ->orderBy('expired_at', 'DESC')
            ->paginate(10);

        $jackpot = Jackpot::first();
        $prizeList = PotentialWinningPriceList::where('type', 'straight')->first();

        return view('website.results.index', compact('draws', 'jackpot', 'prizeList'));
    }

    /**
     * Show a single draw result
     */
    public function show(Draw $draw)
    {
        if (!$draw->is_open_result) {
            abort(404);
        }

        $draw->load('results');
        $results = $this->parseDrawResults($draw);
        $jackpot = Jackpot::first();
        $prizeList = PotentialWinningPriceList::where('type', 'straight')->first();

        return view('website.results.show', compact('draw', 'results', 'jackpot', 'prizeList'));
    }

    /**
     * Parse draw results into structured array
     */
    private function parseDrawResults(Draw $draw): array
    {
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
