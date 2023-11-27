<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\WinnerList;
use Validator;
use Illuminate\Validation\Rule;
use Auth;
use Carbon\Carbon;

class StaffTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $staff = Auth::user();
        // Define the custom validation rule
        Validator::extend('valid_status', function ($attribute, $value, $parameters, $validator) {
            // Your status array
            $statusArray = Ticket::STATUS;

            // Check if each value in $value is in the $statusArray
            foreach ($value as $status) {
                if (!in_array($status, $statusArray)) {
                    return false;
                }
            }

            return true;
        });

        $validator = Validator::make($request->all(), [
            'game_id' => 'nullable|exists:game,id',
            'status' => ['array','valid_status'],
            // 'duration' => ''
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

        if($request->duration != ''){
            $query->where('created_at','>=', Carbon::now()->subDays($request->duration));
        }
        
        $tickets = $query->with(['ticketNumbers', 'draws','platform','game'])->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
        $completed_today = Ticket::where('action_by',$staff->id)
                            ->where('status',Ticket::STATUS['TICKET_COMPLETED'])
                            ->whereBetween('completed_at', [$todayStart, $todayEnd])->count();

        $results = [
            'tickets' => $tickets,
            'completed_today' => $completed_today,
        ];

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

        $count = [
            'ticket_request' => $ticket_count,
            'distribute_prize' => $prize_count,
        ];

        return $count;
    }

}
