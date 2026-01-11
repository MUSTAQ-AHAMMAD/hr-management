<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight"><span class="bg-gradient-to-r from-primary-600 to-cobalt-600 bg-clip-text text-transparent">
                {{ __('Exit Clearance Request #') }}{{ $exitClearanceRequest->id }}
            </span></h2>
            <div class="flex space-x-3">
                @can('edit exit clearance')
                <a href="{{ route('exit-clearance-requests.edit', $exitClearanceRequest) }}" class="inline-flex items-center px-4 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Request
                </a>
                @endcan
                @if($exitClearanceRequest->status === 'cleared' || $exitClearanceRequest->taskAssignments->whereIn('status', ['pending', 'in_progress'])->count() === 0)
                    <form action="{{ route('exit-clearance-requests.generate-pdf', $exitClearanceRequest) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Generate PDF
                        </button>
                    </form>
                @endif
                <a href="{{ route('exit-clearance-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Employee Details -->
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Employee Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Employee</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->employee->full_name }}</p>
                            <p class="text-sm text-gray-500">{{ $exitClearanceRequest->employee->employee_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Exit Date</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->exit_date?->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'cleared' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$exitClearanceRequest->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $exitClearanceRequest->status)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Initiated By</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->initiatedBy->name ?? 'N/A' }}</p>
                        </div>
                        @if($exitClearanceRequest->reason)
                            <div class="md:col-span-3">
                                <p class="text-sm text-gray-500">Reason</p>
                                <p class="text-base text-gray-900">{{ $exitClearanceRequest->reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Line Manager Approval Status -->
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Line Manager Approval</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Line Manager</p>
                            <p class="text-base font-medium text-gray-900">
                                {{ $exitClearanceRequest->line_manager_name ?? $exitClearanceRequest->lineManager->name ?? 'N/A' }}
                            </p>
                            @if($exitClearanceRequest->line_manager_email)
                                <p class="text-sm text-gray-500">{{ $exitClearanceRequest->line_manager_email }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Approval Status</p>
                            @php
                                $approvalStatusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $approvalStatusColors[$exitClearanceRequest->line_manager_approval_status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $exitClearanceRequest->line_manager_approval_status)) }}
                            </span>
                        </div>
                        @if($exitClearanceRequest->line_manager_approved_at)
                            <div>
                                <p class="text-sm text-gray-500">Approved/Rejected At</p>
                                <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->line_manager_approved_at->format('F d, Y H:i') }}</p>
                            </div>
                        @endif
                        @if($exitClearanceRequest->line_manager_approval_notes)
                            <div class="md:col-span-3">
                                <p class="text-sm text-gray-500">Approval Notes</p>
                                <p class="text-base text-gray-900">{{ $exitClearanceRequest->line_manager_approval_notes }}</p>
                            </div>
                        @endif
                    </div>
                    
                    @if($exitClearanceRequest->line_manager_approval_status === 'pending')
                        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Waiting for line manager approval.</strong> An email has been sent to the line manager. Departments cannot be assigned for clearance until the line manager approves this request.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($exitClearanceRequest->line_manager_approval_status === 'rejected')
                        <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        <strong>This exit clearance request has been rejected by the line manager.</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>

            <!-- Employee Assets -->
            @if($exitClearanceRequest->employee->assets->count() > 0)
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Employee Assets</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serial Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($exitClearanceRequest->employee->assets as $asset)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asset->asset_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asset->asset_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $asset->serial_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $assetStatusColors = [
                                                'assigned' => 'bg-blue-100 text-blue-800',
                                                'returned' => 'bg-green-100 text-green-800',
                                                'damaged' => 'bg-yellow-100 text-yellow-800',
                                                'lost' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $assetStatusColors[$asset->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($asset->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $asset->assigned_date?->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Task Assignments -->
            @if($exitClearanceRequest->taskAssignments->count() > 0)
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Clearance Tasks</h3>
                    <div class="space-y-4">
                        @foreach($exitClearanceRequest->taskAssignments->groupBy('task.department.name') as $departmentName => $assignments)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-3">{{ $departmentName }}</h4>
                                <div class="space-y-3">
                                    @foreach($assignments as $assignment)
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900">{{ $assignment->task->name }}</p>
                                                <p class="text-sm text-gray-500 mt-1">{{ $assignment->task->description }}</p>
                                                <p class="text-xs text-gray-400 mt-1">Assigned to: {{ $assignment->assignedTo->name ?? 'N/A' }}</p>
                                                @if($assignment->notes)
                                                    <p class="text-sm text-gray-600 mt-2"><strong>Notes:</strong> {{ $assignment->notes }}</p>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                @php
                                                    $taskStatusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'rejected' => 'bg-red-100 text-red-800',
                                                    ];
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $taskStatusColors[$assignment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Assign Tasks Form -->
            @if(($exitClearanceRequest->status === 'pending' || $availableTasks->whereNotIn('id', $exitClearanceRequest->taskAssignments->pluck('task_id'))->count() > 0) && $exitClearanceRequest->line_manager_approval_status === 'approved')
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <div class="flex items-center mb-4">
                        <svg class="h-6 w-6 text-cobalt-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Assign Additional Tasks</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">Select tasks from departments that need to complete clearance activities.</p>
                    <form action="{{ route('exit-clearance-requests.assign-tasks', $exitClearanceRequest) }}" method="POST">
                        @csrf
                        <div class="space-y-3 mb-4">
                            @foreach($availableTasks->whereNotIn('id', $exitClearanceRequest->taskAssignments->pluck('task_id'))->groupBy('department.name') as $deptName => $tasks)
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 hover:bg-gray-100 transition-colors duration-150">
                                    <div class="flex items-center mb-3">
                                        <svg class="h-5 w-5 text-cobalt-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <h4 class="font-semibold text-gray-900">{{ $deptName }}</h4>
                                    </div>
                                    <div class="space-y-2 pl-7">
                                        @foreach($tasks as $task)
                                            <div class="flex items-start p-3 bg-white rounded-lg border border-gray-200 hover:border-cobalt-300 transition-colors duration-150">
                                                <input type="checkbox" name="task_ids[]" value="{{ $task->id }}" id="task_{{ $task->id }}" class="mt-1 rounded border-gray-300 text-cobalt-600 focus:ring-cobalt-500">
                                                <label for="task_{{ $task->id }}" class="ml-3 text-sm cursor-pointer flex-1">
                                                    <span class="font-medium text-gray-900">{{ $task->name }}</span>
                                                    @if($task->description)
                                                        <span class="block text-gray-500 mt-1">{{ $task->description }}</span>
                                                    @endif
                                                    <span class="inline-flex items-center mt-2 text-xs text-gray-500">
                                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                        </svg>
                                                        Priority: {{ $task->priority }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-cobalt-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Assign Selected Tasks
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
