<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Outlet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();

        $admins = Admin::where('outlet_id', $user->outlet_id)->get();

        return view('admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $outlets = Outlet::all();
        return view('admin.create2', compact('outlets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(),[

            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'phone_number' => 'nullable|number',
            'password' => 'required|same:password_confirmation|min:8',
            'role' => 'required|in:superadmin,operator',
            'outlet' => 'required',
        ]);

        if($validator->fails()){
            Session::flash('success', 'New admin added!');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // try{

            DB::beginTransaction();

            $new_admin = new Admin();
            $new_admin->name = $request->name;
            $new_admin->username = $request->name;
            $new_admin->email = $request->email;
            $new_admin->outlet_id = $request->outlet;
            $new_admin->password = bcrypt($request->password);
            $new_admin->role = $request->role;
            $new_admin->phone_e164 = $request->phone_number;
            $new_admin->save();

            DB::commit();


            Session::flash('success', 'New admin added!');
            return redirect()->route('admin.admins.show', $new_admin->id);

        // } catch (\Exception $ex){

        //     DB::rollback();
        //     Log::info("error : " .$ex->getMessage());
        //     return redirect()->back()->withErrors('Something went wrong. Please try again later')->withInput();
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $admin = Admin::findOrFail($id);

        return view('admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //

        return view('admin.edit', compact('admin'));
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
