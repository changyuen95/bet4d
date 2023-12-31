<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DrawResource;
use App\Models\Draw;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    public function getCurrentDraw(){
        $draw = Draw::getCurrentDraw();
        if(!$draw){
            return response(['message' => trans('messages.no_draw_found')], 422);
        }

        return new DrawResource($draw);
    }

    public function getCountDownTime(){
        $nextDraw = Draw::getCurrentDraw();
        if(!$nextDraw){
            return response(['message' => trans('messages.no_draw_found')], 422);
        }
        $now = Carbon::now();

        $drawDate = Carbon::parse($nextDraw->expired_at);

        $drawDate->addHour();
        $difference = $now->diff($drawDate);

        $days = $difference->days;
        $hours = $difference->h;
        $minutes = $difference->i;
        $seconds = $difference->s;


        return (object)[
            'datetime' => Carbon::parse($nextDraw->expired_at)->format('Y-m-d H:i:s')
        ];
    }
}
