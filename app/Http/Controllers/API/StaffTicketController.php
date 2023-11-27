<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\WinnerList;
use Validator;

use Auth;
class StaffTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $staff = Auth::user();
        $validator = Validator::make($request->all(), [
            'game_id' => 'nullable|exists:game,id',
            'status' => 'array|in:'.Ticket::ALL_STATUS,
            // 'duration' => ''
            'handled_by_me' => 'in:'.[true,false],
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $query = Ticket::where('outlet_id',$staff->id);

        if($request->platfrom_id){
            $query->where('game_id',$request->game_id);

        }

        if($request->status != ''){
            $query->whereIn('ticket_status',$request->status);
        }

        if($request->handled_by_me){
            $query->where('action_by',$staff->id);
        }

        $tickets = $query->with(['ticketNumbers', 'draws','platform','game'])->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

        $completed_today = Ticket::where('action_by',$staff->id)
                            ->where('status',Ticket::STATUS['TICKET_COMPLETED'])->count();

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

    public function pending_count(string $id)
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
    }

}
