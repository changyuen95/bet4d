<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tac;
use Illuminate\Http\Request;
use App\Models\Bonus;
use App\Models\UserBonus;
use App\Models\User;
use App\Notifications\TacNotification;
use App\Rules\Badword;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\Rule;
use Validator;
use DB;
use App\Traits\NotificationTrait;

use Str;
class RegisterController extends Controller
{

    use NotificationTrait;

    public function __invoke(Request $request)
{
    DB::beginTransaction();
    try {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'username' => ['required', Rule::unique('users')->whereNull('deleted_at')],
            'phone_e164' => ['required', 'phone'],
            'email' => ['nullable', 'email', Rule::unique('users')->whereNull('deleted_at')],
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'min:6',
        ], [
            'phone_e164.required' => 'The phone number field is required.',
            'phone_e164.phone' => 'The phone number format is invalid.',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        // Create the new user
        $user = new User;

        $defaultAvatar = asset('images/default_avatar.jpg');

        $user->fill($request->only('name', 'email', 'username', 'phone_e164'));
        $user->password = bcrypt($request->get('password'));
        $user->avatar = $defaultAvatar;
        $user->save();

        // Create user credit and point records
        $user->credit()->create(['credit' => 0]);
        $user->point()->create(['point' => 0]);

        // Assign default role
        $user->assignRole('normal_user');

        // Check for active registration bonus
        $bonus = Bonus::where('target', 'registration')->where('status', 'active')->first();

        if ($bonus) {
            $bonusAmount = $bonus->type === 'fixed'
                ? round($bonus->value, 2) // Fixed bonus
                : 0; // Percentage is not applicable for registration bonuses

            // Create UserBonus record
            $userBonus = UserBonus::create([
                'user_id' => $user->id,
                'bonus_id' => $bonus->id,
                'amount' => $bonusAmount,
                'description' => 'Bonus for registering an account',
            ]);

            // Add bonus to user credit
            $userCredit = $user->credit;
            $userCredit->credit += $bonusAmount;
            $userCredit->save();

            // Send notification about the registration bonus
            $notificationData = [
                'title' => 'Welcome Bonus Received!',
                'message' => 'You have received ' . $bonusAmount . ' as a welcome bonus for registering.',
                'deepLink' => '', // Add your app-specific deep link if needed
            ];
            $this->sendNotification(config('app.ONESIGNAL_APP_ID'), config('app.ONESIGNAL_REST_API_KEY'), $user, $notificationData, $userCredit);
        }

        DB::commit();

        return response(['message' => trans('messages.register_successfully')], 200);
    } catch (Exception $e) {
        DB::rollback();
        return response(['message' => trans('messages.register_failed')], 422);
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
            $tacNo = mt_rand(1000, 9999);
            if(env('APP_ENV') == 'production'){
                $user->notify(new TacNotification($tacNo));
            }

            $currentDatetime = Carbon::now();
            $expired_at = $currentDatetime->addMinutes(2);
            $user->tacs()->create([
                'phone_e164' => $request->phone_e164,
                'verify_code' => $tacNo,
                'token' => '',
                'ref' => Tac::REFERENCE['Register_User'],
                'expired_at' => $expired_at,
            ]);

            DB::commit();

            $response = [
                'message' =>  trans('messages.send_tac_successfully'),
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

}
