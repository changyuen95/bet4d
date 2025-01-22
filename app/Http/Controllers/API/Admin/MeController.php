<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Validation\Rule;
use DB;
use Illuminate\Support\Facades\Storage;
use Image;
use File;
use App\Traits\NotificationTrait;
class MeController extends Controller
{
    use NotificationTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
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
    public function update(Request $request)
    {
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['email','required',Rule::unique('admins')->where(function ($query) use($id){
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

        return response(new AdminResource($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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

    public function me()
    {
        return new AdminResource(Auth::user());
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
                    File::makeDirectory(storage_path('app/public/avatar/admin/'.$user->id.'/attachment/'), $mode = 0777, true, true);
                    $input['imagename'] = 'avatar_'.time().'.'.$avatarAttachmentFile->getClientOriginalExtension();
                    $destination_path = storage_path('app/public/avatar/admin/'.$user->id.'/attachment/');
                    $img = Image::make($avatarAttachmentFile->path());
                    $img->save($destination_path.'/'.$input['imagename']);
                    $avatar_attachment_image_full_path = asset('storage/avatar/admin/'.$user->id.'/attachment/'.$input['imagename']);
                    $oldAvatar = $user->profile_image;
                    $user->update([
                        'profile_image' => $avatar_attachment_image_full_path,
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

    public function oneSignalStaffTest(){
        $notificationData = [];
        $notificationData['title'] = 'One Signal Test';
        $notificationData['message'] = 'Testing';
        $notificationData['deepLink'] = 'fortknox://me/one-signal-test/test';
        $appId = env('ONESIGNAL_STAFF_APP_ID');
        $apiKey = env('ONESIGNAL_STAFF_REST_API_KEY');

        $this->sendNotification($appId, $apiKey, Auth::user(),$notificationData);

    }
}
