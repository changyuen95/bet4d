<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\CreditTransaction;
use App\Models\AdminCreditTransaction;
use App\Models\Game;
use App\Models\Admin;
use App\Models\Platform;
use App\Models\Barcode as Barcode_table;
use App\Models\Role;
use App\Models\BankReceipt;
use App\Models\TopUp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use Exception;
use Auth;
use App\Traits\NotificationTrait;
use Faker\Core\Barcode;
use App\Models\BankAccount;
use File;
use Image;
class BankReceiptController extends Controller
{
    use NotificationTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //staff view all bank receipt

        $validator = Validator::make($request->all(), [
            'status' => ['nullable','array'],
        ]);

        // if ($validator->fails()) {
        //     return response(['message' => $validator->errors()->first()], 422);
        // }

        $query = BankReceipt::where('status',BankReceipt::STATUS['RECEIPT_REQUESTED']);
        $receipts = $query->paginate($request->get('limit') ?? 10);


        return response($receipts, 200);
    }

    public function pending(Request $request)
    {

        // if ($validator->fails()) {
        //     return response(['message' => $validator->errors()->first()], 422);
        // }

        $query = BankReceipt::where('status',BankReceipt::STATUS['RECEIPT_REQUESTED']);

        $receipts = $query->paginate($request->get('limit') ?? 10);

        return response($receipts, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(Request $request)
    // {
    //     // user create bank receipt
    //     $validator = Validator::make($request->all(), [
    //         'amount' => ['required','numeric'],
    //         'receipt_image' => ['required','image'],
    //     ]);

    //     if ($validator->fails()) {
    //         return response(['message' => $validator->errors()->first()], 422);
    //     }

    //     $receipts = BankReceipt::create([
    //         'user_id' => Auth::user()->id,
    //         'amount' => $request->amount,
    //         'receipt_image' => $request->receipt_image,
    //         'status' => BankReceipt::STATUS['RECEIPT_REQUESTED'],
    //     ]);

    //     return response($receipts, 200);

    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // user create bank receipt
        $validator = Validator::make($request->all(), [
            'amount' => ['required','numeric'],
            'receipt_image' => ['required','image'],
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $user = Auth::user();

            File::makeDirectory(storage_path('app/public/bank_receipt/'.$user->id.'/_receipt_/'), $mode = 0777, true, true);
            $receipt_image = $request->file('receipt_image');

           $input['receipt_image'] = 'receipt_image_'.time().'.'.$receipt_image->getClientOriginalExtension();

            $destination_path = storage_path('app/public/bank_receipt/'.$user->id.'/_receipt_/');
            $stored_image = Image::make($receipt_image->path());


            $stored_image->save($destination_path.'/'.$input['receipt_image']);


            $image_full_path = 'bank_receipt/'.$user->id.'/_receipt_/'.$input['receipt_image'];

        $receipt = BankReceipt::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'image' => $image_full_path,
            'status' => BankReceipt::STATUS['RECEIPT_REQUESTED'],
            'approved_by' => null,
        ]);

        return response($receipt, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request ,  $id)
    {
        $receipt = BankReceipt::find($id);

        if(!$receipt){
            return response(['message' => trans('messages.no_receipt_found')], 422);
        }

        // return new TicketResource($receipt);
        return response($receipt, 200);

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

    public function updateReceiptStatus(Request $request, $id)
    {
        //user update receipt status
        $validator = Validator::make($request->all(), [
            // 'user_id' => ['required'],
            'status' => ['required',Rule::in(array_values([BankReceipt::STATUS['RECEIPT_REQUESTED'],BankReceipt::STATUS['RECEIPT_REJECTED'],BankReceipt::STATUS['RECEIPT_SUCCESSFUL']]))],
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $user = Auth::user();
        $receipt = $user->receipts()->find($id);
        if(!$receipt){
            return response(['message' =>  trans('messages.invalid_ticket') ], 422);
        }


        DB::beginTransaction();
        try{
            $userCredit = $user->credit;
            if(!$userCredit){
                return response(['message' => trans('messages.no_user_credit_found')], 422);
            }

            $receipt->status = $request->status;
            $receipt->save();
            DB::commit();

            if($receipt->status == BankReceipt::STATUS['RECEIPT_REQUESTED']){
                    $staffs = Admin::whereHas('roles', function($q) {
                        return $q->where('name', Role::HQ);
                    })->get();
                    $notificationData = [];
                    $notificationData['title'] = 'New topup request';
                    $notificationData['message'] = 'You have receive new bank receipt.';
                    $notificationData['deepLink'] = 'fortknox-admin://bank-receipt/'.$receipt->id;
                    $appId = env('ONESIGNAL_STAFF_APP_ID');
                    $apiKey = env('ONESIGNAL_STAFF_REST_API_KEY');
                    foreach($staffs as $staff){
                        $this->sendNotification($appId, $apiKey, $staff,$notificationData,$receipt);
                    }

            }

            return response([
                'message' =>  trans('messages.update_status_successfully'),
                'ticket' => $receipt
            ], 200);
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_update_status') ], 422);
        }
    }



    public function staffUpdateReceiptStatus(Request $request, $id){
       //admin update ticket status
       $validator = Validator::make($request->all(), [
        // 'user_id' => ['required'],
        'status' => ['required',Rule::in(array_values([BankReceipt::STATUS['RECEIPT_REQUESTED'],BankReceipt::STATUS['RECEIPT_REJECTED'],BankReceipt::STATUS['RECEIPT_SUCCESSFUL']]))],
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $admin = Auth::user();
        $receipt = BankReceipt::find($id);
        if(!$receipt){
            return response(['message' =>  trans('messages.invalid_receipt') ], 422);
        }

        DB::beginTransaction();

        try{
            $user = $receipt->user;
            $userCredit = $user->credit;
            $adminCredit = $admin->credit;

            if($receipt->status == BankReceipt::STATUS['RECEIPT_REQUESTED'] && $request->status == BankReceipt::STATUS['RECEIPT_SUCCESSFUL']){
                $receipt->creditTransaction()->create([
                    'user_id' => $user->id,
                    'amount' => $receipt->amount,
                    'type'  => CreditTransaction::TYPE['Increase'],
                    'before_amount' => $userCredit->credit,
                    'outlet_id' => null,
                    'bank_receipt_id' => $receipt->id,
                ]);

                $userCredit->credit = $userCredit->credit + $receipt->amount;
                $userCredit->save();

                $topup = $admin->topUpMorph()->create([
                    'user_id' => $user->id,
                    'amount' => $request->amount,
                    'remark' => 'Bank transfer',
                    'top_up_with' => TopUp::TOP_UP_WITH['Bank'],
                ]);

                $creditTransaction = $topup->creditTransaction()->create([
                    'user_id' => $user->id,
                    'amount' => $request->amount,
                    'type' => CreditTransaction::TYPE['Increase'],
                    'before_amount' => $userCredit->credit,
                    'outlet_id' => null,
                ]);

                // admin/staff credit
                $adminTransaction= $topup->adminTransaction()->create([
                    'admin_id' => $admin->id,
                    'amount' => $request->amount,
                    'type' => AdminCreditTransaction::TYPE['Increase'],
                    'before_amount' => $adminCredit->amount,
                    'outlet_id' => null,
                    'after_amount' => 0,
                ]);

            }

            if(!$userCredit){
                return response(['message' => trans('messages.no_user_credit_found')], 422);
            }
            $receipt->status = $request->status;
            $receipt->approved_by = $admin->id;
            $receipt->top_up_id = $topup->id;
            $receipt->save();
            DB::commit();

            if($receipt->status == BankReceipt::STATUS['RECEIPT_REQUESTED']){
                    $notificationData = [];
                    $notificationData['title'] = 'New ticket request';
                    $notificationData['message'] = 'You have receive new ticket request.';
                    $notificationData['deepLink'] = 'fortknox://bank_receipt/'.$receipt->id;
                    $appId = env('ONESIGNAL_STAFF_APP_ID');
                    $apiKey = env('ONESIGNAL_STAFF_REST_API_KEY');
                    $this->sendNotification($appId, $apiKey, $receipt->user_id,$notificationData,$receipt);

            }

            return response([
                'message' =>  trans('messages.update_status_successfully'),
                'ticket' => ($receipt)
            ], 200);
        }catch (Exception $e) {
            DB::rollback();
            return response(['message' =>  trans('messages.failed_to_update_status') ], 422);
        }
    }


    public function bankAccount(Request $request)
    {
        $bank = BankAccount::first();
        return response($bank, 200);


    }

}
