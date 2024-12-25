<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Bank;
use App\Traits\NotificationTrait;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use File;
use Image;
use DB;
use Illuminate\Support\Facades\Storage;
class MeController extends Controller
{
    use NotificationTrait;

    public function me()
    {
        return new UserResource(Auth::user());
    }

    public function bank()
    {
        // return Auth::user()->bank;
    }

    public function update(Request $request)
    {
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['email','required',Rule::unique('users')->where(function ($query) use($id){
                return $query->whereNull('deleted_at')
                            ->where('id','!=',$id);
            })]
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }
        $user = Auth::user();
        $user->fill($request->only('name', 'email'));
        $user->save();

        return response(new UserResource($user));

    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        if(!$user){
            return response(['message' => trans('messages.no_user_found')], 422);
        }


        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        $user->delete();


        return response([
            'message' => trans('messages.successfully_deleted_account'),
        ], 200);

    }

    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'avatar' => 'required|image|mimes:jpg,png,jpeg',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $user = Auth::user();
        if(!$user){
            return response(['message' => trans('messages.no_user_found')], 422);
        }

        DB::beginTransaction();
        try {
            if($request->hasFile('avatar')) {
                $allowedfileExtension=['jpg','png','jpeg'];

                $avatarAttachmentFile = $request->file('avatar');
                $avatarAttachmentfilename = $avatarAttachmentFile->getClientOriginalName();
                $avatarAttachmentextension = $avatarAttachmentFile->extension();

                $check =in_array($avatarAttachmentextension,$allowedfileExtension);

                if($check) {
                    File::makeDirectory(storage_path('app/public/avatar/user/'.$user->id.'/attachment/'), $mode = 0777, true, true);
                    $input['imagename'] = 'avatar_'.time().'.'.$avatarAttachmentFile->getClientOriginalExtension();
                    $destination_path = storage_path('app/public/avatar/user/'.$user->id.'/attachment/');
                    $img = Image::make($avatarAttachmentFile->path());
                    $img->save($destination_path.'/'.$input['imagename']);
                    $avatar_attachment_image_full_path = asset('storage/avatar/user/'.$user->id.'/attachment/'.$input['imagename']);
                    $oldAvatar = $user->avatar;
                    $user->update([
                        'avatar' => $avatar_attachment_image_full_path,
                    ]);
                    Storage::delete('public/'.str_replace(asset('storage/'),'',$oldAvatar));
                    DB::commit();
                    return response([
                        'message' =>  trans('messages.successfully_update_avatar'),
                    ], 200);
                } else {
                    DB::rollback();
                    return response(['message' => trans('messages.only_png_jpg_jpeg_is_accepted')], 422);
                }

            }else{
                DB::rollback();
                return response(['message' => trans('messages.no_file_detected')], 422);
            }

        } catch (\Throwable $th) {
            DB::rollback();
            return response(['message' => trans('messages.failed_to_update_avatar')], 422);
        }
    }

    public function oneSignalTest(){
        $notificationData = [];
        $notificationData['title'] = 'One Signal Test';
        $notificationData['message'] = 'Testing';
        $notificationData['deepLink'] = 'fortknox://me/one-signal-test/test';
        $appId = env('ONESIGNAL_APP_ID');
        $apiKey = env('ONESIGNAL_REST_API_KEY');

        $this->sendNotification($appId, $apiKey, Auth::user(),$notificationData);

    }

    public function requestWinner(Request $request , $id)
        {

        $user = Auth::user();
        if(!$user){
            return response(['message' => trans('messages.no_user_found')], 422);
        }
        $ticket_number = $user->ticketNumbers()->find($id);

        if(!$ticket_number){
            return response(['message' => trans('messages.no_ticket_number_found')], 422);
        }

        $ticket = $ticket_number->ticket;
        if(!$ticket){
            return response(['message' => trans('messages.no_ticket_found')], 422);
        }

        $winner = $ticket_number->win;
        if(!$winner){
            return response(['message' => trans('messages.no_winner_found')], 422);
        }


    }
}
