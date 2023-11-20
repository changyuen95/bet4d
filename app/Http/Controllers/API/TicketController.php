<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\CreditTransaction;
use App\Models\Draw;
use App\Models\Game;
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
use App\Traits\NotificationTrait;
class TicketController extends Controller
{
    use NotificationTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->tickets();
        if($request->status != ''){
            $query->where('status',$request->status);
        }

        $tickets = $query->with(['ticketNumbers', 'draws','platform','game'])->paginate($request->get('limit') ?? 10);
        
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
                'ticket.*.ticket_number.digits' => trans('messages.the_ticket_number_must_be_4_digits_for_all_items'),
                'ticket.*.ticket_number.required' => trans('messages.the_ticket_number_is_required_for_all_items'),
                'ticket.*.ticket_number.numeric' => trans('messages.the_ticket_number_must_be_numeric_for_all_items'),
                'ticket.*.ticket_number.integer' => trans('messages.the_ticket_number_must_be_integer_for_all_items'),
                'ticket.*.small_amount.required' => trans('messages.the_small_amount_is_required_for_all_items'),
                'ticket.*.small_amount.numeric' => trans('messages.the_small_amount_must_be_numeric_for_all_items'),
                'ticket.*.small_amount.integer' => trans('messages.the_small_amount_must_be_integer_for_all_items'),
                'ticket.*.big_amount.required' => trans('messages.the_big_amount_is_required_for_all_items'),
                'ticket.*.big_amount.numeric' => trans('messages.the_big_amount_must_be_numeric_for_all_items'),
                'ticket.*.big_amount.integer' => trans('messages.the_big_amount_must_be_integer_for_all_items'),
                'ticket.*.type.required' => trans('messages.the_type_is_required_for_all_items'),
                'ticket.*.type.in' => trans('messages.invalid_type_value_for_some_items'),
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

            $platform = Platform::where('id',$request->platform_id)->where('status',Platform::STATUS['Active'])->first();
            if(!$platform){
                return response(['message' => trans('messages.invalid_platform')], 422);
            }

            $game = $platform->games()->where('id',$request->game_id)->where('status',Game::STATUS['Active'])->first();
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

            // if($userCredit->credit < floatVal($billAmount)){
            //     return response(['message' => trans('messages.insufficient_balance')], 422);
            // }

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

            // $ticketCreated->creditTransaction()->create([
            //     'user_id' => $user->id,
            //     'amount' => $billAmount,
            //     'type'  => CreditTransaction::TYPE['Decrease'],
            //     'before_amount' => $userCredit->credit,
            // ]);

            // $userCredit->credit = $userCredit->credit - $billAmount;
            // $userCredit->save();
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
        $ticket = Auth::user()->tickets()->find($id);

        if(!$ticket){
            return response(['message' => trans('messages.no_ticket_found')], 422);
        }
        
        return new TicketResource($ticket);
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

    public function updateTicketStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'user_id' => ['required'],
            'status' => ['required',Rule::in(array_values([Ticket::STATUS['TICKET_REQUESTED'],Ticket::STATUS['TICKET_CANCELLED']]))],
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $user = Auth::user();
        $ticket = $user->tickets()->find($id);
        if(!$ticket){
            return response(['message' =>  trans('messages.invalid_ticket') ], 422);
        }

        if($request->status == Ticket::STATUS['TICKET_CANCELLED']){
            if($ticket->status == Ticket::STATUS['TICKET_COMPLETED'] || $ticket->status == Ticket::STATUS['TICKET_IN_PROGRESS'] || $ticket->status == Ticket::STATUS['TICKET_REJECTED']){
                return response(['message' =>  trans('messages.unable_to_cancel_when_ticket_status_is_completed_or_in_progress') ], 422);
            }
        }

        if($request->status == Ticket::STATUS['TICKET_IMCOMPLETED'] || $request->status == Ticket::STATUS['TICKET_REQUESTED']){
            if($ticket->status == Ticket::STATUS['TICKET_COMPLETED'] || $ticket->status == Ticket::STATUS['TICKET_IN_PROGRESS'] || $ticket->status == Ticket::STATUS['TICKET_REJECTED']){
                return response(['message' =>  trans('messages.unable_change_to_imcomplete_or_requested_status_when_ticket_status_is_completed_or_in_progress') ], 422);
            }
        }

