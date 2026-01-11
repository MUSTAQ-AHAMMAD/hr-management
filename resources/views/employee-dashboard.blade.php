<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">Welcome, {{ $employee->full_name }}!</h3>
                    <p class="text-gray-600">Employee Code: {{ $employee->employee_code }}</p>
                    <p class="text-gray-600">Department: {{ $employee->department->name }}</p>
                    <p class="text-gray-600">Status: <span class="font-semibold">{{ ucfirst($employee->status) }}</span></p>
                </div>
            </div>

            <!-- Onboarding Status -->
            @if($onboardingRequest)
            <div class="bg-blue-50 border border-blue-200 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-xl font-bold mb-2 text-blue-900">Your Onboarding Status</h3>
                            <p class="text-gray-700 mb-2">Request #{{ $onboardingRequest->id }} - Status: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $onboardingRequest->status)) }}</span></p>
                            <p class="text-sm text-gray-600 mb-4">Expected Completion: {{ $onboardingRequest->expected_completion_date->format('M d, Y') }}</p>
                            
                            @if($onboardingRequest->taskAssignments->count() > 0)
                            <div class="mt-4">
                                <h4 class="font-semibold mb-3 text-blue-800">Department Onboarding Tasks:</h4>
                                <div class="space-y-2">
                                    @foreach($onboardingRequest->taskAssignments as $assignment)
                                    <div class="bg-white rounded-lg p-3 border border-blue-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <span class="font-medium text-gray-900">{{ $assignment->task->name }}</span>
                                                <p class="text-sm text-gray-600">{{ $assignment->task->department->name }}</p>
                                                @if($assignment->task->description)
                                                <p class="text-xs text-gray-500 mt-1">{{ $assignment->task->description }}</p>
                                                @endif
                                                @if($assignment->notes)
                                                <p class="text-xs text-gray-500 mt-1 italic">Note: {{ $assignment->notes }}</p>
                                                @endif
                                            </div>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $assignment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 p-3 bg-blue-100 rounded-lg">
                                    <p class="text-sm text-blue-900">
                                        <strong>Progress:</strong> {{ $onboardingRequest->taskAssignments->where('status', 'completed')->count() }} of {{ $onboardingRequest->taskAssignments->count() }} tasks completed
                                    </p>
                                    @if($onboardingRequest->taskAssignments->where('status', 'completed')->count() === $onboardingRequest->taskAssignments->count())
                                    <p class="text-sm text-green-700 font-semibold mt-2">
                                        ✓ Congratulations! All onboarding tasks are complete. Welcome to the team!
                                    </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Exit Clearance Status -->
            @if(isset($exitClearanceRequest) && $exitClearanceRequest)
            <div class="bg-orange-50 border border-orange-200 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-xl font-bold mb-2 text-orange-900">Your Exit Clearance Status</h3>
                            <p class="text-gray-700 mb-2">Request #{{ $exitClearanceRequest->id }} - Status: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $exitClearanceRequest->status)) }}</span></p>
                            <p class="text-sm text-gray-600 mb-2">Exit Date: {{ $exitClearanceRequest->exit_date->format('M d, Y') }}</p>
                            @if($exitClearanceRequest->line_manager_name)
                            <p class="text-sm text-gray-600 mb-2">Line Manager: {{ $exitClearanceRequest->line_manager_name }}</p>
                            @endif
                            @if($exitClearanceRequest->line_manager_approval_status)
                            <p class="text-sm mb-4">
                                Line Manager Approval: 
                                <span class="font-semibold {{ $exitClearanceRequest->line_manager_approval_status === 'approved' ? 'text-green-600' : ($exitClearanceRequest->line_manager_approval_status === 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
                                    {{ ucfirst($exitClearanceRequest->line_manager_approval_status) }}
                                </span>
                            </p>
                            @endif
                            
                            @if($exitClearanceRequest->taskAssignments->count() > 0)
                            <div class="mt-4">
                                <h4 class="font-semibold mb-3 text-orange-800">Department Clearance Tasks:</h4>
                                <div class="space-y-2">
                                    @foreach($exitClearanceRequest->taskAssignments as $assignment)
                                    <div class="bg-white rounded-lg p-3 border border-orange-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <span class="font-medium text-gray-900">{{ $assignment->task->name }}</span>
                                                <p class="text-sm text-gray-600">{{ $assignment->task->department->name }}</p>
                                                @if($assignment->task->description)
                                                <p class="text-xs text-gray-500 mt-1">{{ $assignment->task->description }}</p>
                                                @endif
                                                @if($assignment->notes)
                                                <p class="text-xs text-gray-500 mt-1 italic">Note: {{ $assignment->notes }}</p>
                                                @endif
                                                @if($assignment->completed_date)
                                                <p class="text-xs text-gray-400 mt-1">Completed: {{ $assignment->completed_date->format('M d, Y h:i A') }}</p>
                                                @endif
                                            </div>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $assignment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 p-3 bg-orange-100 rounded-lg">
                                    <p class="text-sm text-orange-900">
                                        <strong>Progress:</strong> {{ $exitClearanceRequest->taskAssignments->where('status', 'completed')->count() }} of {{ $exitClearanceRequest->taskAssignments->count() }} tasks completed
                                    </p>
                                    @if($exitClearanceRequest->taskAssignments->where('status', 'completed')->count() === $exitClearanceRequest->taskAssignments->count())
                                    <p class="text-sm text-green-700 font-semibold mt-2">
                                        ✓ All clearance tasks completed! Your exit clearance is being processed.
                                    </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Pending Assets (Need Acceptance) -->
            @if($pendingAssets->count() > 0)
            <div class="bg-yellow-50 border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4 text-yellow-900">Assets Pending Your Acceptance</h3>
                    <p class="text-gray-700 mb-4">Please review and accept the following assets assigned to you:</p>
                    
                    <div class="space-y-4">
                        @foreach($pendingAssets as $asset)
                        <div class="border border-yellow-300 rounded-lg p-4 bg-white">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg">{{ $asset->asset_name }}</h4>
                                    <p class="text-gray-600">Type: {{ $asset->asset_type }}</p>
                                    @if($asset->serial_number)
                                    <p class="text-gray-600">Serial Number: {{ $asset->serial_number }}</p>
                                    @endif
                                    @if($asset->description)
                                    <p class="text-gray-600 mt-2">{{ $asset->description }}</p>
                                    @endif
                                    <p class="text-sm text-gray-500 mt-2">
                                        Assigned by: {{ $asset->assignedBy->name }} ({{ $asset->department->name }})
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Date: {{ $asset->assigned_date->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="ml-4 flex flex-col space-y-2">
                                    <form method="POST" action="{{ route('employee-dashboard.accept-asset', $asset) }}">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                            Accept Asset
                                        </button>
                                    </form>
                                    <button type="button" onclick="showRejectModal({{ $asset->id }})" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        Reject Asset
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div id="rejectModal{{ $asset->id }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <div class="mt-3">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Asset: {{ $asset->asset_name }}</h3>
                                    <form method="POST" action="{{ route('employee-dashboard.reject-asset', $asset) }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="rejection_reason{{ $asset->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                Reason for Rejection *
                                            </label>
                                            <textarea 
                                                id="rejection_reason{{ $asset->id }}" 
                                                name="rejection_reason" 
                                                rows="4" 
                                                required
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                placeholder="Please explain why you are rejecting this asset..."></textarea>
                                        </div>
                                        <div class="flex space-x-3">
                                            <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                                Submit Rejection
                                            </button>
                                            <button type="button" onclick="hideRejectModal({{ $asset->id }})" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Accepted Assets -->
            @if($acceptedAssets->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4 text-gray-900 flex items-center">
                        <svg class="h-6 w-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Your Current Assets
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serial Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Accepted Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($acceptedAssets as $asset)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $asset->asset_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->asset_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">{{ $asset->serial_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->assigned_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->acceptance_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->department->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if($asset->description)
                                            {{ Str::limit($asset->description, 50) }}
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Returned/Damaged/Lost Assets History -->
            @if($returnedAssets->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Asset History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Return Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($returnedAssets as $asset)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $asset->asset_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->asset_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $asset->status === 'returned' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $asset->status === 'damaged' ? 'bg-orange-100 text-orange-800' : '' }}
                                            {{ $asset->status === 'lost' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($asset->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $asset->return_date ? $asset->return_date->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">{{ $asset->return_notes ?? $asset->damage_notes ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            @if($pendingAssets->count() === 0 && $acceptedAssets->count() === 0 && $returnedAssets->count() === 0 && !$onboardingRequest)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500">
                    <p>You have no active assets or onboarding activities at this time.</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        function showRejectModal(assetId) {
            document.getElementById('rejectModal' + assetId).classList.remove('hidden');
        }

        function hideRejectModal(assetId) {
            document.getElementById('rejectModal' + assetId).classList.add('hidden');
        }
    </script>
</x-app-layout>
