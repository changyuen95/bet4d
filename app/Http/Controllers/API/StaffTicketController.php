<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketNumber;
use App\Models\VerifyProfile;
use App\Models\WinnerList;
use Validator;
use Illuminate\Validation\Rule;
use Auth;
use Carbon\Carbon;
use File;
use Image;
use Illuminate\Support\Facades\Storage;
use Exception;
use DB;

class StaffTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $staff = Auth::user();
        // Define the custom validation rule
        // Validator::extend('valid_status', function ($attribute, $value, $parameters, $validator) {
        //     // Your status array
        //     $statusArray = Ticket::STATUS;

        //     // Check if each value in $value is in the $statusArray
        //     foreach ($value as $status) {
        //         if (!in_array($status, $statusArray)) {
        //             return false;
        //         }
        //     }

        //     return true;
        // });

        $validator = Validator::make($request->all(), [
            'game_id' => 'nullable|exists:games,id',
            // 'status' => ['array','valid_status'],
            'duration' => 'nullable|integer',
            'handled_by_me' => [Rule::in(array_values([true,false]))],
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $query = Ticket::where('outlet_id',$staff->outlet_id);
        if($request->game_id){
            $query->where('game_id',$request->game_id);

        }

        if($request->status != ''){
            $query->whereIn('status',$request->status);
        }

        if($request->handled_by_me){
            $query->where('action_by',$staff->id);
        }

        if($request->duration != null){
            $query->where('created_at','>=', Carbon::now()->subDays($request->duration)->format('Y:m:d 23:59:59'));
        }

        $tickets = $query->with(['ticketNumbers', 'draws','platform','game'])->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
        $completed_today = Ticket::where('action_by',$staff->id)
                            ->where('status',Ticket::STATUS['TICKET_COMPLETED'])
                            ->whereBetween('completed_at', [$todayStart, $todayEnd])->count();
        // $results = [
        //     'tickets' => $tickets,
        //     'completed_today' => $completed_today,
        // ];
        $completed = collect(['completed_today' => $completed_today]);

        $results = $completed->merge($tickets);


        return $results;
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
        // $ticket = Auth::user()->tickets()->find($id);
        $ticket = Ticket::find($id);

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

    public function pending_count()
    {
        $staff = Auth::user();

        $ticket_count =  $staff->outlet->tickets()->where(function($query1) use ($staff) {
            $query1->where('status',Ticket::STATUS['TICKET_REQUESTED'])
            ->orwhere(function($query2) use ($staff) {
            $query2->where('status', Ticket::STATUS['TICKET_IN_PROGRESS'])
                ->where('action_by',$staff->id);
            });

        })->count();

        $prize_count =WinnerList::where('action_by')->where('is_distribute',false)->count();
        $verify_profile = VerifyProfile::where('status','pending')->count();
        $count = [
            'ticket_request' => $ticket_count,
            'distribute_prize' => $prize_count,
            'profile_verification' => $verify_profile,
        ];

        return $count;
    }

    public function permutationImage(Request $request, $ticket_id, $ticket_number_id){
        $validator = Validator::make($request->all(),
        [
            'image' => 'required|image|mimes:jpg,png,jpeg',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $staff = Auth::user();
        $ticket = Ticket::find($ticket_id);
        if(!$ticket || $ticket->action_by != $staff->id){
            return response(['message' =>  trans('messages.invalid_ticket') ], 422);
        }

        $ticketNumber = $ticket->ticketNumbers()->find($ticket_number_id);
        if(!$ticketNumber){
            return response(['message' =>  trans('messages.invalid_ticket_number') ], 422);
        }

        if($ticketNumber->type == TicketNumber::TYPE['Straight']){
            return response(['message' =>  trans('messages.ticket_number_is_not_permutation') ], 422);
        }

        if($ticketNumber->permutation_image != null){
            return response(['message' =>  trans('messages.permutation_image_existed') ], 422);
        }
        DB::beginTransaction();
        try{
            if($request->hasFile('image')) {
                $allowedfileExtension=['jpg','png','jpeg'];

                $attachmentFile = $request->file('image');
                $attachmentfilename = $attachmentFile->getClientOriginalName();
                $attachmentextension = $attachmentFile->extension();

                $check =in_array($attachmentextension,$allowedfileExtension);

                if($check) {
                    File::makeDirectory(storage_path('app/public/permutation_image/ticketNumber/'.$ticketNumber->id.'/attachment/'), $mode = 0777, true, true);
                    $input['imagename'] = 'avatar_'.time().'.'.$attachmentFile->getClientOriginalExtension();
                    $destination_path = storage_path('app/public/permutation_image/ticketNumber/'.$ticketNumber->id.'/attachment/');
                    $img = Image::make($attachmentFile->path());
                    $img->save($destination_path.'/'.$input['imagename']);
                    $attachment_image_full_path = asset('storage/permutation_image/ticketNumber/'.$ticketNumber->id.'/attachment/'.$input['imagename']);
                    $oldImage = $ticketNumber->permutation_image;
                    $ticketNumber->update([
                        'permutation_image' => $attachment_image_full_path,
                    ]);
                    Storage::delete('public/'.str_replace(asset('storage/'),'',$oldImage));
                    DB::commit();
                    return response([
                        'message' =>  trans('messages.successfully_insert_permutation_image'),
                    ], 200);
                } else {
                    DB::rollback();
                    return response(['message' => trans('messages.only_png_jpg_jpeg_is_accepted')], 422);
                }

            }else{
                DB::rollback();
                return response(['message' => trans('messages.no_file_detected')], 422);
            }
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_insert_permutation_image') ], 422);
        }
    }

    public function removePermutationImage(Request $request, $ticket_id, $ticket_number_id){
        $staff = Auth::user();
        $ticket = $staff->tickets()->find($ticket_id);
        if(!$ticket){
            return response(['message' =>  trans('messages.invalid_ticket') ], 422);
        }

        $ticketNumber = $ticket->ticketNumbers()->find($ticket_number_id);
        if(!$ticketNumber){
            return response(['message' =>  trans('messages.invalid_ticket_number') ], 422);
        }

        if($ticketNumber->permutation_image == null){
            return response(['message' =>  trans('messages.permutation_image_not_found') ], 422);
        }

        DB::beginTransaction();
        try{
            $oldImage = $ticketNumber->permutation_image;
            $ticketNumber->update([
                'permutation_image' => null
            ]);
            Storage::delete('public/'.str_replace(asset('storage/'),'',$oldImage));

            DB::commit();
            return response(['message' =>  trans('messages.successfully_remove_permutation_image') ], 200);
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_remove_permutation_image') ], 422);
        }
    }

    public function ticketNumberImage ($request , $ticket_id , $ticket_number_id){
        $staff = Auth::user();
        $ticket = $staff->tickets()->find($ticket_id);
        if(!$ticket){
            return response(['message' =>  trans('messages.invalid_ticket') ], 422);
        }

        $ticketNumber = $ticket->ticketNumbers()->find($ticket_number_id);
        if(!$ticketNumber){
            return response(['message' =>  trans('messages.invalid_ticket_number') ], 422);
        }

        return $ticketNumber->permutation_image;


    }

}
