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
                    <h3 class="text-xl font-bold mb-4 text-blue-900">Your Onboarding Status</h3>
                    <p class="text-gray-700 mb-4">Status: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $onboardingRequest->status)) }}</span></p>
                    
                    @if($onboardingRequest->taskAssignments->count() > 0)
                    <div class="mt-4">
                        <h4 class="font-semibold mb-2">Department Tasks:</h4>
                        <ul class="list-disc list-inside space-y-2">
                            @foreach($onboardingRequest->taskAssignments as $assignment)
                            <li class="text-gray-700">
                                <span class="font-medium">{{ $assignment->task->department->name }}</span>: {{ $assignment->task->name }}
                                - <span class="text-sm {{ $assignment->status === 'completed' ? 'text-green-600' : 'text-orange-600' }}">
                                    {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
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
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Your Current Assets</h3>
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
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($acceptedAssets as $asset)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $asset->asset_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->asset_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->serial_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->assigned_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->acceptance_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->department->name }}</td>
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
