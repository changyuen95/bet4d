<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CreditTransaction;
use App\Models\PointTransaction;
use App\Models\TopUp;
use App\Models\User;
use App\Models\Qrcode;
use App\Models\QrScannedList;
use Illuminate\Http\Request;
use Auth;
use Exception;
use DB;
use Validator;
class TopUpController extends Controller
{
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
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $user = User::find($id);
        if (!$user) {
            return response(['message' => trans('messages.no_user_found')], 422);
        }
        $staff = Auth::user();
        $outlet = $staff->outlet;
        DB::beginTransaction();
        try{
            $userCredit = $user->credit;
            if(!$userCredit){
                $userCredit = $user->credit()->create([
                    'credit' => 0
                ]);
            }

            $adminCredit = $staff->credit;
            if(!$adminCredit){
                $adminCredit = $staff->admin_credit()->create([
                    'credit' => 0
                ]);
            }

            $userPoint = $user->point;
            if(!$userPoint){
                $userPoint = $user->point()->create([
                    'point' => 0
                ]);
            }

            // $topup = $user->topup()->create([
            //     'amount' => $request->amount,
            //     'remark' => $request->remark,
            //     'top_up_with' => TopUp::TOP_UP_WITH['Outlet'],
            //     'created_by' => Auth::user()->id
            // ]);
            $topup = $staff->topUpMorph()->create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'remark' => $request->remark,
                'top_up_with' => TopUp::TOP_UP_WITH['Outlet'],
            ]);

            $creditTransaction = $topup->creditTransaction()->create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'type' => CreditTransaction::TYPE['Increase'],
                'before_amount' => $userCredit->credit,
                'outlet_id' => $outlet->id,
            ]);

            // admin/staff credit
            $adminTransaction= $topup->adminTransaction()->create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'type' => CreditTransaction::TYPE['Increase'],
                'before_amount' => $userCredit->credit
            ]);

            $userCredit->credit = $userCredit->credit + $request->amount;
            $userCredit->save();

            $adminCredit->credit = $adminCredit->credit + $request->amount;
            $adminCredit->save();

            $pointTransaction = $topup->pointTransaction()->create([
                'user_id' => $user->id,
                'point' => $request->amount,
                'type' => PointTransaction::TYPE['Increase'],
                'before_point' => $request->amount,
                'outlet_id' => $outlet->id,
            ]);

            $userPoint->point = $userPoint->point + $request->amount;
            $userPoint->save();

            DB::commit();

            return response(['message' => trans('messages.you_had_successfully_top_up').' '.$request->amount.' '.trans('messages.credit').' '.trans('messages.for').' '.$user->name], 200);
        }catch (Exception $e) {dd($e);
            DB::rollback();
            return response(['message' =>  trans('messages.top_up_failed') ], 422);
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

    public function topupByQrCode(Request $request,$id)
    {
        $user = Auth::user();

        $qrcode = Qrcode::where('id',$request->qrcode_id)->first();

        if(!$qrcode){
            return response(['message' => trans('messages.invalid_qrcode')], 422);
        }

        if($qrcode->avaibility > 0){
            $qrcode->update([
                'avaibility' => $qrcode->avaibility - 1,
            ]);

            if($qrcode->avaibility < 0){

                $qrcode->update([
                    'avaibility' => $qrcode->avaibility + 1,
                ]);
                return response(['message' => trans('messages.please_try_again')], 422);

            }

        }else{
            return response(['message' => trans('messages.qrcode_reached_max_amount')], 422);
        }

        DB::beginTransaction();


        try{
            $userCredit = $user->credit;
            if(!$userCredit){
                $userCredit = $user->credit()->create([
                    'credit' => 0
                ]);
            }

            $topup = Topup::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'remark' => $request->remark,
                'top_up_with' => TopUp::TOP_UP_WITH['QR'],
            ]);

            $creditTransaction = $topup->creditTransaction()->create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'type' => CreditTransaction::TYPE['Increase'],
                'before_amount' => $userCredit->credit,
                'outlet_id' => $user->outlet_id,
            ]);

            $userCredit->credit = $userCredit->credit + $request->amount;
            $userCredit->save();

            $qrcode_transaction = QrScannedList::create([
                'user_id' => $user->id,
                'qrcode_id' => $qrcode->id,
            ]);

            DB::commit();

            return response(['message' => trans('messages.you_had_successfully_top_up').' '.$qrcode->amount.' '.trans('messages.credit').' '.trans('messages.for').' '.$user->name], 200);
        }catch (Exception $e) {dd($e);
            DB::rollback();
            $qrcode->avaibility = $qrcode->avaibility+1;
            $qrcode->save();

            return response(['message' =>  trans('messages.top_up_failed') ], 422);
        }
    }

}
