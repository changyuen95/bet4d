<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\CreditTransaction;
use App\Models\Draw;
use App\Models\Game;
use App\Models\Platform;
use App\Models\Barcode as Barcode_table;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketNumber;
use App\Models\User;
use App\Models\Tax;
use App\Models\TicketReceipt;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use Exception;
use Auth;
use App\Traits\NotificationTrait;
use Faker\Core\Barcode;
use File;
use Image;

class TicketController extends Controller
{
    use NotificationTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'games' => 'nullable|array|exists:games,id',
            'status' => ['nullable','array'],
            'duration' => 'nullable|integer',
            'win' => [Rule::in(array_values([true,false]))],
            'prize_distribution' => [Rule::in(array_values(['pending','completed']))]
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $query = Auth::user()->tickets();

        if($request->platform_id){
            $query->where('platform_id',$request->platform_id);
        }

        if($request->games){
            $query->whereIn('game_id',$request->games);

        }

        if($request->status != ''){
            $query->whereIn('status',$request->status);
        }

        if($request->duration != null){
            $query->where('created_at','>=', Carbon::now()->subDays($request->duration)->format('Y:m:d 23:59:59'));
        }

        if($request->win === true){
            $query->has('ticketNumbers.win');

            if($request->prize_distribution == 'pending'){
                $query->whereHas('ticketNumbers.win', function ($query) {
                    $query->where('is_distribute', false);
                });
            }elseif($request->prize_distribution == 'completed'){
                $query->whereHas('ticketNumbers.win', function ($query) {
                    $query->where('is_distribute', true);
                });
            }
        }elseif($request->win === false){
            $query->doesntHave('ticketNumbers.win');
        }


        $tickets = $query->with(['ticketNumbers.win', 'draws','platform','game'])->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

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
                'draw_id' => ['required'],
                'ticket.*.ticket_number' => ['required', 'numeric', 'digits:4'],
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

            $drawData = Draw::find($request->draw_id);
            if(!$drawData){
                return response(['message' => trans('messages.no_draw_found')], 422);
            }

            if(Draw::checkIsExpired($drawData)){
                return response(['message' => trans('messages.draw_expired')], 422);
            }
            // $drawData = Draw::getDrawData($platform->id);
            $billAmount = 0;
            $tax = Tax::first();
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

