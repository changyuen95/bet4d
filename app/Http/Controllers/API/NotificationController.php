<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $user = Auth::user();
        // $query = $user->notifications();
        // $notifications = $query->paginate($request->get('limit') ?? 10);
        // return $notifications;
        return Auth::user()->notifications()->orderBy('created_at','DESC')->paginate($request->get('limit') ?? 10);

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

    public function markAsRead(string $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($id);
        if(!$notification){
            return response(['message' => trans('messages.no_notification_found')], 422);
        }

        if($notification->read_at == null){
            $notification->update([
                'read_at' => Carbon::now()
            ]);
        }

        return response(['message' => trans('messages.marked_as_read')], 200);

    }

    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        $notification = $user->notifications()->whereNull('read_at');


        $notification->update([
            'read_at' => Carbon::now()
        ]);




        return response(['message' => trans('messages.marked_as_read')], 200);

    }

    public function unReadCount(){
        $user = Auth::user();
        if(!$user){
            return response(['message' => trans('messages.no_user_found')], 422);
        }
        $notificationsCount = $user->notifications()->whereNull('read_at')->count();

        $count = [
            'notifications_count' => $notificationsCount,
        ];

        return $notificationsCount;
    }
}
