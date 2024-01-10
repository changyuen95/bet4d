<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminClearCreditTransaction;
use App\Models\AdminCreditTransaction;
use App\Models\VerifyProfile;
use App\Models\WinnerList;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class RecordController extends Controller
{
    //
    public function indexProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'nullable|in:success,failed,all',
        ]);

        $type = $request->get('type');

        if($type == "failed"){
            $verifiedProfiles = VerifyProfile::where('status',VerifyProfile::STATUS['Failed']);

        }else if($type == "success"){
            $verifiedProfiles = VerifyProfile::where('status', VerifyProfile::STATUS['Success']);

        }else{
            $verifiedProfiles = VerifyProfile::where('status', '!=' ,VerifyProfile::STATUS['Pending']);
        }

        if($request->duration != ''){
            $verifiedProfiles->where('created_at','>=', Carbon::now()->subDays($request->duration));
        }

        $verifiedProfilesList = $verifiedProfiles->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

        return response($verifiedProfilesList, 200);
    }

    public function indexClearedCredit(Request $request)
    {
        $admin = $request->user();

        $clearedTransactions = AdminClearCreditTransaction::select('admin_clear_credit_transactions.*', 'admin_credit_transactions.*')
                                ->leftJoin('admins', 'admins.id', 'admin_clear_credit_transactions.admin_id')
                                ->leftJoin('admin_credit_transactions', 'admin_credit_transactions.admin_clear_credit_transactions_id', 'admin_clear_credit_transactions.id')
                                ->where('admins.outlet_id', $admin->outlet_id)
                                ->where('admin_credit_transactions.transaction_type', AdminCreditTransaction::TRANSACTION_TYPE['Cleared'])
                                ->where('admin_credit_transactions.is_verified', true);

        $duration = $request->duration ?? '';

        if($duration != '')
        {
            $clearedTransactions->where('admin_clear_credit_transactions.created_at','>=', Carbon::now()->subDays($request->duration));
        }

        $clearedTransactionsList = $clearedTransactions->orderBy('admin_clear_credit_transactions.created_at', 'desc')->paginate($request->get('limit') ?? 10);

        return response($clearedTransactionsList, 200);
    }

    public function indexVerifiedPrize(Request $request)
    {
        $admin = $request->user();

        $verifiedPrizes = WinnerList::where('is_verified', true)->where('outlet_id', $admin->outlet_id);


        $duration = $request->duration ?? '';

        if($duration != ''){
            $verifiedPrizes->where('created_at','>=', Carbon::now()->subDays($request->duration));
        }

        $verifiedPrizesList = $verifiedPrizes->orderBy('created_at', 'desc')->paginate($request->get('limit') ?? 10);

        return response($verifiedPrizesList, 200);
    }
}
