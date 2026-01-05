<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight"><span class="bg-gradient-to-r from-primary-600 to-cobalt-600 bg-clip-text text-transparent">
                {{ __('Onboarding Request #') }}{{ $onboardingRequest->id }}
            </span></h2>
            <div class="flex space-x-2">
                @can('edit onboarding')
                <a href="{{ route('onboarding-requests.edit', $onboardingRequest) }}" class="inline-flex items-center px-4 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Request
                </a>
                @endcan
                <a href="{{ route('onboarding-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Request Details -->
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Employee</p>
                            <p class="text-base font-medium text-gray-900">{{ $onboardingRequest->employee->full_name }}</p>
                            <p class="text-sm text-gray-500">{{ $onboardingRequest->employee->employee_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="text-base font-medium text-gray-900">{{ $onboardingRequest->employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Initiated By</p>
                            <p class="text-base font-medium text-gray-900">{{ $onboardingRequest->initiatedBy->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <div class="mt-1">
                                @if($onboardingRequest->status === 'completed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Completed
                                </span>
                                @elseif($onboardingRequest->status === 'in_progress')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    In Progress
                                </span>
                                @elseif($onboardingRequest->status === 'cancelled')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Cancelled
                                </span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Pending
                                </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Expected Completion Date</p>
                            <p class="text-base font-medium text-gray-900">{{ $onboardingRequest->expected_completion_date?->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Actual Completion Date</p>
                            <p class="text-base font-medium text-gray-900">{{ $onboardingRequest->actual_completion_date?->format('F d, Y') ?? 'Not completed yet' }}</p>
                        </div>
                        @if($onboardingRequest->notes)
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Notes</p>
                            <p class="text-base text-gray-900">{{ $onboardingRequest->notes }}</p>
                        </div>
                        @endif
                        
                        <!-- Custom Fields Display -->
                        @if($onboardingRequest->customFieldValues->count() > 0)
                        @foreach($onboardingRequest->customFieldValues as $fieldValue)
                        <div>
                            <p class="text-sm text-gray-500">{{ $fieldValue->customField->label }}</p>
                            <p class="text-base font-medium text-gray-900">
                                @if($fieldValue->customField->field_type === 'checkbox')
                                    {{ $fieldValue->value ? 'Yes' : 'No' }}
                                @elseif($fieldValue->customField->field_type === 'date')
                                    {{ \Carbon\Carbon::parse($fieldValue->value)->format('F d, Y') }}
                                @else
                                    {{ $fieldValue->value }}
                                @endif
                            </p>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assign Tasks (Only if pending) -->
            @if($onboardingRequest->status === 'pending' && $onboardingRequest->taskAssignments->count() === 0)
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign Tasks</h3>
                    <form action="{{ route('onboarding-requests.assign-tasks', $onboardingRequest) }}" method="POST">
                        @csrf
                        <div class="space-y-2">
                            @php
                                $onboardingTasks = \App\Models\Task::where('type', 'onboarding')->where('is_active', true)->get();
                            @endphp
                            @foreach($onboardingTasks as $task)
                            <label class="flex items-center">
                                <input type="checkbox" name="task_ids[]" value="{{ $task->id }}" class="rounded border-gray-300 text-navy-600 shadow-sm focus:ring-navy-500" checked>
                                <span class="ms-2 text-sm text-gray-700">
                                    <strong>{{ $task->name }}</strong> - {{ $task->description }} ({{ $task->department->name }})
                                </span>
                            </label>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <x-primary-button>
                                {{ __('Assign Selected Tasks') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Assigned Tasks -->
            @if($onboardingRequest->taskAssignments->count() > 0)
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assigned Tasks</h3>
                    <div class="space-y-4">
                        @foreach($onboardingRequest->taskAssignments as $assignment)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $assignment->task->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $assignment->task->description }}</p>
                                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                        <span>Department: {{ $assignment->task->department->name }}</span>
                                        <span>Assigned to: {{ $assignment->assignedTo->name }}</span>
                                        <span>Due: {{ $assignment->due_date?->format('M d, Y') }}</span>
                                    </div>
                                    @if($assignment->notes)
                                    <p class="text-sm text-gray-600 mt-2">Notes: {{ $assignment->notes }}</p>
                                    @endif
                                </div>
                                <div>
                                    @if($assignment->status === 'completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                    @elseif($assignment->status === 'in_progress')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        In Progress
                                    </span>
                                    @elseif($assignment->status === 'rejected')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Pending
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