        if($ticket->status == Ticket::STATUS['TICKET_COMPLETED'] || $ticket->status == Ticket::STATUS['TICKET_REJECTED'] || $ticket->status == Ticket::STATUS['TICKET_CANCELLED']){
            return response(['message' =>  trans('messages.unable_to_change_status_when_ticket_is_completed') ], 422);
        }

        DB::beginTransaction();
        try{
            $ticketNumber = $ticket->ticketNumbers;
            $billAmount = 0;
            foreach($ticketNumber as $ticketNo){
                $billAmount += $ticketNo->small_amount;
                $billAmount += $ticketNo->big_amount;
            }

            $userCredit = $user->credit;
            if(!$userCredit){
                return response(['message' => trans('messages.no_user_credit_found')], 422);
            }

            if($ticket->status == Ticket::STATUS['TICKET_REQUESTED'] && $request->status == Ticket::STATUS['TICKET_CANCELLED']){
                $ticket->creditTransaction()->create([
                    'user_id' => $user->id,
                    'amount' => $billAmount,
                    'type'  => CreditTransaction::TYPE['Increase'],
                    'before_amount' => $userCredit->credit,
                    'outlet_id' => $ticket->outlet_id,
                ]);

                $userCredit->credit = $userCredit->credit + $billAmount;
                $userCredit->save();
            }

            if($ticket->status == Ticket::STATUS['TICKET_IMCOMPLETED'] && $request->status == Ticket::STATUS['TICKET_REQUESTED']){
                if($userCredit->credit < floatVal($billAmount)){
                    return response(['message' => trans('messages.insufficient_balance')], 422);
                }

                $ticket->creditTransaction()->create([
                    'user_id' => $user->id,
                    'amount' => $billAmount,
                    'type'  => CreditTransaction::TYPE['Decrease'],
                    'before_amount' => $userCredit->credit,
                    'outlet_id' => $ticket->outlet_id,
                ]);

                $userCredit->credit = $userCredit->credit - $billAmount;
                $userCredit->save();
            }

            $ticket->status = $request->status;
            $ticket->save();
            DB::commit();

            if($ticket->status == Ticket::STATUS['TICKET_REQUESTED']){
                $outlet = $ticket->outlet;
                if($outlet){
                    $staffs = $outlet->staffs;
                    $message = 'You have receive new ticket request.';
                    foreach($staffs as $staff){
                        $this->sendNotification($staff,$message,$ticket);
                    }
                }
            }

            return response([
                'message' =>  trans('messages.update_status_successfully'),
                'ticket' => new TicketResource($ticket)
            ], 200);
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_update_status') ], 422);
        }
    }

    public function staffUpdateTicketStatus(Request $request, $id){
        $validator = Validator::make($request->all(), [
            // 'user_id' => ['required'],
            'status' => ['required',Rule::in(array_values([Ticket::STATUS['TICKET_IN_PROGRESS'],Ticket::STATUS['TICKET_REJECTED'],Ticket::STATUS['TICKET_COMPLETED']]))],
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $staff = Auth::user();
        $staffOutlet = $staff->outlet;
        if(!$staffOutlet){
            return response(['message' =>  trans('messages.you_are_not_belongs_to_any_outlet') ], 422);
        }

        $ticket = $staffOutlet->tickets()->find($id);
        if(!$ticket){
            return response(['message' =>  trans('messages.invalid_ticket') ], 422);
        }

        $user = User::find($ticket->user_id);
        if(!$user){
            return response(['message' =>  trans('messages.no_ticket_owner_found') ], 422);
        }

        if($ticket->action_by != '' && $ticket->action_by != $staff->id){
            return response(['message' =>  trans('messages.this_ticket_had_been_accepted_by_another_staff') ], 422);
        }

        $ticket->action_by = $staff->id;
        $ticket->save();


        if($request->status == Ticket::STATUS['TICKET_IN_PROGRESS']){
            if($ticket->status != Ticket::STATUS['TICKET_REQUESTED']){
                $ticket->action_by = null;
                $ticket->save();
                return response(['message' =>  trans('messages.unable_to_accept_ticket_request_when_ticket_status_is_not_requested') ], 422);
            }
        }

        if($request->status == Ticket::STATUS['TICKET_REJECTED']){
            if($ticket->status != Ticket::STATUS['TICKET_REQUESTED']){
                $ticket->action_by = null;
                $ticket->save();
                return response(['message' =>  trans('messages.unable_to_reject_ticket_request_when_ticket_status_is_not_requested') ], 422);
            }

            $validator1 = Validator::make($request->all(), [
                // 'user_id' => ['required'],
                'reject_reason' => ['required'],
            ]);
    
            if ($validator1->fails()) {
                return response(['message' => $validator1->errors()->first()], 422);
            }
        }

        if($request->status == Ticket::STATUS['TICKET_COMPLETED']){
            if($ticket->action_by != $staff->id){
                return response(['message' =>  trans('messages.ticket_request_selected_is_not_belongs_to_you') ], 422);
            }

            if($ticket->status != Ticket::STATUS['TICKET_IN_PROGRESS']){
                return response(['message' =>  trans('messages.unable_to_complete_ticket_request_when_ticket_status_is_not_in_progress') ], 422);
            }
        }

        DB::beginTransaction();
        try{
            $ticketNumber = $ticket->ticketNumbers;
            $billAmount = 0;
            foreach($ticketNumber as $ticketNo){
                $billAmount += $ticketNo->small_amount;
                $billAmount += $ticketNo->big_amount;
            }

            $userCredit = $user->credit;
            if(!$userCredit){
                $ticket->action_by = null;
                $ticket->save();
                return response(['message' => trans('messages.no_user_credit_found')], 422);
            }
            if($request->status != Ticket::STATUS['TICKET_COMPLETED']){
                if($request->status == Ticket::STATUS['TICKET_REJECTED']){
                    $ticket->reject_reason = $request->reject_reason;
                    $ticket->creditTransaction()->create([
                        'user_id' => $user->id,
                        'amount' => $billAmount,
                        'type'  => CreditTransaction::TYPE['Increase'],
                        'before_amount' => $userCredit->credit,
                        'outlet_id' => $ticket->outlet_id,
                    ]);
                    $userCredit->credit = $userCredit->credit + $billAmount;
                    $userCredit->save();
                }
                
            }else{
                $barCodeCount = $ticket->barcode()->count();
                if($barCodeCount <= 0){
                    return response(['message' => trans('messages.at_least_1_barcode_is_scanned_in_order_to_complete_ticket_request')], 422);
                }
            }

            $ticket->status = $request->status;
            $ticket->save();
            DB::commit();

            return response([
                'message' =>  trans('messages.update_status_successfully'),
                'ticket' => new TicketResource($ticket)
            ], 200);
        }catch (Exception $e) {
            DB::rollback();
            $ticket->action_by = null;
            $ticket->save();
            return response(['message' =>  trans('messages.failed_to_update_status') ], 422);
        }
    }

    public function staffScanBarcode(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'barcode' => ['required'],
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $staff = Auth::user();
        $ticket = Ticket::find($id);
        if($ticket->action_by != $staff->id){
            return response(['message' =>  trans('messages.invalid_ticket') ], 422);
        }
        $checkDuplicateBarcode = $ticket->barcode()->where('barcode',$request->barcode)->count();
        if($checkDuplicateBarcode > 0){
            return response(['message' =>  trans('messages.this_ticket_had_scanned_before_please_try_another_ticket') ], 422);
        }
        
        DB::beginTransaction();
        try{

            $ticket->barcode()->create([
                'barcode' => $request->barcode
            ]);

            DB::commit();
            return response(['message' =>  trans('messages.successfully_scanned_barcode') ], 200);
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_scan_barcode') ], 422);
        }
    }
}
