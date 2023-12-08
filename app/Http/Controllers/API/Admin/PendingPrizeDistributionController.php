<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PendingPrizeDistributionResource;
use App\Models\Admin;
use App\Models\Role;
use App\Models\WinnerList;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PendingPrizeDistributionController extends Controller
{
    //
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id' => 'nullable|exists:games,id',
            'handled_by_me' => [Rule::in(array_values([true,false]))],
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $superadmin = $request->user();

        if(!$superadmin){
            return response(['message' => trans('messages.no_user_found')], 422);
        }

        $game_id = $request->game_id;

        if($superadmin->hasRole(Role::SUPER_ADMIN)){

            /****** return all the pending prize to be distributed ******/
            // $prizes_to_be_distributed = WinnerList::where('is_distribute', 0)
            //                             ->join('ticket_numbers', 'winner_lists.ticket_number_id', '=', 'ticket_numbers.id')
            //                             ->join('tickets', 'ticket_numbers.ticket_id', '=', 'tickets.id')
            //                             ->leftjoin('draw_results', 'winner_lists.draw_result_id', 'draw_result.id')
            //                             ->where('tickets.outlet_id', $superadmin->outlet_id)
            //                             ->where('tickets.status', 'completed')
            //                             ->select('winner_lists.*', 'draw_result.type', 'draw_result.position');
            $prizes_to_be_distributed = WinnerList::where('is_distribute', 0)->whereHas('ticketNumber', function($q) use($game_id){
                                            $q->whereHas('ticket', function($q) use($game_id){
                                                $q->when($game_id != '', function ($query) use($game_id){
                                                    return $query->where('game_id', $game_id);
                                                });
                                            });
                                        })->where('outlet_id',$superadmin->outlet_id)->with('drawResult','ticketNumber','winner');

            if($request->handled_by_me){
                $prizes_to_be_distributed->where('action_by',$superadmin->id);
            }

            if($request->duration != ''){
                $prizes_to_be_distributed->where('created_at','>=', Carbon::now()->subDays($request->duration));
            }

            $pending_prizes_distribution_list = $prizes_to_be_distributed->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

            return response($pending_prizes_distribution_list, 200);

        }

        return response(['message' => trans('admin.staff_not_found')], 422);
    }

    public function show(string $id)
    {
        $winner = Auth::user()->outlet->winnerList()->find($id);

        if(!$winner)
        {
            return response(['message' => trans('messages.no_winner_prize_found')], 422);
        }
    
        return new PendingPrizeDistributionResource($winner);
    }

    public function getCount(Request $request)
    {

        $superadmin = $request->user();

        if(!$superadmin){
            return response(['message' => trans('messages.no_user_found')], 422);
        }

        if($superadmin->hasRole(Role::SUPER_ADMIN)){

            $prizes_to_be_distributed_count = WinnerList::where('is_distribute', 0)
                                                ->join('ticket_numbers', 'winner_lists.ticket_number_id', '=', 'ticket_numbers.id')
                                                ->join('tickets', 'ticket_numbers.ticket_id', '=', 'tickets.id')
                                                ->where('tickets.outlet_id', $superadmin->outlet_id)
                                                ->where('tickets.status', 'completed')
                                                ->count();

            return $prizes_to_be_distributed_count;
    
        }
    }
}
