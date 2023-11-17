<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DrawCalendar;
use Illuminate\Http\Request;
use Validator;
class DrawCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|date_format:Y',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $query = DrawCalendar::query();
        $query->whereMonth('date', $request->month)->whereYear('date', $request->year);

        $calendar = $query->get();

        return response($calendar, 200);
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
}
