<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PendingPrizeDistributionResource;
use App\Models\Admin;
use App\Models\Role;
use App\Models\WinnerList;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PendingPrizeDistributionController extends Controller
{
    use NotificationTrait;
    //
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id' => 'nullable|exists:games,id',
            // 'handled_by_me' => [Rule::in(array_values([true,false]))],
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
                                        })->with('drawResult','ticketNumber','winner');

            // if($request->handled_by_me){
            //     $prizes_to_be_distributed->where('action_by',$superadmin->id);
            // }

            if($request->duration != ''){
                $prizes_to_be_distributed->where('created_at','>=', Carbon::now()->subDays($request->duration));
            }

            $pending_prizes_distribution_list = $prizes_to_be_distributed->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

            $to_distribute = collect(['to_distribute' => $prizes_to_be_distributed->count()]);

            $results = $to_distribute->merge($pending_prizes_distribution_list);

            return $results;

        }

        return response(['message' => trans('admin.staff_not_found')], 422);
    }

    public function show(string $id, Request $request)
    {
        // $winner = $request->user()->outlet->winnerList()->find($id);
        $winner = WinnerList::where('is_distribute', 0)->where('id',$id)->with('drawResult','ticketNumber','winner')->first();
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
                                                ->where('tickets.status', 'completed')
                                                ->count();

            return $prizes_to_be_distributed_count;

        }
    }

    public function resendNotification(Request $request, $id)
    {
        $winner = WinnerList::find($id);
        if(!$winner){
            return response(['message' => trans('messages.no_winner_prize_found')], 422);
        }
        $ticketNumber = $winner->ticketNumber;
        $ticket = optional($ticketNumber)->ticket;
        if(!$ticket){
            return response(['message' => trans('messages.no_ticket_found')], 422);
        }
        $ticketUser = $ticket->user;

        $outletStaffs = optional(optional($ticket->outlet)->staffs())->whereHas('roles', function($q) {
            return $q->where('name', Role::OPERATOR);
        })->get();

        foreach($outletStaffs as $outletStaff){
            $notificationData = [];
            if($ticketUser){
                $notificationData['title'] = $ticketUser->name.' had win the prize';
                $notificationData['message'] = $ticketUser->name.' had win the prize, please distribute the prize to customer';
            }else{
                $notificationData['title'] = 'Someone had win the prize';
                $notificationData['message'] = 'Someone had win the prize, please distribute the prize to customer';
            }
            $notificationData['deepLink'] = 'fortknox-admin://distribute-prizes/'.$winner->id;
            $appId = config('app.ONESIGNAL_STAFF_APP_ID');
            $apiKey = config('app.ONESIGNAL_STAFF_REST_API_KEY');
            $this->sendNotification($appId, $apiKey, $outletStaff,$notificationData,$winner);
        }

        return response(['message' => trans('messages.resend_notification_successfully')], 200);

    }
}
