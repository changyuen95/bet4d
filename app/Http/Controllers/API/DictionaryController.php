<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dictionary;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Dictionary::query();

        if($request->keywords != ''){
            $searchTerm = $request->keywords;
            $query->where(function($query1) use ($searchTerm) {
                $query1->where('keyword_en', 'like', '%' . $searchTerm . '%')
                      ->orWhere('keyword_ch', 'like', '%' . $searchTerm . '%')
                      ->orWhere('number', 'like', '%' . $searchTerm . '%');
            });
        }

        if(app()->getLocale() == 'en'){
            $dictionaries = $query->select('id', 'keyword_en AS keyword', 'number')->paginate($request->get('limit') ?? 10);
        }elseif(app()->getLocale() == 'cn'){
            $dictionaries = $query->select('id', 'keyword_ch AS keyword', 'number')->paginate($request->get('limit') ?? 10);
        }else{
            $dictionaries = $query->select('id', 'keyword_en AS keyword', 'number')->paginate($request->get('limit') ?? 10);
        }

        return response($dictionaries, 200);
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
