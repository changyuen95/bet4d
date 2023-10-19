<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\CreditTransaction;
use App\Models\Draw;
use App\Models\Platform;
use App\Models\Ticket;
use App\Models\TicketNumber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use Exception;
use Auth;
class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->tickets();
        if($request->status != ''){
            $query->where('status',$request->status);
        }

        $tickets = $query->get();
        return response([
            'success' => true,
            'outlet' =>  TicketResource::collection($tickets)
        ], 200);
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
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                // 'user_id' => ['required'],
                'platform_id' => ['required'],
                'outlet_id' => ['required'],
                'game_id' => ['required'],
                'ticket.*.ticket_number' => ['required', 'numeric', 'integer','digits:4'],
                'ticket.*.small_amount' => ['required', 'numeric', 'integer'],
                'ticket.*.big_amount' => ['required', 'numeric', 'integer'],
                'ticket.*.type' => ['required',Rule::in(array_values(TicketNumber::TYPE))],
            ]);

            $customMessages = [
                'ticket.*.ticket_number.digits' => trans('the_ticket_number_must_be_4_digits_for_all_items'),
                'ticket.*.ticket_number.required' => trans('the_ticket_number_is_required_for_all_items'),
                'ticket.*.ticket_number.numeric' => trans('the_ticket_number_must_be_numeric_for_all_items'),
                'ticket.*.ticket_number.integer' => trans('the_ticket_number_must_be_integer_for_all_items'),
                'ticket.*.small_amount.required' => trans('the_small_amount_is_required_for_all_items'),
                'ticket.*.small_amount.numeric' => trans('the_small_amount_must_be_numeric_for_all_items'),
                'ticket.*.small_amount.integer' => trans('the_small_amount_must_be_integer_for_all_items'),
                'ticket.*.big_amount.required' => trans('the_big_amount_is_required_for_all_items'),
                'ticket.*.big_amount.numeric' => trans('the_big_amount_must_be_numeric_for_all_items'),
                'ticket.*.big_amount.integer' => trans('the_big_amount_must_be_integer_for_all_items'),
                'ticket.*.type.required' => trans('the_type_is_required_for_all_items'),
                'ticket.*.type.in' => trans('invalid_type_value_for_some_items'),
            ];
            
            $validator->setCustomMessages($customMessages);
            if ($validator->fails()) {
                return response(['message' => $validator->errors()->first()], 422);
            }
    
            $user = User::find(Auth::user()->id);
            if(!$user){
                return response(['message' => trans('messages.no_user_found')], 422);
            }

            $userCredit = $user->credit;
            if(!$userCredit){
                return response(['message' => trans('messages.no_user_credit_found')], 422);
            }

            $platform = Platform::find($request->platform_id);
            if(!$platform){
                return response(['message' => trans('messages.invalid_platform')], 422);
            }

            $game = $platform->games()->find($request->game_id);
            if(!$game){
                return response(['message' => trans('messages.invalid_game')], 422);
            }

            $outlet = $platform->outlets()->find($request->outlet_id);
            if(!$outlet){
                return response(['message' => trans('messages.invalid_outlet')], 422);
            }

            $drawData = Draw::getDrawData($platform->id);
            $billAmount = 0;
            foreach($request->ticket as $ticket){
                $billAmount += $ticket['small_amount'];
                $billAmount += $ticket['big_amount'];
            }

            if($userCredit->credit < floatVal($billAmount)){
                return response(['message' => trans('messages.insufficient_balance')], 422);
            }

            $ticketCreated = $user->tickets()->create([
                'outlet_id' => $outlet->id,
                'platform_id' => $platform->id,
                'game_id' => $game->id,
                'draw_id' => $drawData->id,
                'status' => Ticket::STATUS['TICKET_IMCOMPLETED'],
            ]);

            foreach($request->ticket as $ticket){
                $ticketCreated->ticketNumbers()->create([
                    'number' => $ticket['ticket_number'],
                    'small_amount' => $ticket['small_amount'],
                    'big_amount' => $ticket['big_amount'],
                    'type' => $ticket['type'],
                ]);
            }

            $ticketCreated->creditTransaction()->create([
                'user_id' => $user->id,
                'amount' => $billAmount,
                'type'  => CreditTransaction::TYPE['Decrease'],
                'before_amount' => $userCredit->credit,
            ]);

            $userCredit->credit = $userCredit->credit - $billAmount;
            $userCredit->save();
            DB::commit();

            return response([
                'message' =>  trans('messages.submit_ticket_request_successfully'),
                'tickets' =>  new TicketResource($ticketCreated)
            ], 200);

        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_submit_ticket_request') ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    public function updateTicketStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'user_id' => ['required'],
            'ticket_id' => ['required','exists:tickets,id'],
            'status' => ['required',Rule::in(array_values(Ticket::STATUS))],
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $ticket = Auth::user()->tickets()->find($request->ticket_id);
        if(!$ticket){
            return response(['message' =>  trans('messages.invalid_ticket') ], 422);
        }

        if($request->status == Ticket::STATUS['TICKET_CANCELLED']){
            if($ticket->status == Ticket::STATUS['TICKET_COMPLETED'] || $ticket->status == Ticket::STATUS['TICKET_IN_PROGRESS']){
                return response(['message' =>  trans('messages.unable_to_cancel_when_ticket_status_is_completed_or_in_progress') ], 422);
            }
        }

        if($request->status == Ticket::STATUS['TICKET_IMCOMPLETED'] || $request->status == Ticket::STATUS['TICKET_REQUESTED']){
            if($ticket->status == Ticket::STATUS['TICKET_COMPLETED'] || $ticket->status == Ticket::STATUS['TICKET_IN_PROGRESS']){
                return response(['message' =>  trans('messages.unable_change_to_imcomplete_or_requested_status_when_ticket_status_is_completed_or_in_progress') ], 422);
            }
        }

        if($ticket->status == Ticket::STATUS['TICKET_COMPLETED']){
            return response(['message' =>  trans('messages.unable_to_change_status_when_ticket_is_completed') ], 422);
        }

        $ticket->status = $request->status;
        $ticket->save();

        return response([
            'message' =>  trans('messages.update_status_successfully'),
            'ticket' => new TicketResource($ticket)
        ], 200);

    }
}
