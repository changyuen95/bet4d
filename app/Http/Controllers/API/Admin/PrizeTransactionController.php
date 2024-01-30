<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\WinnerList;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\VerifyPrizeResource;
use App\Http\Resources\WinnerListDisplayResource;


class PrizeTransactionController extends Controller
{
    //
    public function index(Request $request, $id)
    {
        $superadmin = $request->user();

        if(!$superadmin){
            return response(['message' => trans('messages.no_user_found')], 422);
        }

        $type_of_distribution = $request->transaction_type ?? 'all_types';
        $duration = $request->duration;

        $distributed_winner_list = WinnerList::where('is_distribute', true)->where('action_by', $id)
                                    ->whereHas('ticketNumber.ticket.outlet', function($q) use($superadmin){
                                        $q->where('outlets.id', $superadmin->outlet_id);
                                    })
                                    ->with('drawResult', 'ticketNumber', 'winner');


        if($duration != ''){
            $distributed_winner_list->where('created_at','>=', Carbon::now()->subDays($request->duration));
        }

        switch ($type_of_distribution){
            case 'all_types':
                $winner_list = $distributed_winner_list->orderBy('created_at','DESC');
                break;

            case 'verified':
                $winner_list = $distributed_winner_list->where('is_verified', true)->orderBy('created_at','DESC');
                break;

            case 'distributed':
                $winner_list = $distributed_winner_list->where('is_verified', false)->orderBy('created_at','DESC');
                break;

            default:
                $winner_list = $distributed_winner_list;
        }

        $to_verify = collect(['to_verify' => $winner_list->sum('amount')]);

        $results = $to_verify->merge($winner_list->paginate($request->get('limit') ?? 10));

        return $results;
    }

    public function show(string $admin_id, string $id)
    {

        $winner= WinnerList::where('action_by', $admin_id)
                            ->where('id', $id)
                            ->with('drawResult', 'ticketNumber', 'winner')
                            ->first();
                            // $winner = Auth::user()->outlet->winnerList()->find($id);

        if ($winner) {
            return $winner;
        }

        return response(['message' => trans('messages.transaction_detail_not_found')], 422);

    }


    public function pendingList(Request $request,$admin_id )
    {
        $pending_verify_prize = WinnerList::where('is_distribute', 1)
                            ->where('is_verified', 0)
                            ->where('action_by',$admin_id)
                            ->with('drawResult','ticketNumber.ticket','winner');

        if($request->duration != ''){
            $pending_verify_prize->where('created_at','>=', Carbon::now()->subDays($request->duration));
        }

        $pending_verify_prize_list = $pending_verify_prize->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

        return response($pending_verify_prize_list, 200);

    }

    public function pendingDetail(Request $request,$admin_id  , $id)
    {

        $pending_verify_prize = WinnerList::where('is_distribute', 1)
                        ->where('action_by',$admin_id)
                        ->where('is_verified', 0)
                        ->where('id',$id)
                        ->with('drawResult','ticketNumber.ticket','winner')
                        ->first();

        if(!$pending_verify_prize)
        {
            return response(['message' => trans('messages.no_pending_verify_prize_found')], 422);
        }

        return new VerifyPrizeResource($pending_verify_prize);

    }

    public function verifyPendingprize(Request $request,$admin_id  , $id)
    {

        $pending_verify_prize = WinnerList::where('is_distribute', 1)
                        ->where('is_verified', 0)
                        ->where('id',$id)
                        ->where('action_by',$admin_id)
                        ->with('drawResult','ticketNumber.ticket','winner')
                        ->first();

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
