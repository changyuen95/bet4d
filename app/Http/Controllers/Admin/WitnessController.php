<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Witness;
use App\Models\Draw;
use App\Models\DrawWitness;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class WitnessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Witness::query();

        // Filter by IC if provided
        if ($request->has('ic') && $request->ic != '') {
            $ic = preg_replace('/[^0-9]/', '', $request->ic);
            $query->where('ic', 'LIKE', '%' . $ic . '%');
        }

        // Filter by name if provided
        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        $witnesses = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.witness.index', compact('witnesses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.witness.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ic' => 'required|string|unique:witnesses,ic',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Remove any non-numeric characters from IC
            $ic = preg_replace('/[^0-9]/', '', $request->ic);

            $witness = Witness::create([
                'name' => $request->name,
                'ic' => $ic,
                'phone' => $request->phone,
                'address' => $request->address,
                'remarks' => $request->remarks,
            ]);

            DB::commit();

            Session::flash('success', 'Witness created successfully!');
            return redirect()->route('admin.witnesses.index');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating witness: ' . $e->getMessage());
            Session::flash('error', 'Failed to create witness!');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $witness = Witness::with(['draws' => function($query) {
            $query->orderBy('expired_at', 'desc');
        }])->findOrFail($id);

        return view('admin.witness.show', compact('witness'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $witness = Witness::findOrFail($id);
        return view('admin.witness.edit', compact('witness'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $witness = Witness::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ic' => 'required|string|unique:witnesses,ic,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Remove any non-numeric characters from IC
            $ic = preg_replace('/[^0-9]/', '', $request->ic);

            $witness->update([
                'name' => $request->name,
                'ic' => $ic,
                'phone' => $request->phone,
                'address' => $request->address,
                'remarks' => $request->remarks,
            ]);

            DB::commit();

            Session::flash('success', 'Witness updated successfully!');
            return redirect()->route('admin.witnesses.index');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating witness: ' . $e->getMessage());
            Session::flash('error', 'Failed to update witness!');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $witness = Witness::findOrFail($id);
            $witness->delete();

            Session::flash('success', 'Witness deleted successfully!');
            return redirect()->route('admin.witnesses.index');

        } catch (Exception $e) {
            Log::error('Error deleting witness: ' . $e->getMessage());
            Session::flash('error', 'Failed to delete witness!');
            return redirect()->back();
        }
    }

    /**
     * Show the page to select witnesses for current draw
     */
    public function selectForDraw()
    {
        // Get current draw
        $currentDraw = Draw::getCurrentDraw();

        if (!$currentDraw) {
            Session::flash('error', 'No active draw found!');
            return redirect()->route('admin.witnesses.index');
        }

        // Get already selected witnesses for this draw
        $selectedWitnesses = $currentDraw->witnesses()->get();

        // Get all witnesses for selection
        $witnesses = Witness::orderBy('name', 'asc')->get();

        return view('admin.witness.select-for-draw', compact('currentDraw', 'selectedWitnesses', 'witnesses'));
    }

    /**
     * Save selected witnesses for current draw
     */
    public function saveSelectedWitnesses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'draw_id' => 'required|exists:draws,id',
            'witness_ids' => 'required|array|min:1|max:10',
            'witness_ids.*' => 'exists:witnesses,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $draw = Draw::findOrFail($request->draw_id);

            // Remove existing witnesses for this draw
            DrawWitness::where('draw_id', $draw->id)->delete();

            // Add selected witnesses
            foreach ($request->witness_ids as $witnessId) {
                DrawWitness::create([
                    'draw_id' => $draw->id,
                    'witness_id' => $witnessId,
                    'selected_at' => Carbon::now(),
                ]);
            }

            DB::commit();

            Session::flash('success', 'Witnesses selected successfully!');
            return redirect()->route('admin.witnesses.select-for-draw');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error selecting witnesses: ' . $e->getMessage());
            Session::flash('error', 'Failed to select witnesses!');
            return redirect()->back();
        }
    }

    /**
     * Print witness form for current draw
     */
    public function printWitnessForm()
    {
        // Get current draw
        $currentDraw = Draw::getCurrentDraw();

        if (!$currentDraw) {
            Session::flash('error', 'No active draw found!');
            return redirect()->route('admin.witnesses.index');
        }

        // Get selected witnesses for this draw
        $witnesses = $currentDraw->witnesses()->get();

        if ($witnesses->isEmpty()) {
            Session::flash('error', 'No witnesses selected for this draw!');
            return redirect()->route('admin.witnesses.select-for-draw');
        }

        return view('admin.witness.print', compact('currentDraw', 'witnesses'));
    }
}
