<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employee Details') }}
            </h2>
            <div class="flex space-x-2">
                @can('edit employees')
                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-4 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Employee
                </a>
                @endcan
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Email Pending Alert -->
            @if($employee->isPendingEmailCreation())
            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 px-6 py-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="font-bold">Pending Email Creation</h3>
                        <p class="text-sm">This employee is waiting for IT team to create an email ID. The IT team has been notified.</p>
                    </div>
                </div>
            </div>
            @elseif($employee->email_created_by_it)
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold">Email Created by IT</h3>
                        <p class="text-sm">Email ID was created by IT team on {{ $employee->email_created_at?->format('F d, Y \a\t h:i A') }}. Employee is ready for onboarding.</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Basic Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Employee Code</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->employee_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-base font-medium text-gray-900">
                                @if($employee->email)
                                    {{ $employee->email }}
                                    @if($employee->email_created_by_it)
                                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800" title="Created by IT on {{ $employee->email_created_at?->format('F d, Y') }}">
                                            IT Created
                                        </span>
                                    @endif
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending IT Creation
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Designation</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->designation }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Joining Date</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->joining_date?->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Exit Date</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->exit_date?->format('F d, Y') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <div class="mt-1">
                                @if($employee->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                                @elseif($employee->status === 'inactive')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    On Leave
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onboarding Requests -->
            @if($employee->onboardingRequests->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="h-6 w-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Onboarding History
                    </h3>
                    <div class="space-y-4">
                        @foreach($employee->onboardingRequests as $request)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-medium text-gray-900 text-lg">Request #{{ $request->id }}</p>
                                    <p class="text-sm text-gray-500">Created: {{ $request->created_at->format('F d, Y h:i A') }}</p>
                                    @if($request->expected_completion_date)
                                    <p class="text-sm text-gray-500">Expected Completion: {{ $request->expected_completion_date->format('F d, Y') }}</p>
                                    @endif
                                </div>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $request->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($request->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </div>
                            
                            @if($request->taskAssignments->count() > 0)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Tasks:</p>
                                <div class="space-y-2">
                                    @foreach($request->taskAssignments as $assignment)
                                    <div class="flex items-center justify-between bg-purple-50 p-2 rounded">
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-gray-900">{{ $assignment->task->name }}</span>
                                            <span class="text-xs text-gray-500 ml-2">({{ $assignment->task->department->name }})</span>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $assignment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($assignment->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Exit Clearance Requests -->
            @if($employee->exitClearanceRequests->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="h-6 w-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Exit Clearance History
                    </h3>
                    <div class="space-y-4">
                        @foreach($employee->exitClearanceRequests as $request)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-medium text-gray-900 text-lg">Request #{{ $request->id }}</p>
                                    <p class="text-sm text-gray-500">Created: {{ $request->created_at->format('F d, Y h:i A') }}</p>
                                    @if($request->exit_date)
                                    <p class="text-sm text-gray-500">Exit Date: {{ $request->exit_date->format('F d, Y') }}</p>
                                    @endif
                                    @if($request->line_manager_name)
                                    <p class="text-sm text-gray-500">Line Manager: {{ $request->line_manager_name }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $request->status === 'cleared' ? 'bg-green-100 text-green-800' : 
                                           ($request->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($request->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                    </span>
                                    @if($request->line_manager_approval_status)
                                    <p class="text-xs text-gray-500 mt-1">
                                        LM: 
                                        <span class="font-semibold {{ $request->line_manager_approval_status === 'approved' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ ucfirst($request->line_manager_approval_status) }}
                                        </span>
                                    </p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($request->reason)
                            <div class="mt-2 p-2 bg-gray-50 rounded text-sm text-gray-700">
                                <strong>Reason:</strong> {{ $request->reason }}
                            </div>
                            @endif
                            
                            @if($request->taskAssignments->count() > 0)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Clearance Tasks:</p>
                                <div class="space-y-2">
                                    @foreach($request->taskAssignments as $assignment)
                                    <div class="flex items-center justify-between bg-orange-50 p-2 rounded">
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-gray-900">{{ $assignment->task->name }}</span>
                                            <span class="text-xs text-gray-500 ml-2">({{ $assignment->task->department->name }})</span>
                                            @if($assignment->completed_date)
                                            <span class="text-xs text-gray-400 ml-2">- {{ $assignment->completed_date->format('M d, Y') }}</span>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $assignment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($assignment->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($assignment->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <a href="{{ route('exit-clearance-requests.show', $request) }}" class="text-sm text-cobalt-600 hover:text-cobalt-900 font-semibold">
                                    View Full Details →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Assets -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="h-6 w-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Asset History
                        </h3>
                        @can('create assets')
                        <a href="{{ route('assets.create', ['employee_id' => $employee->id]) }}" class="inline-flex items-center px-4 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Assign Asset
                        </a>
                        @endcan
                    </div>
                    
                    @if($employee->assets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial Number</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acceptance</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($employee->assets as $asset)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $asset->asset_name }}</div>
                                        @if($asset->asset_value)
                                        <div class="text-sm text-gray-500">Value: ${{ number_format($asset->asset_value, 2) }}</div>
                                        @endif
                                        @if($asset->description)
                                        <div class="text-xs text-gray-400 mt-1">{{ Str::limit($asset->description, 30) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $asset->asset_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-mono">{{ $asset->serial_number ?? 'N/A' }}</div>
                                        @if($asset->condition)
                                        <div class="text-xs text-gray-500">Condition: {{ ucfirst($asset->condition) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $asset->assigned_date?->format('M d, Y') }}
                                        @if($asset->assignedBy)
                                        <div class="text-xs text-gray-400">by {{ $asset->assignedBy->name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $asset->return_date?->format('M d, Y') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($asset->acceptance_status === 'accepted')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Accepted
                                        </span>
                                        @if($asset->acceptance_date)
                                        <div class="text-xs text-gray-400 mt-1">{{ $asset->acceptance_date->format('M d, Y') }}</div>
                                        @endif
                                        @elseif($asset->acceptance_status === 'rejected')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                        @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($asset->status === 'assigned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Assigned
                                        </span>
                                        @elseif($asset->status === 'returned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Returned
                                        </span>
                                        @elseif($asset->status === 'damaged')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Damaged
                                        </span>
                                        @elseif($asset->status === 'lost')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Lost
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('assets.show', $asset) }}" class="text-cobalt-600 hover:text-cobalt-900">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-8">No assets assigned to this employee yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
