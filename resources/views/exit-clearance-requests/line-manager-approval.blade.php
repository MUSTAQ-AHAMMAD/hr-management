<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            <span class="bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                {{ __('Exit Clearance Approval Request') }}
            </span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Approval Notice -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg shadow-lg">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-green-900">Action Required</h3>
                            <p class="mt-2 text-sm text-green-800">
                                As the line manager, you need to review and approve this exit clearance request. 
                                Once approved, the HR department will assign clearance tasks to relevant departments.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Details -->
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="bg-gradient-to-r from-cobalt-600 to-primary-600 px-8 py-4">
                    <h3 class="text-xl font-bold text-white">Employee Information</h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-full bg-cobalt-100 flex items-center justify-center">
                                    <span class="text-cobalt-700 font-bold text-lg">
                                        {{ substr($exitClearanceRequest->employee->first_name, 0, 1) }}{{ substr($exitClearanceRequest->employee->last_name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Employee Name</p>
                                <p class="text-lg font-bold text-gray-900">{{ $exitClearanceRequest->employee->full_name }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Employee Code</p>
                            <p class="text-base font-semibold text-gray-900">{{ $exitClearanceRequest->employee->employee_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Designation</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->employee->designation ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->employee->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->employee->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exit Details -->
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="bg-gradient-to-r from-orange-600 to-red-600 px-8 py-4">
                    <h3 class="text-xl font-bold text-white">Exit Clearance Details</h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Exit Date</p>
                            <p class="text-base font-semibold text-gray-900">{{ $exitClearanceRequest->exit_date?->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Joining Date</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->employee->joining_date?->format('F d, Y') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Initiated By</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->initiatedBy->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Request Date</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->created_at->format('F d, Y') }}</p>
                        </div>
                        @if($exitClearanceRequest->reason)
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500 mb-2">Reason for Exit</p>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <p class="text-base text-gray-900">{{ $exitClearanceRequest->reason }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assets Assigned -->
            @if($exitClearanceRequest->employee->assets->count() > 0)
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Company Assets Assigned
                    </h3>
                </div>
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Asset Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Asset Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Serial Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Assigned Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($exitClearanceRequest->employee->assets as $asset)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $asset->asset_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asset->asset_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $asset->serial_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $asset->assigned_date?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            {{ $asset->status === 'assigned' ? 'bg-blue-100 text-blue-800' : 
                                               ($asset->status === 'returned' ? 'bg-green-100 text-green-800' : 
                                               ($asset->status === 'damaged' ? 'bg-red-100 text-red-800' : 
                                               'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($asset->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Approval Form -->
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-8 py-4">
                    <h3 class="text-xl font-bold text-white">Approve Exit Clearance</h3>
                </div>
                <div class="p-8">
                    <form action="{{ route('exit-clearance-requests.line-manager-approve', ['exitClearanceRequest' => $exitClearanceRequest->id, 'token' => $token]) }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Approval Notes (Optional)
                                </label>
                                <textarea 
                                    id="notes" 
                                    name="notes" 
                                    rows="4" 
                                    class="block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm"
                                    placeholder="Add any comments or notes about this approval..."></textarea>
                            </div>
                            <div class="flex gap-4">
                                <button 
                                    type="submit" 
                                    class="flex-1 inline-flex justify-center items-center px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg font-bold text-base text-white uppercase tracking-wider hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-200">
                                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Approve Exit Clearance
                                </button>
                                <a 
                                    href="{{ route('exit-clearance-requests.line-manager-reject', ['exitClearanceRequest' => $exitClearanceRequest->id, 'token' => $token]) }}" 
                                    class="inline-flex justify-center items-center px-6 py-4 bg-gray-200 border border-gray-300 rounded-lg font-semibold text-base text-gray-700 uppercase tracking-wider hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                                    View Reject Option
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
