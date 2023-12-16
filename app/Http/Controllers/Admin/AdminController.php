<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewAdminAddedEmail;
use App\Models\Admin;
use App\Models\Outlet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();

        $admins = Admin::where('outlet_id', $user->outlet_id)->orderBy('created_at', 'desc')->get();

        return view('admin.admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $outlets = Outlet::all();
        return view('admin.admin.create2', compact('outlets'));
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
            'phone_number' => 'nullable|numeric',
            'password' => 'required|same:password_confirmation|min:8',
            'role' => 'required|in:super_admin,operator',
            'outlet' => 'required',
            'user_avatar' => 'nullable|mimes:jpeg,png,jpg|max:10240'

        ]);

        if($validator->fails()){
            Session::flash('fail', 'Fail to add admin!');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try{

            DB::beginTransaction();

            $new_admin = new Admin();
            $new_admin->name = $request->name;
            $new_admin->username = $request->name;
            $new_admin->email = $request->email;
            $new_admin->outlet_id = $request->outlet;
            $new_admin->password = bcrypt($request->password);
            $new_admin->role = $request->role;
            $new_admin->phone_e164 = $request->phone_number;

            if($request->hasFile('user_avatar')){
                $profile_img = $request->file('user_avatar');
                $original_file_name = $profile_img->getClientOriginalName();
                $original_without_extension = pathinfo($original_file_name, PATHINFO_FILENAME);
                $random_hex = rand(11,99);
                $extension = strtolower($profile_img->getClientOriginalExtension());
                $random_name = "admin_profile_image_" .$random_hex. date("Ymdhis");
                $new_file_name_with_path = "storage/admin_profile_img/" . $random_name . '.' . $extension;
                $destinationPath = storage_path('web/public/admin_profile_img');

                $name = "/storage/admin_profile_img/" . $random_name . '.' . $extension;  // Name path to store in DB

                $move = $profile_img->move($destinationPath, $new_file_name_with_path);

                $new_admin->profile_image = $name;

            }

            $new_admin->save();
            $new_admin->assignRole($request->role);
            $new_admin->save();

            DB::commit();


            if($request->email)
            {
                /***** Send email notifcation to newly added admin *****/
                $sender = config('main.admin_email');
                $receiver = $request->email;
                $info = [
                    'title' => 'New Admin Added',
                    'subtitle' => 'Welcome to the team of ' .$new_admin->outlet->name. ' admin!',
                    'content' => 'Dear ' .$new_admin->name. ', an admin account has been created for you. You may access to the admin panel through the below link.',
                    'url' => route('admin.login'),
                    'name' => $new_admin->name,
                    'email' => $new_admin->email,
                    'password' => $request->password,
                ];

                $title = $info['title'];

                try{

                    $this->sendAdminLoginEmail($sender, $receiver, $info, $title);


                } catch (\Exception $ex){

                    Log::info('Error: ' .$ex->getMessage());
                }
            }

            Session::flash('success', 'New admin added!');
            return redirect()->route('admin.admins.show', $new_admin->id);

        } catch (\Exception $ex){

            DB::rollback();
            Log::info("error : " .$ex->getMessage());
            return redirect()->back()->withErrors('Something went wrong. Please try again later')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $admin = Admin::findOrFail($id);

        return view('admin.admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //

        return view('admin.admin.edit', compact('admin'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Admin $admin, Request $request)
    {
        //

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => "required|unique:admins,email," . $admin->id . ",id",
            'phone_number' => 'nullable|numeric',
            'user_avatar' => 'nullable|mimes:jpeg,png,jpg|max:10240'
        ]);

        if($validator->fails()){
            Session::flash('fail', 'Failed to update the information!');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $admin->update([
            'name' => $request->name,
            'username' => $request->name,
            'email' => $request->email,
            'phone_e164' => $request->phone_number,
        ]);

        if($request->hasFile('user_avatar')){

            $profile_img = $request->file('user_avatar');
            $original_file_name = $profile_img->getClientOriginalName();
            $original_without_extension = pathinfo($original_file_name, PATHINFO_FILENAME);
            $random_hex = rand(11,99);
            $extension = strtolower($profile_img->getClientOriginalExtension());
            $random_name = "admin_profile_image_" .$random_hex. date("Ymdhis");
            $new_file_name_with_path = "storage/admin_profile_img/" . $random_name . '.' . $extension;
            $destinationPath = storage_path('web/public/admin_profile_img');

            $name = "/storage/admin_profile_img/" . $random_name . '.' . $extension;  // Name path to store in DB

            $move = $profile_img->move($destinationPath, $new_file_name_with_path);

            if($admin->profile_image){

                $previous_img = $admin->profile_image;

                $filtering_path = str_replace('storage/', '', "web/public".$previous_img);
                $delete_path = storage_path($filtering_path);
                if (file_exists($delete_path)) {
                    unlink($delete_path);
                }
            }

            $admin->profile_image = $name;
        }

        $admin->save();

        Session::flash('success', 'Admin successfully updated!');
        return redirect()->route('admin.admins.show', $admin->id);

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $admin = Admin::find($id);
        if(!$admin)
        {
            return response()->json(['success' => false, 200]);
        }

        $admin->deleted_at = Carbon::now();
        $admin->save();

        return response()->json(['success' => true, 200]);
    }
    

    public function resendEmail($id){

        $admin = Admin::find($id);
        if(!$admin)
        {
            Session::flash('fail', 'Something went wrong');
            return response()->json(['success' => false, 200]);
        }

        $new_password = Admin::generatePassword();
        $admin->password = Hash::make($new_password);
        $admin->save();

        if($admin->email)
        {
            /***** Send email notifcation to newly added admin *****/
            $sender = config('main.admin_email');
            $receiver = $admin->email;
            $info = [
                'title' => 'New Admin Added',
                'subtitle' => 'Welcome to the team of ' .$admin->outlet->name. ' admin!',
                'content' => 'Dear ' .$admin->name. ', an admin account has been created for you. You may access to the admin panel through the below link.',
                'url' => route('admin.login'),
                'name' => $admin->name,
                'email' => $admin->email,
                'password' => $new_password,
            ];

            $title = $info['title'];

            try{

                $this->sendAdminLoginEmail($sender, $receiver, $info, $title);
                Log::info('Admin login crendential email successfully sent!');

            } catch (\Exception $ex){

                Log::info('Error: ' .$ex->getMessage());
            }
        }
    }


    public function sendAdminLoginEmail($sender, $receiver, $info, $subject){

        Mail::send(new NewAdminAddedEmail($sender, $receiver, $info, $subject));
    }


}
