<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\WinningHistoryResource;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Validator;
use Illuminate\Validation\Rule;

class WinningHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'duration' => ['nullable','numeric'],
            'games' => 'nullable|array|exists:games,id',
            'prize_distribution' => [Rule::in(array_values(['pending','completed']))]
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $user = Auth::user();
        if(!$user){
            return response(['message' => trans('messages.no_user_found')], 422);
        }

        $query = $user->tickets()->has('ticketNumbers.win')->with('ticketNumbers.win');
        if($request->platform_id != ''){
            $query->where('platform_id', $request->platform_id);
        }

        if($request->games != ''){
            $query->whereIn('game_id', $request->games);
        }

        if($request->duration != ''){
            $query->where('created_at','>=', Carbon::now()->subDays($request->duration));
        }

        if($request->prize_distribution == 'pending'){
            $query->whereHas('ticketNumbers.win', function ($query) {
                $query->where('is_distribute', false);
            });
        }elseif($request->prize_distribution == 'completed'){
            $query->whereHas('ticketNumbers.win', function ($query) {
                $query->where('is_distribute', true);
            });
        }
        $tickets = $query->paginate($request->get('limit') ?? 10);
        
        return response($tickets, 200);
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
        $user = Auth::user();
        if(!$user){
            return response(['message' => trans('messages.no_user_found')], 422);
        }

        $winningHistory = $user->winningHistory()->find($id);
        if(!$winningHistory){
            return response(['message' => trans('messages.no_winning_history_found')], 422);
        }

        return new WinningHistoryResource($winningHistory);

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
