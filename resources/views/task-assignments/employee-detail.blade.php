<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('task-assignments.by-employee') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Task Assignments for {{ $employee->full_name }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Employee ID: {{ $employee->employee_code }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg shadow-sm mb-4 flex items-start" role="alert">
                <svg class="h-5 w-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg shadow-sm mb-4 flex items-start" role="alert">
                <svg class="h-5 w-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <!-- Employee Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-gradient-to-r from-cobalt-50 to-blue-50">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-wider">Employee ID</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $employee->employee_code }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-wider">Full Name</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $employee->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-wider">Department</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-wider">Status</p>
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($employee->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onboarding Tasks Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 bg-purple-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Onboarding Tasks</h3>
                        </div>
                        <span class="px-3 py-1 bg-purple-200 text-purple-800 rounded-full text-sm font-medium">
                            {{ $onboardingAssignments->count() }} tasks
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($onboardingAssignments as $assignment)
                    <div class="border border-purple-200 rounded-lg p-4 mb-4 bg-purple-50">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-lg">{{ $assignment->task->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $assignment->task->description }}</p>
                            </div>
                            <div class="ml-4">
                                @if($assignment->status === 'completed')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Completed
                                </span>
                                @elseif($assignment->status === 'in_progress')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    In Progress
                                </span>
                                @elseif($assignment->status === 'rejected')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Rejected
                                </span>
                                @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Pending
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm mb-3">
                            <div class="bg-white p-2 rounded border border-purple-200">
                                <span class="text-gray-500 text-xs">Department:</span>
                                <p class="font-medium text-gray-900">{{ $assignment->task->department->name }}</p>
                            </div>
                            <div class="bg-white p-2 rounded border border-purple-200">
                                <span class="text-gray-500 text-xs">Assigned To:</span>
                                <p class="font-medium text-gray-900">{{ $assignment->assignedTo->name }}</p>
                            </div>
                            <div class="bg-white p-2 rounded border border-purple-200">
                                <span class="text-gray-500 text-xs">Due Date:</span>
                                <p class="font-medium text-gray-900">{{ $assignment->due_date?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-white p-2 rounded border border-purple-200">
                                <span class="text-gray-500 text-xs">Completed:</span>
                                <p class="font-medium text-gray-900">{{ $assignment->completed_date?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @if($assignment->notes)
                        <div class="mt-3 p-2 bg-white rounded border border-purple-200">
                            <span class="text-xs text-gray-500 font-semibold">Notes:</span>
                            <p class="text-sm text-gray-700 mt-1">{{ $assignment->notes }}</p>
                        </div>
                        @endif

                        @if($assignment->rejection_reason)
                        <div class="mt-3 p-2 bg-red-50 rounded border border-red-200">
                            <span class="text-xs text-red-700 font-semibold">Rejection Reason:</span>
                            <p class="text-sm text-red-900 mt-1">{{ $assignment->rejection_reason }}</p>
                        </div>
                        @endif

                        @if($assignment->status !== 'completed' && $assignment->status !== 'rejected')
                        <form action="{{ route('task-assignments.update-status', $assignment) }}" method="POST" class="mt-4 pt-4 border-t border-purple-200">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Update Status</label>
                                    <select name="status" class="block w-full border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm text-sm">
                                        <option value="in_progress" {{ $assignment->status === 'in_progress' ? 'selected' : '' }}>Mark In Progress</option>
                                        <option value="completed">Mark Completed</option>
                                        <option value="rejected">Reject Task</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                    <input type="text" name="notes" placeholder="Add notes..." class="block w-full border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm text-sm" value="{{ $assignment->notes }}">
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Update Status
                                    </button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                    @empty
                    <div class="text-center text-gray-500 py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No onboarding tasks</h3>
                        <p class="mt-1 text-sm text-gray-500">This employee has no onboarding tasks assigned.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Exit Clearance Tasks Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 bg-orange-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Exit Clearance Tasks</h3>
                        </div>
                        <span class="px-3 py-1 bg-orange-200 text-orange-800 rounded-full text-sm font-medium">
                            {{ $exitAssignments->count() }} tasks
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($exitAssignments as $assignment)
                    <div class="border border-orange-200 rounded-lg p-4 mb-4 bg-orange-50">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-lg">{{ $assignment->task->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $assignment->task->description }}</p>
                            </div>
                            <div class="ml-4">
                                @if($assignment->status === 'completed')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Completed
                                </span>
                                @elseif($assignment->status === 'in_progress')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    In Progress
                                </span>
                                @elseif($assignment->status === 'rejected')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Rejected
                                </span>
                                @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Pending
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm mb-3">
                            <div class="bg-white p-2 rounded border border-orange-200">
                                <span class="text-gray-500 text-xs">Department:</span>
                                <p class="font-medium text-gray-900">{{ $assignment->task->department->name }}</p>
                            </div>
                            <div class="bg-white p-2 rounded border border-orange-200">
                                <span class="text-gray-500 text-xs">Assigned To:</span>
                                <p class="font-medium text-gray-900">{{ $assignment->assignedTo->name }}</p>
                            </div>
                            <div class="bg-white p-2 rounded border border-orange-200">
                                <span class="text-gray-500 text-xs">Due Date:</span>
                                <p class="font-medium text-gray-900">{{ $assignment->due_date?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-white p-2 rounded border border-orange-200">
                                <span class="text-gray-500 text-xs">Completed:</span>
                                <p class="font-medium text-gray-900">{{ $assignment->completed_date?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @if($assignment->notes)
                        <div class="mt-3 p-2 bg-white rounded border border-orange-200">
                            <span class="text-xs text-gray-500 font-semibold">Notes:</span>
                            <p class="text-sm text-gray-700 mt-1">{{ $assignment->notes }}</p>
                        </div>
                        @endif

                        @if($assignment->rejection_reason)
                        <div class="mt-3 p-2 bg-red-50 rounded border border-red-200">
                            <span class="text-xs text-red-700 font-semibold">Rejection Reason:</span>
                            <p class="text-sm text-red-900 mt-1">{{ $assignment->rejection_reason }}</p>
                        </div>
                        @endif

                        @if($assignment->status !== 'completed' && $assignment->status !== 'rejected')
                        <form action="{{ route('task-assignments.update-status', $assignment) }}" method="POST" class="mt-4 pt-4 border-t border-orange-200">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Update Status</label>
                                    <select name="status" class="block w-full border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm text-sm">
                                        <option value="in_progress" {{ $assignment->status === 'in_progress' ? 'selected' : '' }}>Mark In Progress</option>
                                        <option value="completed">Mark Completed</option>
                                        <option value="rejected">Reject Task</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                    <input type="text" name="notes" placeholder="Add notes..." class="block w-full border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm text-sm" value="{{ $assignment->notes }}">
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Update Status
                                    </button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                    @empty
                    <div class="text-center text-gray-500 py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No exit clearance tasks</h3>
                        <p class="mt-1 text-sm text-gray-500">This employee has no exit clearance tasks assigned.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @if($onboardingAssignments->count() === 0 && $exitAssignments->count() === 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-center text-gray-500">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No tasks assigned</h3>
                    <p class="mt-2 text-sm text-gray-500">This employee has no tasks assigned yet.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
