<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\WinnerList;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        $distributed_winner_list = WinnerList::where('is_distribute', true)
                                    ->whereHas('ticketNumber.ticket.outlet', function($q) use($superadmin){
                                        $q->where('outlets.id', $superadmin->outlet_id);
                                    })
                                    ->with('drawResult', 'ticketNumber', 'winner');

        if($duration != ''){
            $distributed_winner_list->where('created_at','>=', Carbon::now()->subDays($request->duration));
        }

        switch ($type_of_distribution){
            case 'all_types':
                $winner_list = $distributed_winner_list->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);
                break;

            case 'verified':
                $winner_list = $distributed_winner_list->where('is_verified', true)->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);
                break;

            case 'distributed':
                $winner_list = $distributed_winner_list->where('is_verified', false)->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);
                break;

            default:
                $winner_list = $distributed_winner_list;
        }

        return response($winner_list, 200);
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

        return response(['message' => trans('admin.transaction_detail_not_found')], 422);

    }
}
