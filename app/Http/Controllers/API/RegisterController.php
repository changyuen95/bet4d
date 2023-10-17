<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\Badword;
use Exception;
use Illuminate\Validation\Rule;
use Validator;
use DB;
class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'username' => ['required', Rule::unique('users')->whereNull('deleted_at')],
                'phone_no' => ['required','numeric'],
                'email' => ['required','email',Rule::unique('users')->whereNull('deleted_at')],
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'min:6'
            ]);
    
            if ($validator->fails()) {
                return response(['message' => $validator->errors()->first()], 422);
            }
    
            $user = User::firstOrNew(['email' => $request->get('email')]);
    
            if ($user->email_verified_at) {
                return response(['message' => 'This email has been taken'], 422);
            }
    
            $user->fill($request->only('name', 'email', 'username', 'phone_no'));
            $user->password = bcrypt($request->get('password'));
            $user->save();
            
            $user->assignRole('normal_user');
            DB::commit();
            return response(['message' =>  trans('messages.register_successfully') ], 200);
    
            // $user->sendEmailVerificationNotification();
        }catch (Exception $e) {
            DB::rollback();
            dd($e);
            return response(['message' =>  trans('messages.register_failed') ], 422);
        }
    
    }
}