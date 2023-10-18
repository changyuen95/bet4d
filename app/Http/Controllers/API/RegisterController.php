<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tac;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\TacNotification;
use App\Rules\Badword;
use Carbon\Carbon;
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
                'phone_e164' => ['required','phone',Rule::unique('users')->whereNull('deleted_at')],
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
    
            $user->fill($request->only('name', 'email', 'username', 'phone_e164'));
            $user->password = bcrypt($request->get('password'));
            $user->save();
            
            $user->credit()->create([
                'credit' => 0
            ]);

            $user->assignRole('normal_user');
            DB::commit();
            return response(['message' =>  trans('messages.register_successfully') ], 200);
    
            // $user->sendEmailVerificationNotification();
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.register_failed') ], 422);
        }
    
    }

    public function registerTac(Request $request){
        $validator = Validator::make($request->all(), [
            'phone_e164' => 'required|phone',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try{
            $user = User::where('phone_e164', $request->get('phone_e164'))->first();

            if ($user) {
                return response(['message' => trans('messages.phone_exists')], 422);
            }
            $user = new User;
            $user->phone_e164 = $request->phone_e164;

            $validTac = Tac::where('phone_e164',$request->phone_e164)->where('expired_at','>=' ,Carbon::now())->where('verified_at',null)->count();

            if ($validTac > 0 ) {
                return response(['message' => trans('messages.please_try_again_in_2_minutes')], 422);
            }

            // $tacNo = $this->sendTac($request->phone_e164);
            $tacNo = mt_rand(100000, 999999);            
            $user->notify(new TacNotification($tacNo));
            
            $currentDatetime = Carbon::now();
            $expired_at = $currentDatetime->addMinutes(2);
            $user->tacs()->create([
                'phone_e164' => $request->phone_e164,
                'verify_code' => $tacNo,
                'ref' => Tac::REFERENCE['Register_User'],
                'expired_at' => $expired_at,
            ]);
            
            DB::commit();

            return response(['message' =>  trans('messages.send_tac_successfully') ], 200);
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.send_tac_failed') ], 422);
        }
    }

}