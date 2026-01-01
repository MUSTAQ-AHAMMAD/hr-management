<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Exit Clearance Request #') }}{{ $exitClearanceRequest->id }}
            </h2>
            <div class="flex space-x-3">
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

    <div class="py-6">
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
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

            <!-- Employee Assets -->
            @if($exitClearanceRequest->employee->assets->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
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
            @if($exitClearanceRequest->status === 'pending' || $availableTasks->whereNotIn('id', $exitClearanceRequest->taskAssignments->pluck('task_id'))->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign Additional Tasks</h3>
                    <form action="{{ route('exit-clearance-requests.assign-tasks', $exitClearanceRequest) }}" method="POST">
                        @csrf
                        <div class="space-y-3 mb-4">
                            @foreach($availableTasks->whereNotIn('id', $exitClearanceRequest->taskAssignments->pluck('task_id'))->groupBy('department.name') as $deptName => $tasks)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-2">{{ $deptName }}</h4>
                                    @foreach($tasks as $task)
                                        <div class="flex items-start mb-2">
                                            <input type="checkbox" name="task_ids[]" value="{{ $task->id }}" id="task_{{ $task->id }}" class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label for="task_{{ $task->id }}" class="ml-2 text-sm text-gray-700">
                                                <span class="font-medium">{{ $task->name }}</span>
                                                @if($task->description)
                                                    <span class="block text-gray-500">{{ $task->description }}</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Assign Selected Tasks
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
