<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DistributeResource;
use App\Models\WinnerList;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use File;
use Image;
use DB;
use Validator;
use Illuminate\Validation\Rule;

class DistributePrizeController extends Controller
{
    use NotificationTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id' => 'nullable|exists:games,id',
            'handled_by_me' => [Rule::in(array_values([true,false]))],
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $staff = Auth::user();
        if(!$staff){
            return response(['message' => trans('messages.no_user_found')], 422);
        }

        // $query = $staff->tickets()->whereHas('ticketNumbers.win', function($q){
        //                             $q->where('is_distribute', false);
        //                         })->with('ticketNumbers.win.drawResult');
        $requestGameId = $request->game_id;
        $query = WinnerList::whereHas('ticketNumber', function($q) use($requestGameId){
                                $q->whereHas('ticket', function($q) use($requestGameId){
                                    $q->when($requestGameId != '', function ($query) use($requestGameId){
                                        return $query->where('game_id', $requestGameId);
                                    });
                                });
                            })->where('outlet_id',$staff->outlet_id)->with('drawResult','ticketNumber','winner');

        // if($request->is_distribute != ''){
        //     $booleanValue = filter_var($request->is_distribute, FILTER_VALIDATE_BOOLEAN);
            // $query->where('is_distribute',$booleanValue);
            // if($booleanValue){
            //     $query->where('action_by',$staff->id);
            // }
        // }

            $query->where('is_distribute',0);


        if($request->handled_by_me){
            $query->where('action_by',$staff->id);
        }

        if($request->duration != ''){
            $query->where('created_at','>=', Carbon::now()->subDays($request->duration));
        }

        $winnerList = $query->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

        return response($winnerList, 200);
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
        $validator = Validator::make($request->all(),
        [
            'distribute_attachment' => 'required|image|mimes:jpg,png,jpeg',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $staff = Auth::user();
        if(!$staff){
            return response(['message' => trans('messages.no_user_found')], 422);
        }

        $winner = $staff->outlet->winnerList()->find($id);

        if(!$winner){
            return response(['message' => trans('messages.no_winner_prize_found')], 422);
        }

        if($winner->is_distribute){
            return response(['message' => trans('messages.prize_is_already_distributed')], 422);
        }

        DB::beginTransaction();
        try {
            if($request->hasFile('distribute_attachment')) {
                $allowedfileExtension=['jpg','png','jpeg'];

                $distributeAttachmentFile = $request->file('distribute_attachment');
                $distributeAttachmentfilename = $distributeAttachmentFile->getClientOriginalName();
                $distributeAttachmentextension = $distributeAttachmentFile->extension();

                $check =in_array($distributeAttachmentextension,$allowedfileExtension);

                if($check) {
                    File::makeDirectory(storage_path('app/public/distribute_prize/'.$staff->id.'/attachment/'), $mode = 0777, true, true);
                    $input['imagename'] = 'distribute_attachment_'.time().'.'.$distributeAttachmentFile->getClientOriginalExtension();
                    $destination_path = storage_path('app/public/distribute_prize/'.$staff->id.'/attachment/');
                    $frontimg = Image::make($distributeAttachmentFile->path());
                    $frontimg->save($destination_path.'/'.$input['imagename']);
                    $distribute_attachment_image_full_path = 'distribute_prize/'.$staff->id.'/attachment/'.$input['imagename'];
                    $winner->update([
                        'is_distribute' => true,
                        'distribute_attachment' => $distribute_attachment_image_full_path,
                        'action_by' => $staff->id
                    ]);

                    $winnerUser = $winner->winner;
                    if($winnerUser){
                        $notificationData = [];
                        $notificationData['title'] = 'Prize distribution';
                        $notificationData['message'] = 'Your Prize had distributed by our staff';
                        $notificationData['deepLink'] = env('APP_URL').'/api/me/winner/'.$winner->id;
                        $this->sendNotification($winnerUser,$notificationData,$winner);
                    }
                    DB::commit();
                    return response([
                        'message' =>  trans('messages.the_payment_receipt_is_submitted'),
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
            return response(['message' => trans('messages.failed_to_distribute_prize')], 422);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $winner = Auth::user()->outlet->winnerList()->find($id);

        if(!$winner){
            return response(['message' => trans('messages.no_winner_prize_found')], 422);
        }

        // if($winner->is_distribute){
        //     return response(['message' => trans('messages.prize_is_already_distributed')], 422);
        // }
        return new DistributeResource($winner);
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
}
