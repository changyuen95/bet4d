<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DrawResource;
use App\Models\Draw;
use App\Models\DrawResult;
use App\Models\DrawResultStaging;
use App\Models\Jackpot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DrawResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Draw::query();

        $drawResults = $query->with('results')->where('is_open_result',true)->orderBy('expired_at','DESC')->paginate($request->get('limit') ?? 10);
        return $drawResults;
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
        $draw = Draw::find($id);
        if(!$draw){
            return response(['message' => trans('messages.no_draw_found')], 422);
        }

        if(!$draw->is_open_result){
            return response(['message' => trans('messages.the_draw_result_havent_release')], 422);
        }
        return new DrawResource($draw);
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

    public function stcMasterResult(Request $request)
    {
        \Log::info('Fetching STC Master results for draw: '.$request->drawNo);
        $results = DB::connection('stcmaster')
                ->table('tmpresultmaster')
                ->where('DrwKey', $request->drawNo)
                ->get();

        $jackpot = DB::connection('stcmaster')
                ->table('jackpot')
                ->first();

        $drawNo = str_replace(" ","",$request->drawNo);
        $drawNoArray = explode('/', $drawNo );
        $validDraw = isset($drawNoArray[1]);
        $draw = '';
        $firstPrize = '';
        $secondPrize = '';
        $thirdPrize = '';
        $specialPrizes = '';
        $consolationPrizes = '';
        if($validDraw){
            $draw = Draw::where('draw_no',ltrim((int)$drawNoArray[0], '0'))->where('year',$drawNoArray[1])->first();
            if($draw){
                foreach($results as $stcresult){
                    $position = 0;
                    if($stcresult->DrwPrz == 'F' || $stcresult->DrwPrz == 'S' || $stcresult->DrwPrz == 'T' ){
                        $position = 1;
                    }elseif($stcresult->DrwPrz == 'C'){
                        $position = (int)$stcresult->ScrPos - 13;
                    }elseif($stcresult->DrwPrz == 'Z'){
                        $position = (int)$stcresult->ScrPos;
                    }

                    $draw->results()->updateOrCreate([
                        'type' => DrawResult::STC_MASTER_TYPE[$stcresult->DrwPrz],
                        'position' => $position,
                    ],[
                        'number' => $stcresult->DrwNo
                    ]);

                    if($stcresult->DrwPrz == 'F' || $stcresult->DrwPrz == 'S' || $stcresult->DrwPrz == 'T'){
                        $draw->results()->updateOrCreate([
                            'type' => DrawResult::TYPE['special'],
                            'position' => $stcresult->ScrPos,
                        ],[
                            'number' => '-'
                        ]);
                    }
                }

                $firstPrize = $draw->results()->where('type',DrawResult::TYPE['1st'])->first();
                $secondPrize = $draw->results()->where('type',DrawResult::TYPE['2nd'])->first();
                $thirdPrize = $draw->results()->where('type',DrawResult::TYPE['3rd'])->first();
                $specialPrizes = $draw->results()->where('type',DrawResult::TYPE['special'])->orderBy('position')->pluck('number')->toArray();
                $consolationPrizes = $draw->results()->where('type',DrawResult::TYPE['consolation'])->orderBy('position')->pluck('number')->toArray();
            }
        }

        $jackpotResult = Jackpot::first();
        if($jackpot){
            $jackpotResult->jackpot1 = $jackpot->jackpot1;
            $jackpotResult->jackpot2 = $jackpot->jackpot2;
            $jackpotResult->save();
        }



        
        // dd(Carbon::parse($draw->expired_at)->format('d/m/Y (D)'));
        $payload = [
            'stc4d' => [
                'title' => 'WINNING RESULTS',
                'draw_no' => $draw?str_pad($draw->draw_no, 3, '0', STR_PAD_LEFT).'/'.$draw->year:'-|-',
                'date' => $draw?Carbon::parse($draw->expired_at)->format('d/m/Y (D)'):'-',
                'first' => $firstPrize?$firstPrize->number:'-',
                'second' => $secondPrize?$secondPrize->number:'-',
                'third' => $thirdPrize?$thirdPrize->number:'-',
                'special' => $specialPrizes,
                'consolation' => $consolationPrizes,
                'jackpot1' => $jackpotResult->jackpot1,
                'jackpot2' => $jackpotResult->jackpot2
            ]
        ];

        event(new \App\Events\ScoreboardUpdated($payload));

        return response()->json(['status' => 'broadcasted', 'payload' => $payload]);
    }

    public function stcMasterResultStaging(Request $request)
    {
        \Log::info('Fetching STC Master results for draw: '.$request->drawNo);
        $results = DB::connection('stcmaster')
                ->table('tmpresultmaster')
                ->where('DrwKey', $request->drawNo)
                ->get();

        $jackpot = DB::connection('stcmaster')
                ->table('jackpot')
                ->first();

        $drawNo = str_replace(" ","",$request->drawNo);
        $drawNoArray = explode('/', $drawNo );
        $validDraw = isset($drawNoArray[1]);
        $draw = '';
        $firstPrize = '';
        $secondPrize = '';
        $thirdPrize = '';
        $specialPrizes = '';
        $consolationPrizes = '';
        if($validDraw){
            $draw = Draw::where('draw_no',ltrim((int)$drawNoArray[0], '0'))->where('year',$drawNoArray[1])->first();
            if($draw){
                foreach($results as $stcresult){
                    $position = 0;
                    if($stcresult->DrwPrz == 'F' || $stcresult->DrwPrz == 'S' || $stcresult->DrwPrz == 'T' ){
                        $position = 1;
                    }elseif($stcresult->DrwPrz == 'C'){
                        $position = (int)$stcresult->ScrPos - 13;
                    }elseif($stcresult->DrwPrz == 'Z'){
                        $position = (int)$stcresult->ScrPos;
                    }

                    $draw->resultsStaging()->updateOrCreate([
                        'type' => DrawResultStaging::STC_MASTER_TYPE[$stcresult->DrwPrz],
                        'position' => $position,
                    ],[
                        'number' => $stcresult->DrwNo
                    ]);

                    if($stcresult->DrwPrz == 'F' || $stcresult->DrwPrz == 'S' || $stcresult->DrwPrz == 'T'){
                        $draw->resultsStaging()->updateOrCreate([
                            'type' => DrawResultStaging::TYPE['special'],
                            'position' => $stcresult->ScrPos,
                        ],[
                            'number' => '-'
                        ]);
                    }
                }

                $firstPrize = $draw->resultsStaging()->where('type',DrawResultStaging::TYPE['1st'])->first();
                $secondPrize = $draw->resultsStaging()->where('type',DrawResultStaging::TYPE['2nd'])->first();
                $thirdPrize = $draw->resultsStaging()->where('type',DrawResultStaging::TYPE['3rd'])->first();
                $specialPrizes = $draw->resultsStaging()->where('type',DrawResultStaging::TYPE['special'])->orderBy('position')->pluck('number')->toArray();
                $consolationPrizes = $draw->resultsStaging()->where('type',DrawResultStaging::TYPE['consolation'])->orderBy('position')->pluck('number')->toArray();
            }
        }

        $jackpotResult = Jackpot::first();
        if($jackpot){
            $jackpotResult->jackpot1 = $jackpot->jackpot1;
            $jackpotResult->jackpot2 = $jackpot->jackpot2;
            $jackpotResult->save();
        }



        
        // dd(Carbon::parse($draw->expired_at)->format('d/m/Y (D)'));
        $payload = [
            'stc4d' => [
                'title' => 'WINNING RESULTS',
                'draw_no' => $draw?str_pad($draw->draw_no, 3, '0', STR_PAD_LEFT).'/'.$draw->year:'-|-',
                'date' => $draw?Carbon::parse($draw->expired_at)->format('d/m/Y (D)'):'-',
                'first' => $firstPrize?$firstPrize->number:'-',
                'second' => $secondPrize?$secondPrize->number:'-',
                'third' => $thirdPrize?$thirdPrize->number:'-',
                'special' => $specialPrizes,
                'consolation' => $consolationPrizes,
                'jackpot1' => $jackpotResult->jackpot1,
                'jackpot2' => $jackpotResult->jackpot2
            ]
        ];

        event(new \App\Events\ScoreboardUpdated($payload));

        return response()->json(['status' => 'broadcasted', 'payload' => $payload]);
    }
}
