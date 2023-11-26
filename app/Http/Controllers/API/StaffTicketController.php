<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Auth;
class StaffTicketController extends Controller
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

        $tickets = $query->with(['ticketNumbers', 'draws','platform','game'])->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

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

}
