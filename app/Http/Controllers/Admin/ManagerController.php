<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Manager::query();

        // Filter by IC if provided
        if ($request->has('ic') && $request->ic != '') {
            $ic = preg_replace('/[^0-9]/', '', $request->ic);
            $query->where('ic', 'LIKE', '%' . $ic . '%');
        }

        // Filter by name if provided
        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        // Filter by role if provided
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $managers = $query->orderBy('name', 'asc')->paginate(15);

        return view('admin.manager.index', compact('managers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.manager.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ic' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:1,2,3',
            'is_active' => 'required|boolean',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Remove any non-numeric characters from IC
            $ic = preg_replace('/[^0-9]/', '', $request->ic);

            // Check if IC already exists
            $existingManager = Manager::where('ic', $ic)->first();
            if ($existingManager) {
                Session::flash('error', 'Manager with this IC already exists!');
                return redirect()->back()->withInput();
            }

            $manager = Manager::create([
                'name' => $request->name,
                'ic' => $ic,
                'phone' => $request->phone,
                'role' => $request->role,
                'is_active' => $request->is_active,
                'remarks' => $request->remarks,
            ]);

            DB::commit();

            Session::flash('success', 'Manager created successfully!');
            return redirect()->route('admin.managers.index');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating manager: ' . $e->getMessage());
            Session::flash('error', 'Failed to create manager!');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $manager = Manager::findOrFail($id);
        return view('admin.manager.show', compact('manager'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $manager = Manager::findOrFail($id);
        return view('admin.manager.edit', compact('manager'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ic' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:1,2,3',
            'is_active' => 'required|boolean',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $manager = Manager::findOrFail($id);

            // Remove any non-numeric characters from IC
            $ic = preg_replace('/[^0-9]/', '', $request->ic);

            // Check if IC already exists for another manager
            $existingManager = Manager::where('ic', $ic)->where('id', '!=', $id)->first();
            if ($existingManager) {
                Session::flash('error', 'Another manager with this IC already exists!');
                return redirect()->back()->withInput();
            }

            $manager->update([
                'name' => $request->name,
                'ic' => $ic,
                'phone' => $request->phone,
                'role' => $request->role,
                'is_active' => $request->is_active,
                'remarks' => $request->remarks,
            ]);

            DB::commit();

            Session::flash('success', 'Manager updated successfully!');
            return redirect()->route('admin.managers.index');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating manager: ' . $e->getMessage());
            Session::flash('error', 'Failed to update manager!');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $manager = Manager::findOrFail($id);
            $manager->delete();

            Session::flash('success', 'Manager deleted successfully!');
            return redirect()->route('admin.managers.index');

        } catch (Exception $e) {
            Log::error('Error deleting manager: ' . $e->getMessage());
            Session::flash('error', 'Failed to delete manager!');
            return redirect()->back();
        }
    }
}
