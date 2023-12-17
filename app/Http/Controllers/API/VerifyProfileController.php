<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use App\Models\VerifyProfile;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use DB;
use Validator;
use File;
use Image;
use Auth;
class VerifyProfileController extends Controller
{
    use NotificationTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->verifyProfile();
        if($request->status != ''){
            $query->where('status',$request->status);
        }

        $verifyProfile = $query->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);
        
        return response($verifyProfile, 200);
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
        $validator = Validator::make($request->all(),
        [
            'full_name' => 'required',
            'ic_no' => 'required|numeric|digits:12',
            'front_ic' => 'required|image|mimes:jpg,png,jpeg',
            'back_ic' => 'required|image|mimes:jpg,png,jpeg',
            'selfie_with_ic' => 'required|image|mimes:jpg,png,jpeg',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $user = Auth::user();
        $hasCompletedVerified = $user->verifyProfile()->where('status',VerifyProfile::STATUS['Success'])->count();
        $hasPendingVerified = $user->verifyProfile()->where('status',VerifyProfile::STATUS['Pending'])->count();

        if ($hasCompletedVerified > 0) {
            return response(['message' => trans('messages.your_profile_verification_is_already_success')], 422);
        }

        if ($hasPendingVerified > 0) {
            return response(['message' => trans('messages.you_already_submitted_document')], 422);
        }

        DB::beginTransaction();
        try {
            if($request->hasFile('front_ic') && $request->hasFile('back_ic') && $request->hasFile('selfie_with_ic')) {
                $allowedfileExtension=['jpg','png','jpeg'];
                
                $frontICFile = $request->file('front_ic');
                $backICFile = $request->file('back_ic');
                $selfieICFile = $request->file('selfie_with_ic');

                $frontICfilename = $frontICFile->getClientOriginalName();
                $backICfilename = $backICFile->getClientOriginalName();
                $selfieICfilename = $selfieICFile->getClientOriginalName();

                $frontICextension = $frontICFile->extension();
                $backICextension = $backICFile->extension();
                $selfieICextension = $selfieICFile->extension();

                $check1 =in_array($frontICextension,$allowedfileExtension);
                $check2 =in_array($backICextension,$allowedfileExtension);
                $check3 =in_array($selfieICextension,$allowedfileExtension);

                
                if($check1 && $check2 && $check3) {
                    File::makeDirectory(storage_path('app/public/verify_profile/'.$user->id.'/attachment/'), $mode = 0777, true, true);
    
                    $input['frontimagename'] = 'front_ic_'.time().'.'.$frontICFile->getClientOriginalExtension();
                    $input['backimagename'] = 'back_ic_'.time().'.'.$backICFile->getClientOriginalExtension();
                    $input['selfieimagename'] = 'selfie_ic_'.time().'.'.$selfieICFile->getClientOriginalExtension();

                    $destination_path = storage_path('app/public/verify_profile/'.$user->id.'/attachment/');
                    $frontimg = Image::make($frontICFile->path());
                    $backimg = Image::make($backICFile->path());
                    $selfieimg = Image::make($selfieICFile->path());
                
                    $frontimg->save($destination_path.'/'.$input['frontimagename']);
                    $backimg->save($destination_path.'/'.$input['backimagename']);
                    $selfieimg->save($destination_path.'/'.$input['selfieimagename']);

                    $front_ic_image_full_path = 'verify_profile/'.$user->id.'/attachment/'.$input['frontimagename'];
                    $back_ic_image_full_path = 'verify_profile/'.$user->id.'/attachment/'.$input['backimagename'];
                    $selfie_ic_image_full_path = 'verify_profile/'.$user->id.'/attachment/'.$input['selfieimagename'];

                    $verifyProfile = $user->verifyProfile()->create([
                        'full_name' => $request->full_name,
                        'ic_no' => $request->ic_no,
                        'front_ic' => $front_ic_image_full_path,
                        'back_ic' => $back_ic_image_full_path,
                        'selfie_with_ic' => $selfie_ic_image_full_path,
                        'status' => VerifyProfile::STATUS['Pending']

                    ]);

                    $operators = Admin::whereHas('roles', function($q) {
                        return $q->where('name', Role::OPERATOR);
                    })->get();

                    foreach($operators as $operator){
                        $notificationData = [];
                        $notificationData['title'] = 'ic verification pending approval.';
                        $notificationData['message'] = 'There is an ic verification is pending approval.';
                        $notificationData['deepLink'] = 'fortknox-admin://verify-user-profiles/'.$verifyProfile->id;
        
                        $this->sendNotification($operator,$notificationData,$verifyProfile);
                    }

                    DB::commit();
                    return response([
                        'message' =>  trans('messages.profile_verification_pending_we_will_review_your_document_and_update_within_10_working_days'),
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
            return response(['message' => trans('messages.failed_to_submit_document')], 422);
        }
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

    public function pendingListing(Request $request){
        $verifyProfiles = VerifyProfile::where('status',VerifyProfile::STATUS['Pending'])->paginate($request->get('limit') ?? 10);
        
        return response($verifyProfiles, 200);
    }

    public function verifyProfileDetail($id){
        $verifyProfile = VerifyProfile::find($id);
        if(!$verifyProfile){
            return response(['message' => trans('messages.no_ic_verification_request_found')], 422);
        }
        return response($verifyProfile, 200);
    }

    public function approvedICVerification(Request $request, $id){
        $staff = Auth::user();

        $verifyProfile = VerifyProfile::find($id);
        if(!$verifyProfile){
            return response(['message' => trans('messages.no_ic_verification_request_found')], 422);
        }

        if($verifyProfile->status != VerifyProfile::STATUS['Pending']){
            return response(['message' => trans('messages.not_able_to_approve_when_status_is_not_pending')], 422);
        }

        $user = User::find($verifyProfile->user_id);
        if(!$user){
            return response(['message' => trans('messages.no_ic_verification_owner_found')], 422);
        }

        DB::beginTransaction();
        $verifyProfile->update([
            'status' => VerifyProfile::STATUS['Success'],
            'action_by' => $staff->id,
        ]);

        $user->update([
            'is_verified' => true
        ]);
 
        DB::commit();
        return response([
            'message' =>  trans('messages.profile_verified_you_can_start_playing_now'),
        ], 200);
    }

    public function rejectedICVerification(Request $request, $id){
        $staff = Auth::user();

        $verifyProfile = VerifyProfile::find($id);
        if(!$verifyProfile){
            return response(['message' => trans('messages.no_ic_verification_request_found')], 422);
        }

        if($verifyProfile->status != VerifyProfile::STATUS['Pending']){
            return response(['message' => trans('messages.not_able_to_reject_when_status_is_not_pending')], 422);
        }
      
        $user = $verifyProfile->user;
        if(!$user){
            return response(['message' => trans('messages.no_ic_verification_owner_found')], 422);
        }

        DB::beginTransaction();
        $verifyProfile->update([
            'status' => VerifyProfile::STATUS['Failed'],
            'action_by' => $staff->id,
        ]);

        DB::commit();
        return response([
            'message' =>  trans('messages.profile_verification_unsuccessful'),
        ], 200);
    }
}
