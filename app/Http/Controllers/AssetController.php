<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\User;
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
            'asset_value' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'condition' => 'nullable|in:new,good,fair,poor',
            'warranty_period' => 'nullable|string|max:255',
            'warranty_expiry' => 'nullable|date',
            'description' => 'nullable|string',
            'assigned_date' => 'required|date',
        ]);

        $validated['assigned_by'] = Auth::id();
        $validated['status'] = 'assigned';
        $validated['condition'] = $validated['condition'] ?? 'good';

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
            'asset_value' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'condition' => 'nullable|in:new,good,fair,poor',
            'warranty_period' => 'nullable|string|max:255',
            'warranty_expiry' => 'nullable|date',
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

        // Notify HR users about the damaged asset
        $this->notifyHRAboutAssetDamage($asset, 'damaged');

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

        // Notify HR users about the lost asset
        $this->notifyHRAboutAssetDamage($asset, 'lost');

        return back()->with('success', 'Asset marked as lost with depreciation value recorded.');
    }

    /**
     * Notify HR users about damaged or lost assets.
     */
    private function notifyHRAboutAssetDamage(Asset $asset, string $status)
    {
        // Get all HR users (users with Admin or Super Admin roles)
        $hrUsers = User::role(['Admin', 'Super Admin'])->get();

        $statusText = ucfirst($status);
        $lossAmount = $asset->depreciation_value ?? 0;
        
        $employee = $asset->employee;
        $department = $employee->department->name ?? 'N/A';

        foreach ($hrUsers as $hrUser) {
            Notification::create([
                'user_id' => $hrUser->id,
                'title' => "Asset {$statusText}: {$asset->asset_name}",
                'message' => "Employee {$employee->full_name} ({$employee->employee_code}) from {$department} department has {$status} asset '{$asset->asset_name}' ({$asset->asset_type}). Loss amount: $" . number_format($lossAmount, 2) . ". Reason: {$asset->damage_notes}",
                'type' => 'asset_damage',
                'notifiable_type' => Asset::class,
                'notifiable_id' => $asset->id,
                'is_read' => false,
            ]);
        }
    }

    /**
     * Display asset reports and analytics.
     */
    public function reports(Request $request)
    {
        $query = Asset::with(['employee.department', 'assignedBy']);

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->where('assigned_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('assigned_date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by department
        if ($request->has('department_id') && $request->department_id) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $assets = $query->latest()->paginate(50);

        // Calculate statistics
        $stats = [
            'total_assets' => Asset::count(),
            'total_value' => Asset::sum('asset_value') ?? 0,
            'assigned_count' => Asset::where('status', 'assigned')->count(),
            'returned_count' => Asset::where('status', 'returned')->count(),
            'damaged_count' => Asset::where('status', 'damaged')->count(),
            'lost_count' => Asset::where('status', 'lost')->count(),
            'total_loss' => Asset::whereIn('status', ['damaged', 'lost'])->sum('depreciation_value') ?? 0,
        ];

        // Get department-wise breakdown
        $departmentBreakdown = Asset::with('employee.department')
            ->get()
            ->groupBy(function ($asset) {
                return $asset->employee->department->name ?? 'No Department';
            })
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'value' => $group->sum('asset_value'),
                    'damaged' => $group->where('status', 'damaged')->count(),
                    'lost' => $group->where('status', 'lost')->count(),
                ];
            });

        $departments = \App\Models\Department::all();

        return view('assets.reports', compact('assets', 'stats', 'departmentBreakdown', 'departments'));
    }
}