            $sub_main_id = null;
            $all_sub_tickets =[];
            foreach($request->ticket as $ticket){
                if($ticket['type'] == TicketNumber::TYPE['Box']){
                    $ticketNumberArray = $this->getPermutationsProbabilities($ticket['ticket_number']);
                    foreach($ticketNumberArray as $ticketGenerated){
                        $permutation_type = $this->calculatePermutations($ticket['ticket_number']);
                        $sub_ticket =  $ticketCreated->ticketNumbers()->create([
                            'number' => $ticketGenerated,
                            'small_amount' => $ticket['small_amount'],
                            'actual_small_amount' => $ticket['small_amount'],
                            'big_amount' => $ticket['big_amount'],
                            'actual_big_amount' => $ticket['big_amount'],
                            'refund_amount' => 0,
                            'tax_amount' => (($ticket['big_amount'] + $ticket['small_amount']) * $tax->percentage / 100),
                            'actual_tax_amount' => (($ticket['big_amount'] + $ticket['small_amount']) * $tax->percentage / 100),
                            'tax_id' => Tax::first()->id,
                            'type' => $ticket['type'],
                            'permutation_type' => $permutation_type,
                            'is_main' => ($ticketGenerated == $ticket['ticket_number'] ? 1 : 0),
                        ]);

                        if($ticketGenerated == $ticket['ticket_number']){
                            $sub_main_id = $sub_ticket->id;
                        }else{
                            $all_sub_tickets[] = $sub_ticket->id;
                        }
                    }
                }else{
                    $permutation_type = null;
                    if($ticket['type'] == TicketNumber::TYPE['e-box']){
                        $permutation_type = $this->calculatePermutations($ticket['ticket_number']);
                    }
                    $ticketCreated->ticketNumbers()->create([
                        'number' => $ticket['ticket_number'],
                        'small_amount' => $ticket['small_amount'],
                        'actual_small_amount' => $ticket['small_amount'],
                        'big_amount' => $ticket['big_amount'],
                        'actual_big_amount' => $ticket['big_amount'],
                        'refund_amount' => 0,
                        'tax_amount' => (($ticket['big_amount'] + $ticket['small_amount']) * $tax->percentage / 100),
                        'actual_tax_amount' => (($ticket['big_amount'] + $ticket['small_amount']) * $tax->percentage / 100),
                        'tax_id' => Tax::first()->id,
                        'type' => $ticket['type'],
                        'permutation_type' => $permutation_type,
                        'is_main' => 1,
                    ]);

                }

                if($sub_main_id){
                    $sub_tickets = TicketNumber::whereIn('id',$all_sub_tickets);
                    $sub_tickets->update(['main_ticket_id' => $sub_main_id]);
                    $sub_main_id = null;
                    $all_sub_tickets = [];
                }

            }


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
                    $staffs = $outlet->staffs()->whereHas('roles', function($q) {
                        return $q->where('name', Role::OPERATOR);
                    })->get();
                    $notificationData = [];
                    $notificationData['title'] = 'New ticket request';
                    $notificationData['message'] = 'You have receive new ticket request.';
                    $notificationData['deepLink'] = 'fortknox-admin://tickets/'.$ticket->id;
                    $appId = env('ONESIGNAL_STAFF_APP_ID');
                    $apiKey = env('ONESIGNAL_STAFF_REST_API_KEY');
                    foreach($staffs as $staff){
                        $this->sendNotification($appId, $apiKey, $staff,$notificationData,$ticket);
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

    public function staffTicketListing(Request $request){
        $staff = Auth::user();
        $staffOutlet = $staff->outlet;
        if(!$staffOutlet){
            return response(['message' =>  trans('messages.you_are_not_belongs_to_any_outlet') ], 422);
        }

        $query = $staffOutlet->tickets();
        // if($request->status != ''){
        //     $query->where('status',$request->status);
        // }
        $tickets =  $query->where(function($query1) use ($staff) {
            $query1->where('status',Ticket::STATUS['TICKET_REQUESTED'])
            ->orwhere(function($query2) use ($staff) {
            $query2->where('status', Ticket::STATUS['TICKET_IN_PROGRESS'])
                ->where('action_by',$staff->id);
            });

        })
        ->with(['ticketNumbers', 'draws','platform','game'])
        ->orderBy('created_at','DESC')
        ->paginate($request->get('limit') ?? 10);
        return response($tickets, 200);
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
                // $ticket->action_by = null;
                // $ticket->save();
                return response(['message' =>  trans('messages.unable_to_accept_ticket_request_when_ticket_status_is_not_requested') ], 422);
            }
        }

