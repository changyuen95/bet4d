<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserTransferDetailResources;
use App\Models\TransferOption;
use App\Models\UserTransferDetails;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Exception;
use Auth;
use DB;
class UserTransferDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $transferDetails = $user->transferDetails()->with('transferOption')->orderBy('primary','ASC')->paginate($request->get('limit') ?? 10);
        return response($transferDetails, 200);
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
        $validator = Validator::make($request->all(), [
            'transfer_option_id' => ['required','exists:transfer_options,id'],
            'primary' => ['required',Rule::in(array_values(UserTransferDetails::PRIMARY))],
        ]);
       
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $transferOption = TransferOption::find($request->transfer_option_id);
        if(!$transferOption){
            return response(['message' => trans('messages.no_transfer_option_found')], 422);
        }

        $rules = [];
        if($transferOption->type == TransferOption::TYPE['eWallet']){
            $rules = [
                'phone_e164' => ['required','phone'],
                'phone_owner_name' => ['required'],
            ];
        }elseif($transferOption->type == TransferOption::TYPE['Bank']){
            $rules = [
                'bank_no' => ['required'],
                'bank_account_holder_name' => ['required'],
            ];
        }else{
            return response(['message' => trans('messages.invalid_transfer_option_type')], 422);
        }

        $validator1 = Validator::make($request->all(), $rules);

        if ($validator1->fails()) {
            return response(['message' => $validator1->errors()->first()], 422);
        }

        DB::beginTransaction();
        try{
            $user = Auth::user();
            if($request->primary == UserTransferDetails::PRIMARY['Yes']){
                $user->transferDetails()->update([
                    'primary' => UserTransferDetails::PRIMARY['No']
                ]);
            }

            $transferDetails = $user->transferDetails()->create([
                'transfer_option_id' => $request->transfer_option_id,
                'primary' => $request->primary,
                'bank_no' => $request->bank_no,
                'bank_account_holder_name' => $request->bank_account_holder_name,
                'phone_e164' => $request->phone_e164,
                'phone_owner_name' => $request->phone_owner_name,
            ]);

            DB::commit();
            return response([
                'message' =>  trans('messages.create_transfer_details_successfully'),
                'transfer_details' => new UserTransferDetailResources($transferDetails),
            ], 200);
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_create_transfer_details') ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $transferDetails = $user->transferDetails()->with('transferOption')->find($id);
        if(!$transferDetails){
            return response(['message' => trans('messages.no_transfer_detail_found')], 422);

        }
        return response(new UserTransferDetailResources($transferDetails), 200);
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
        $validator = Validator::make($request->all(), [
            'primary' => ['required',Rule::in(array_values(UserTransferDetails::PRIMARY))],
        ]);
       
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }
        $user = Auth::user();
        if(!$user){
            return response(['message' => trans('messages.no_user_found')], 422);
        }
        $transferDetails = $user->transferDetails()->find($id);
        if(!$transferDetails){
            return response(['message' => trans('messages.no_transfer_detail_found')], 422);
        }

        $transferOption = $transferDetails->transferOption;
        if(!$transferOption){
            return response(['message' => trans('messages.no_transfer_option_found')], 422);
        }

        $rules = [];
        if($transferOption->type == TransferOption::TYPE['eWallet']){
            $rules = [
                'phone_e164' => ['required','phone'],
                'phone_owner_name' => ['required'],
            ];
        }elseif($transferOption->type == TransferOption::TYPE['Bank']){
            $rules = [
                'bank_no' => ['required'],
                'bank_account_holder_name' => ['required'],
            ];
        }else{
            return response(['message' => trans('messages.invalid_transfer_option_type')], 422);
        }

        $validator1 = Validator::make($request->all(), $rules);

        if ($validator1->fails()) {
            return response(['message' => $validator1->errors()->first()], 422);
        }

        DB::beginTransaction();
        try{
            $user = Auth::user();
            if($request->primary == UserTransferDetails::PRIMARY['Yes']){
                $user->transferDetails()->update([
                    'primary' => UserTransferDetails::PRIMARY['No']
                ]);
            }
            
            $transferDetail = $user->transferDetails()->find($id);
            $transferDetail->primary = $request->primary;
            $transferDetail->bank_no = $request->bank_no;
            $transferDetail->bank_account_holder_name = $request->bank_account_holder_name;
            $transferDetail->phone_e164 = $request->phone_e164;
            $transferDetail->phone_owner_name = $request->phone_owner_name;
            $transferDetail->save();
            DB::commit();
            
            $transferDetails = UserTransferDetails::find($id);

            return response([
                'message' =>  trans('messages.update_transfer_details_successfully'),
                'transfer_details' => new UserTransferDetailResources($transferDetails),
            ], 200);
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_update_transfer_details') ], 422);
        }
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

        $transferDetails = $user->transferDetails()->find($id);
        if(!$transferDetails){
            return response(['message' => trans('messages.no_transfer_detail_found')], 422);
        }
        DB::beginTransaction();
        try{
            $transferDetails->delete();
            DB::commit();
            return response([
                'message' =>  trans('messages.remove_transfer_details_successfully'),
            ], 200);
        }catch (Exception $e) {dd($e);
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_remove_transfer_details') ], 422);
        }
    }
}
