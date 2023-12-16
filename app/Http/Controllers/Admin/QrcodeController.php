<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Qrcode;
use App\Models\QrScannedList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DataTables;

class QrcodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $qrCodes = Qrcode::all();


        return view('admin.qrcode.index', compact('qrCodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //


        return view('admin.qrcode.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'qr_name' => 'nullable',
            'credit_amount' => 'required|numeric',
            'scan_limit' => 'required|numeric',
            'remark' => 'nullable||string|min:5|max:255',
            'status' => 'required|integer',

        ]);

        if($validator->fails()){

            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $new_qrcode = new Qrcode();
        $new_qrcode->name = $request->qr_name;
        $new_qrcode->scan_limit = $request->scan_limit;
        $new_qrcode->credit = $request->credit_amount;
        $new_qrcode->remark = $request->remark;
        $new_qrcode->status = $request->status;
        $new_qrcode->save();

        DB::commit();

        Session::flash('success', 'QR code successfully created');
        return redirect()->route('admin.qrcodes.index');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $qrCode = Qrcode::findorFail($id);

        return view('admin.qrcode.show', compact('qrCode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $qrCode = Qrcode::findorFail($id);
        return view('admin.qrcode.edit', compact('qrCode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $qrCode = Qrcode::find($id);
        if(!$qrCode)
        {
            Session::flash('fail', 'Something went wrong. QR code not found!');
            return redirect()->back();
        }

        $qrCode->name = $request->qr_name;
        $qrCode->status = $request->status;
        $qrCode->remark = $request->remark;
        $qrCode->save();

        Session::flash('success', 'QR code detail updated successfully');
        return redirect()->route('admin.qrcodes.show', $qrCode->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $qr = Qrcode::find($id);
        if(!$qr)
        {
            return response()->json(['success' => false, 'message' => 'Something went wrong when deleting the QR Code!']);
        }

        $qr->deleted_at = Carbon::now();
        $qr->save();

        return response()->json(['success' => true, 200]);

    }

    public function indexScannedList()
    {
        // $qrcodes = QrScannedList::leftjoin('users','users.id', 'qr_scanned_lists.user_id')
        //                         ->leftjoin('qrcodes','qrcodes.id','qr_scanned_lists.qr_id')
        //                         ->select('users.name as username', 'qrcodes.name as qrname', 'qrcodes.scan_limit as scan_limit', 'qrcodes.credit as credit', 'qr_scanned_lists.created_at as scanned_at')
        //                         ->get();

        //                         dd($qrcodes);
        return view('admin.qrcode.scanned-list');
    }

    public function scannedListDatatable(Request $request)
    {

        $param = $request->toArray();
        $search = $request['search']['value'];

        $qrcodes = QrScannedList::leftjoin('users','users.id', 'qr_scanned_lists.user_id')
                                ->leftjoin('qrcodes','qrcodes.id','qr_scanned_lists.qr_id')
                                ->when($request->search['value'], function ($query) use ($request) {

                                    foreach(explode(' ',$request->search['value']) as $search){

                                        $query->where(function($query) use ($request,$search) {
                                            $query->where('users.name', 'REGEXP', $search)
                                            ->orWhere('qrcodes.name', 'REGEXP', $search);
                                        });
                                    }
                                })
                                ->select('users.name as username', 'qrcodes.name as qrname', 'qrcodes.scan_limit as scan_limit', 'qrcodes.credit as credit', 'qr_scanned_lists.created_at as scanned_at');


        if($request->ajax()) {
            $table = Datatables::of($qrcodes)
                ->addIndexColumn()
                ->editColumn('users.name', function ($qrcode) {
                    return $qrcode->username ?? '-';
                })

                ->editColumn('qrcodes.name', function ($qrcode) {
                    return $qrcode->qrname ?? '-';
                })

                ->editColumn('qrcodes.scan_limit', function ($qrcode) {
                    return $qrcode->scan_limit ?? '-';
                })

                ->editColumn('qrcodes.credit', function ($qrcode) {
                    return $qrcode->credit ?? '-';
                })

                ->editColumn('qr_scanned_lists.created_at', function ($qrcode) {

                    $scanned_at = $qrcode->scanned_at;
                    $formated_date = Carbon::parse($scanned_at)->diffForHumans();

                    return $formated_date ?? '-';
                })
                ->escapeColumns([])
                ->make(true);

            return $table;
        }
    }

    public function qrCodePrint($id)
    {
        $qrcode = Qrcode::findOrFail($id);
    
        return view('admin.qrcode.print', compact('qrcode'));
    }
}
