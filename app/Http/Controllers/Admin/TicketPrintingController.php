<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\TicketPrinting;
use App\Models\TicketPrintingNumber;
use App\Models\Draw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TicketPrintingController extends Controller
{
    public function index()
    {
        $tickets = TicketPrinting::with('draw')->latest()->paginate(20);

        return view('ticket_printing.index', compact('tickets'));
    }

    public function create()
    {
        $draws = Draw::where('created_at','<=',Carbon::now())->orderBy('created_at', 'desc')->get();

        return view('ticket_printing.create', compact('draws'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'serial_number' => 'required|string|max:255',
        'draw_id' => 'required|exists:draws,id',
        'printed_at' => 'required|date',
        'outlet_number' => 'required|string|max:255',
        'tickets' => 'required|array|min:1|max:6',
        'tickets.*.number' => 'required|string|max:4',
        'tickets.*.big' => 'required|numeric|min:0',
        'tickets.*.small' => 'required|numeric|min:0',
        'ticket_type' => 'required',
        'subtotal' => 'required|numeric|min:0',
    ]);
    
    $serialNumber = strtoupper(trim($validated['serial_number']));

    DB::beginTransaction();
    try {
        $ticket = TicketPrinting::create([
            'serial_number' => $serialNumber,
            'draw_id' => $validated['draw_id'],
            'printed_at' => $validated['printed_at'],
            'outlet_number' => $validated['outlet_number'],
            'ticket_type' => $validated['ticket_type'],
            'subtotal' => $validated['subtotal']
        ]);
        

        foreach ($validated['tickets'] as $row) {
            $ticket->numbers()->create([
                'number' => $row['number'],
                'amount_big' => $row['big'],
                'amount_small' => $row['small'],
            ]);
        }

        DB::commit();
        // ✅ Redirect to print page if requested
        if ($request->input('action') === 'print') {
            return redirect()->route('admin.ticket_printing.print', $ticket->id);
        }

        return redirect()->route('admin.ticket_printing.index')->with('success', 'Ticket saved.');

    } catch (\Throwable $th) {
        dd($th);
        DB::rollBack();
        return back()->withErrors(['error' => $th->getMessage()]);
    }
}



    public function show($id)
    {
        $ticket = TicketPrinting::with(['draw', 'numbers'])->findOrFail($id);
        return view('ticket_printing.show', compact('ticket'));
    }

    
    public function print($id)
    {
        $ticket = TicketPrinting::with('numbers', 'draw')->findOrFail($id);
        return view('ticket_printing.print', compact('ticket'));
    }
    
    public function sign(Request $request)
{
    $dataToSign = $request->input('data');

    // Read private key (adjust path if you stored elsewhere)
    $privateKey = file_get_contents(base_path('private-key.pem')); 

    // Sign the data
    openssl_sign($dataToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);

    // Return the base64 signature
    return response()->json([
        'signature' => base64_encode($signature),
    ]);
}

}
