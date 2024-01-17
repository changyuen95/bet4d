<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\VerifyPrizeResource;
use App\Http\Resources\WinnerListDisplayResource;
use App\Models\WinnerList;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerifyPrizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pending_verify_prize = WinnerList::where('is_distribute', 1)->where('is_verified', 0)->with('drawResult','ticketNumber.ticket','winner');

        if($request->duration != ''){
            $pending_verify_prize->where('created_at','>=', Carbon::now()->subDays($request->duration));
        }

        $pending_verify_prize_list = $pending_verify_prize->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

        return response($pending_verify_prize_list, 200);
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
        $pending_verify_prize = WinnerList::where('is_distribute', 1)->where('is_verified', 0)->where('id',$id)->with('drawResult','ticketNumber.ticket','winner')->first();

        if(!$pending_verify_prize)
        {
            return response(['message' => trans('messages.no_pending_verify_prize_found')], 422);
        }

        return new VerifyPrizeResource($pending_verify_prize);
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

    public function verifyDistributePrize($id){
        $pending_verify_prize = WinnerList::where('is_distribute', 1)->where('is_verified', 0)->where('id',$id)->with('drawResult','ticketNumber.ticket','winner')->first();

        if(!$pending_verify_prize)
        {
            return response(['message' => trans('messages.no_pending_verify_prize_found')], 422);
        }

        $pending_verify_prize->update([
            'is_verified' => true
        ]);

        return response($pending_verify_prize);
    }
}
