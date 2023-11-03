<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tac;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Str;

class VerifyTacController extends Controller
{
    public function __invoke(Request $request)
    {
        // phone, tac,expired_at
        $validator = Validator::make($request->all(), [
            'phone_e164' => 'required|phone',
            'tac' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        // $user = User::where('phone_e164', $request->get('phone_e164'))->first();
        // if(!$user){
        //     return response(['message' => trans('messages.phone_doesnt_exist')], 422);
        // }

        // $tac = $user->tacs()->where('verify_code', $request->tac)->where('verified_at','=',null)->where('expired_at','>',Carbon::now())->first();

        $tac = Tac::where('phone_e164',$request->phone_e164)
                ->where('verify_code', $request->tac)
                ->where('verified_at','=',null)
                ->where('expired_at','>',Carbon::now())
                ->first();
        $token = Str::random(20);
        if(!$tac){
            return response(['message' => trans('messages.invalid_tac')], 422);
        }else{
            $tac->verified_at = Carbon::now();
            $tac->token = $token;
            $tac->save();
            return response([
                'message' => trans('messages.verify_successfully'),
                'token' => $token,
            ], 200);
        }
    }

    public function index(Request $request)
    {
        $input['phone_e164'] = '+'.$request->phone_e164;
        // phone, tac,expired_at
        $validator = Validator::make($input, [
            'phone_e164' => 'required|phone',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $tac = Tac::where('phone_e164',$input['phone_e164'])->orderBy('created_at','DESC')->first();
        if(!$tac){
            return response(['message'=>trans('messages.no_record_found')], 422);
        }

        return response($tac, 200);

    }
}
