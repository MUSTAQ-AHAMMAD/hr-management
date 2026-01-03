<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    /**
     * Display the employee dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get employee record
        $employee = $user->employee;
        
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'No employee record found for your account.');
        }
        
        // Get pending assets (need acceptance)
        $pendingAssets = Asset::where('employee_id', $employee->id)
            ->where('acceptance_status', 'pending_acceptance')
            ->with(['assignedBy', 'department', 'taskAssignment.task'])
            ->get();
        
        // Get accepted assets
        $acceptedAssets = Asset::where('employee_id', $employee->id)
            ->where('acceptance_status', 'accepted')
            ->whereIn('status', ['assigned'])
            ->with(['assignedBy', 'department', 'taskAssignment.task'])
            ->get();
        
        // Get returned/damaged/lost assets
        $returnedAssets = Asset::where('employee_id', $employee->id)
            ->whereIn('status', ['returned', 'damaged', 'lost'])
            ->with(['assignedBy', 'department'])
            ->get();
        
        // Get onboarding status
        $onboardingRequest = $employee->onboardingRequests()
            ->whereIn('status', ['pending', 'in_progress'])
            ->with('taskAssignments.task.department')
            ->first();
        
        return view('employee-dashboard', compact(
            'employee',
            'pendingAssets',
            'acceptedAssets',
            'returnedAssets',
            'onboardingRequest'
        ));
    }
    
    /**
     * Accept an asset.
     */
    public function acceptAsset(Request $request, Asset $asset)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Verify this asset belongs to the logged-in employee
        if ($asset->employee_id !== $employee->id) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        // Update asset acceptance status
        $asset->update([
            'acceptance_status' => 'accepted',
            'acceptance_date' => now(),
        ]);
        
        return back()->with('success', 'Asset accepted successfully.');
    }
    
    /**
     * Reject an asset.
     */
    public function rejectAsset(Request $request, Asset $asset)
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        // Verify this asset belongs to the logged-in employee
        if ($asset->employee_id !== $employee->id) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        // Update asset acceptance status
        $asset->update([
            'acceptance_status' => 'rejected',
            'damage_notes' => $validated['rejection_reason'],
        ]);
        
        return back()->with('success', 'Asset rejection recorded. The department will be notified.');
    }
}
