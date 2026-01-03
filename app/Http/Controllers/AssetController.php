<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asset::with(['employee', 'assignedBy']);

        // Filter by employee if provided
        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $assets = $query->latest()->paginate(15);
        $employees = Employee::where('status', 'active')->get();

        return view('assets.index', compact('assets', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::whereIn('status', ['active', 'onboarding'])->get();
        return view('assets.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'asset_type' => 'required|string|max:255',
            'asset_name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'assigned_date' => 'required|date',
        ]);

        $validated['assigned_by'] = Auth::id();
        $validated['status'] = 'assigned';

        Asset::create($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Asset assigned successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        $asset->load(['employee', 'assignedBy']);
        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $employees = Employee::whereIn('status', ['active', 'onboarding'])->get();
        return view('assets.edit', compact('asset', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'asset_type' => 'required|string|max:255',
            'asset_name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'assigned_date' => 'required|date',
            'status' => 'required|in:assigned,returned,damaged,lost',
            'return_date' => 'nullable|date',
            'return_notes' => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Asset deleted successfully.');
    }

    /**
     * Mark asset as returned.
     */
    public function markAsReturned(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'return_notes' => 'nullable|string',
        ]);

        $asset->update([
            'status' => 'returned',
            'return_date' => now(),
            'return_notes' => $validated['return_notes'] ?? null,
        ]);

        return back()->with('success', 'Asset marked as returned successfully.');
    }

    /**
     * Mark asset as damaged.
     */
    public function markAsDamaged(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'damage_notes' => 'required|string',
            'depreciation_value' => 'required|numeric|min:0',
        ]);

        $asset->update([
            'status' => 'damaged',
            'return_date' => now(),
            'damage_notes' => $validated['damage_notes'],
            'depreciation_value' => $validated['depreciation_value'],
        ]);

        return back()->with('success', 'Asset marked as damaged with depreciation value recorded.');
    }

    /**
     * Mark asset as lost.
     */
    public function markAsLost(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'damage_notes' => 'required|string',
            'depreciation_value' => 'required|numeric|min:0',
        ]);

        $asset->update([
            'status' => 'lost',
            'return_date' => now(),
            'damage_notes' => $validated['damage_notes'],
            'depreciation_value' => $validated['depreciation_value'],
        ]);

        return back()->with('success', 'Asset marked as lost with depreciation value recorded.');
    }
}
