<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OutletResource;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Validator;
class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $platform_id)
    {
        // $validator = Validator::make($request->all(), [
        //     'platform_id' => ['required'],
        // ]);

        // if ($validator->fails()) {
        //     return response(['message' => $validator->errors()->first()], 422);
        // }
        
        $query = Outlet::query();
        $query->where('platform_id',$platform_id);
        if($request->search != ''){
            $searchTerm = $request->search;
            $query->where(function($query1) use ($searchTerm) {
                $query1->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('address', 'like', '%' . $searchTerm . '%');
            });
        }
       
        $outlets = $query->paginate($request->get('limit') ?? 10);
        return response($outlets, 200);
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
    public function show($platform_id,string $id)
    {
        $outlet = Outlet::where('platform_id',$platform_id)->where('id',$id)->first();

        if(!$outlet){
            return response(['message' => trans('messages.no_outlet_found')], 422);
        }
        
        return new OutletResource($outlet);
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
