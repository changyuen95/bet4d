<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use App\Models\Platform;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DataTables;

class DrawController extends Controller
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
        return view('admin.draw.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $platforms = Platform::where('status', 'active')->get();
        return view('admin.draw.create', compact('platforms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'platform' => 'required',
            'draw_date' => 'required',
        ]);

        if($validator->fails())
        {
            Session::flash('fail', 'Fail to add special draw!');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        // try{

            $formated_date = Carbon::parse($request->draw_date)->format('Y-m-d');

            /****** To get the previous draw with same year ******/
            $year = Carbon::parse($request->draw_date)->format('Y');
            $previous_draw = Draw::where('platform_id', $request->platform)->whereYear('created_at', $year)->orderBy('created_at', 'desc')->first();


            /****** To get the next draw from the request date ******/
            $next_draw = Draw::where('platform_id', $request->platform)->where('is_special_draw', false)->whereDate('expired_at', '>', $formated_date)->orderBy('expired_at', 'asc')->first();

            if($next_draw)
            {
                Session::flash('fail', 'Cannot create a special draw before an ordinary draw');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            /****** Add new draw record ******/
            $new_special_draw = new Draw();
            $new_special_draw->platform_id = $request->platform;
            $new_special_draw->expired_at = Carbon::parse($request->draw_date)->format("Y-m-d 19:00:00");

            if($previous_draw)
            {
                $draw_num = $previous_draw->draw_no;
                $new_special_draw->draw_no = $draw_num + 1;

            } else{
                $new_special_draw->draw_no = 1;
            }

            $new_special_draw->is_special_draw = true;
            $new_special_draw->year = Carbon::parse($request->draw_date)->format('y');
            $new_special_draw->save();

        // } catch (\Exception $ex)
        // {
        //     DB::rollBack();
        //     log::info($ex->getMessage());
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        DB::commit();

        return redirect()->route('admin.draws.index');
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

    public function getCalendarList()
    {
        $user = Auth::user();
        $admin_platform = $user->outlet->platform->id;
        $draws = Draw::where('platform_id', $admin_platform)->get();

        return response()->json($draws);
    }

    public function showDrawTicketList($date)
    {
        $platforms = Platform::where('status', 'active')->get();
        return view('admin.draw.draw-ticket-list', [
            'date' => $date,
            'platforms' => $platforms,
        ]);
    }

    public function ticketListDatatable(Request $request)
    {
        $param = $request->toArray();
        $search = $request['search']['value'];
        $platform_id = $request['platform']??null;

        $user = Auth::user();
        $admin_platform = $user->outlet->platform->id;

        $nearest_draw = Draw::where('expired_at', '>=' ,$request['calendarDate'])->orderBy('expired_at', 'asc')->first();

        $tickets = Ticket::with('ticketNumbers')
                    ->leftjoin('users', 'users.id', 'tickets.user_id')
                    ->leftjoin('games', 'games.id', 'tickets.game_id')
                    ->leftjoin('outlets', 'outlets.id', 'tickets.outlet_id')
                    ->where('tickets.status', 'completed')
                    ->where('tickets.draw_id', $nearest_draw->id)
                    ->where('tickets.platform_id', $platform_id)
                    ->when($request->search['value'], function ($query) use ($request) {
                        foreach(explode(' ',$request->search['value']) as $search){

                            $query->where(function($query) use ($request,$search) {
                                $query->where('users.name', 'REGEXP', $search)
                                    ->orWhere('games.name', 'REGEXP', $search)
                                    ->orWhere('');
                            });
                        }
                    })
                    ->select('users.name as username', 'games.name as game_name', 'tickets.created_at as purchased_at', 'outlets.name as outlet_name');


        if($request->ajax()) {
            $table = Datatables::of($tickets)
                ->addIndexColumn()
                ->editColumn('users.name', function ($ticket) {
                    return $ticket->username ?? '-';
                })

                ->editColumn('ticketNumbers', function ($ticket) {
                    return $ticket->ticketNumbers->first() ?? '-';
                })

                ->editColumn('games.name', function ($ticket) {
                    return $ticket->game_name ?? '-';
                })

                ->editColumn('outlets.name', function ($ticket) {
                    return $ticket->outlet_name ?? '-';
                })

                ->editColumn('tickets.created_at', function ($ticket) {

                    $purchased_at = $ticket->purchased_at;

                    return $purchased_at ?? '-';
                })
                ->escapeColumns([])
                ->make(true);

            return $table;
        }
    }
}
