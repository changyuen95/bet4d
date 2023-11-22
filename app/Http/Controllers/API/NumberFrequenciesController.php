<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NumberFrequencyResource;
use App\Models\NumberFrequencies;
use Illuminate\Http\Request;

class NumberFrequenciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = NumberFrequencies::query();
        $numberFrequencies = $query->with('details')->paginate($request->get('limit') ?? 10);
        return response($numberFrequencies, 200);
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
        $numberFrequency = NumberFrequencies::find($id);

        if(!$numberFrequency){
            return response(['message' => trans('messages.no_number_frequency_found')], 422);
        }
        
        return new NumberFrequencyResource($numberFrequency);
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
