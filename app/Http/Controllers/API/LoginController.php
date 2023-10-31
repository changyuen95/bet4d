<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // if ($user->email_verified_at === null) {
            //     return response(['message' => 'Please verify your email'], 422);
            // }

            if($user->status === User::STATUS['Active']){
                $user->access_token = $user->createToken('Mobile App', ['access:api'])->plainTextToken;
                return $user;
            }elseif($user->status === User::STATUS['Inactive']){
                return response(['message' => 'Account is Inactive'], 422);
            }elseif($user->status === User::STATUS['Disabled']){
                return response(['message' => 'Account is disabled'], 422);
            }else{
                return response(['message' => trans('auth.failed')], 422);
            }
        } else {
            return response(['message' => trans('auth.failed')], 422);
        }
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }
        Auth::shouldUse('admin-api');
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // if ($user->email_verified_at === null) {
            //     return response(['message' => 'Please verify your email'], 422);
            // }

            if($user->status === User::STATUS['Active']){
                $user->access_token = $user->createToken('Mobile App', ['access:api'])->plainTextToken;
                return $user;
            }elseif($user->status === User::STATUS['Inactive']){
                return response(['message' => 'Account is Inactive'], 422);
            }elseif($user->status === User::STATUS['Disabled']){
                return response(['message' => 'Account is disabled'], 422);
            }else{
                return response(['message' => trans('auth.failed')], 422);
            }
        } else {
            return response(['message' => trans('auth.failed')], 422);
        }
    }
    
    public function logout(Request $request)
    {
        $user = $request->user();

        // Revoke all of the user's tokens.
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json(['message' => trans('messages.logout_successfully')], 200);
    }
}