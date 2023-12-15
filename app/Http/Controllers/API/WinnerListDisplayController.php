<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\WinnerListDisplayResource;
use App\Models\Draw;
use Illuminate\Http\Request;

class WinnerListDisplayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Draw::whereHas('results')->with('winnerListDisplay');

        $winningListDisplay = $query->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);
        
        return response($winningListDisplay, 200);
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
        $draw = Draw::whereHas('results')->where('id',$id)->first();
        if(!$draw){
            return response(['message' => trans('messages.no_draw_found')], 422);
        }

        return new WinnerListDisplayResource($draw);
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