        if($request->status == Ticket::STATUS['TICKET_REJECTED']){
            if($ticket->status != Ticket::STATUS['TICKET_IN_PROGRESS']){
                // $ticket->action_by = null;
                // $ticket->save();
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
                // $ticket->action_by = null;
                // $ticket->save();
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

                // foreach($ticketNumber as $ticketNum){
                //     if($ticketNum->type == TicketNumber::TYPE['Permutation'] && $ticketNum->permutation_image == null){
                //         return response(['message' => trans('messages.permutation_image_cannot_be_empty')], 422);
                //     }
                // }
                $ticket->completed_at = Carbon::now();

                if($ticket->total_refund > 0){
                    $ticket->creditTransaction()->create([
                        'user_id' => $user->id,
                        'amount' => $billAmount,
                        'type'  => CreditTransaction::TYPE['Increase'],
                        'before_amount' => $userCredit->credit,
                        'outlet_id' => $ticket->outlet_id,
                    ]);
                    $userCredit->credit = $userCredit->credit + $ticket->total_refund;
                    $userCredit->save();
                }

            }

            $ticket->status = $request->status;
            $ticket->save();

            $ticketUser = $ticket->user;
            if($ticketUser){
                if($request->status == Ticket::STATUS['TICKET_IN_PROGRESS']){
                    $notificationData = [];
                    $notificationData['title'] = 'Ticket Accepted';
                    $notificationData['message'] = 'Your ticket is in progress';
                    $notificationData['deepLink'] = 'fortknox://me/tickets/'.$ticket->id;
                    $appId = env('ONESIGNAL_APP_ID');
                    $apiKey = env('ONESIGNAL_REST_API_KEY');
                    $this->sendNotification($appId, $apiKey, $ticketUser,$notificationData,$ticket);
                }elseif($request->status == Ticket::STATUS['TICKET_REJECTED']){
                    $notificationData = [];
                    $notificationData['title'] = 'Ticket Rejected';
                    $notificationData['message'] = 'Ticket is rejected';
                    $notificationData['deepLink'] = 'fortknox://me/tickets/'.$ticket->id;
                    $appId = env('ONESIGNAL_APP_ID');
                    $apiKey = env('ONESIGNAL_REST_API_KEY');

                    $this->sendNotification($appId, $apiKey, $ticketUser,$notificationData,$ticket);
                }elseif($request->status == Ticket::STATUS['TICKET_COMPLETED']){
                    $notificationData = [];
                    $notificationData['title'] = 'Ticket process is completed';
                    $notificationData['message'] = 'Ticketing process is completed , please check your ticket now';
                    $notificationData['deepLink'] = 'fortknox://me/tickets/'.$ticket->id;
                    $appId = env('ONESIGNAL_APP_ID');
                    $apiKey = env('ONESIGNAL_REST_API_KEY');

                    $this->sendNotification($appId, $apiKey, $ticketUser,$notificationData,$ticket);
                }
            }
            DB::commit();

            return response([
                'message' =>  trans('messages.update_status_successfully'),
                'ticket' => new TicketResource($ticket)
            ], 200);
        }catch (Exception $e) {
            DB::rollback();
            // $ticket->action_by = null;
            // $ticket->save();
            return response(['message' =>  trans('messages.failed_to_update_status') ], 422);
        }
    }

    public function staffScanBarcode(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpg,png,jpeg',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $staff = Auth::user();
        $ticket = Ticket::find($id);
        if(!$ticket || $ticket->action_by != $staff->id){
            return response(['message' =>  trans('messages.invalid_ticket') ], 422);
        }
        // $checkDuplicateBarcode = $ticket->receipts()->where('path',$request->image)->count();
        // if($checkDuplicateBarcode > 0){
        //     return response(['message' =>  trans('messages.this_ticket_had_scanned_before_please_try_another_ticket') ], 422);
        // }

        DB::beginTransaction();

        try{
            if($request->hasFile('image')) {
                $allowedfileExtension=['jpg','png','jpeg'];

                $attachmentFile = $request->file('image');
                $attachmentfilename = $attachmentFile->getClientOriginalName();
                $attachmentextension = $attachmentFile->extension();

                $check =in_array($attachmentextension,$allowedfileExtension);

                if($check) {
                    File::makeDirectory(storage_path('app/public/ticket_receipts/'.$ticket->id.'/attachment/'), $mode = 0777, true, true);
                    $input['imagename'] = 'avatar_'.time().'.'.$attachmentFile->getClientOriginalExtension();
                    $destination_path = storage_path('app/public/ticket_receipts/'.$ticket->id.'/attachment/');
                    $img = Image::make($attachmentFile->path());
                    $img->save($destination_path.'/'.$input['imagename']);
                    $attachment_image_full_path = asset('storage/ticket_receipts/'.$ticket->id.'/attachment/'.$input['imagename']);
                    // $oldImage = $ticketNumber->permutation_image;
                    $ticket_receipt = new TicketReceipt();
                    $ticket_receipt->path = $attachment_image_full_path;
                    $ticket_receipt->ticket_id = $ticket->id;
                    $ticket_receipt->save();
                    // Storage::delete('public/'.str_replace(asset('storage/'),'',$oldImage));
                    DB::commit();
                    return response([
                        'message' =>  trans('messages.successfully_insert_rceipt'),
                    ], 200);
                } else {
                    DB::rollback();
                    return response(['message' =>  trans('messages.failed_to_scan_barcode') ], 422);
                }

            }else{
                DB::rollback();
                return response(['message' =>  trans('messages.failed_to_scan_barcode') ], 422);
            }
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_scan_barcode') ], 422);
        }
    }

    public function removeBarcode(Request $request, $id, $barcode_id){
        $staff = Auth::user();
        $ticket = $staff->tickets()->find($id);
        if(!$ticket){
            return response(['message' =>  trans('messages.invalid_ticket') ], 422);
        }
        $barcode = $ticket->receipts()->find($barcode_id);
        if(!$barcode){
            return response(['message' =>  trans('messages.invalid_barcode') ], 422);
        }

        DB::beginTransaction();
        try{

            $barcode->delete();

            DB::commit();
            return response(['message' =>  trans('messages.successfully_remove_barcode') ], 200);
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_remove_barcode') ], 422);
        }
    }

    public function barcodeListing(Request $request, $id){
        $staff = Auth::user();
        $ticket = $staff->tickets()->find($id);
        if(!$ticket){
            return response(['message' =>  trans('messages.invalid_ticket') ], 422);
        }

        $barcodeQuery = $ticket->receipts();

        $barcode = $barcodeQuery->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

        return response($barcode, 200);
    }

    public function getAllNumbers(Request $request , $id){

        $ticket_numbers = Ticket::where('id',$id)->with('ticketNumbers')->first();

        return response($ticket_numbers, 200);

    }

    public function modifyTicketAmount(Request $request , $id){
        //update ticket detail
        $validator = Validator::make($request->all(), [
            'ticket_number' => 'array|exists:ticket_numbers,id',
            'actual_big_number' => ['array'],
            'actual_small_number' => ['array'],
            ]);

        // $validator->setCustomMessages($customMessages);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }
            $total_refund = 0;
            $ticket = Ticket::find($id);
            $tax = Tax::first();

        foreach($request->ticket_number as $key => $ticket_number){
            $ticket_number = TicketNumber::find($ticket_number);
            $ticket_number->actual_big_amount = $request->actual_big_number[$key];
            $ticket_number->actual_small_amount = $request->actual_small_number[$key];
            $actual_tax = (($request->actual_big_number[$key] + $request->actual_small_number[$key]) * $tax->percentage / 100);

            $ticket_number->actual_tax_amount =  $actual_tax;
            $ticket_number->refund_amount = ($ticket_number->big_amount + $ticket_number->small_amount + $ticket_number->tax_amount ) - ($request->actual_big_number[$key] + $request->actual_small_number[$key] + $actual_tax ) ;
            $ticket_number->save();


            $total_refund = $total_refund + $ticket_number->refund_amount;
        }






        return response($ticket, 200);

        $ticket = Ticket::find($id);


    }

    function calculatePermutations($number) {
        // Count the frequency of each character in the string
        $freq = count_chars($number, 1);

        // Calculate the total length of the string
        $total = strlen($number);

        // Start with total permutations (factorial of length)
        $permutations = $this->factorial($total);

        // Divide by the factorial of each duplicate character frequency
        foreach ($freq as $count) {
            if ($count > 1) {
                $permutations /= $this->factorial($count);
            }
        }

        return $permutations;
    }

    // Helper function to calculate factorial
    function factorial($n) {
        if ($n == 0 || $n == 1) {
            return 1;
        }
        $result = 1;
        for ($i = 2; $i <= $n; $i++) {
            $result *= $i;
        }
        return $result;
    }


    public function getPermutationsProbabilities($number)
    {
        // Convert the string number into an array of digits
        $digits = str_split($number);

        // Generate all permutations
        $permutations = $this->generatePermutations($digits);

        // Convert each permutation from array to string
        $permutationStrings = array_map(function ($permutation) {
            return implode('', $permutation);
        }, $permutations);

        // Remove duplicate permutations (if any)
        $uniquePermutations = array_unique($permutationStrings);

        // Return the result as a regular array (unique permutations)
        return $uniquePermutations;
    }

    // Function to generate all permutations of an array
    private function generatePermutations(array $array)
    {
        $result = [];

        // If there is only one element, return it as the only permutation
        if (count($array) == 1) {
            return [$array];
        }

        // Loop through each element in the array
        foreach ($array as $key => $value) {
            // Remove the current element from the array
            $remaining = $array;
            unset($remaining[$key]);
            // Recursively get permutations of the remaining elements
            $permutations = $this->generatePermutations(array_values($remaining));

            // Merge the current element with all the permutations of the remaining elements
            foreach ($permutations as $permutation) {
                $result[] = array_merge([$value], $permutation);
            }
        }

        return $result;
    }
}
