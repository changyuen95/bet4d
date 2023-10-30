<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tac;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Str;
use Validator;
use Carbon\Carbon;
use Exception;
use DB;
use App\Notifications\TacNotification;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_e164' => 'required|phone',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try{
            $user = User::where('phone_e164', $request->get('phone_e164'))->first();

            if (!$user) {
                return response(['message' => trans('messages.phone_doesnt_exist')], 422);
            }

            $validTac = $user->tacs()->where('expired_at','>=' ,Carbon::now())->where('verified_at',null)->count();

            if ($validTac > 0 ) {
                return response(['message' => trans('messages.please_try_again_in_2_minutes')], 422);
            }

            // $tacNo = $this->sendTac($request->phone_e164);
            $tacNo = mt_rand(100000, 999999);
            if(env('APP_ENV') == 'production'){
                $user->notify(new TacNotification($tacNo));
            }
            $token = Str::random(20);
            $currentDatetime = Carbon::now();
            $expired_at = $currentDatetime->addMinutes(2);
            $user->tacs()->create([
                'phone_e164' => $request->phone_e164,
                'verify_code' => $tacNo,
                'token' => $token,
                'ref' => Tac::REFERENCE['Forgot_Password'],
                'expired_at' => $expired_at,
            ]);
            
            DB::commit();
            $response = [
                'message' =>  trans('messages.send_tac_successfully'),
                'token' => $token
            ];
            if(env('APP_ENV') != 'production'){
                $response['tac'] = $tacNo;
            }

            return response($response, 200);
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.send_tac_failed') ], 422);
        }
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_e164' => 'required|phone',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $user = User::where('phone_e164',$request->phone_e164)->first();
        if(!$user){
            return response(['message' => trans('messages.phone_doesnt_exist')], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response(['message' => trans('messages.reset_password_successfully')], 200);
    }
}