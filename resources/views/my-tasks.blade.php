<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Tasks') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Onboarding Tasks Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 bg-blue-50">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Onboarding Tasks</h3>
                        <span class="ml-auto px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-sm font-medium">
                            {{ $onboardingTasks->count() }} tasks
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($onboardingTasks as $assignment)
                    <div class="border border-blue-200 rounded-lg p-4 mb-4 bg-blue-50">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $assignment->task->name }}</h4>
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
                                <p class="text-sm text-gray-600 mb-3">{{ $assignment->task->description }}</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                    <div class="bg-white p-2 rounded border border-blue-200">
                                        <span class="text-gray-500 text-xs">Employee ID:</span>
                                        <p class="font-medium text-gray-900">{{ $assignment->assignable->employee->employee_code ?? 'N/A' }}</p>
                                    </div>
                                    <div class="bg-white p-2 rounded border border-blue-200">
                                        <span class="text-gray-500 text-xs">Employee:</span>
                                        <p class="font-medium text-gray-900">{{ $assignment->assignable->employee->full_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="bg-white p-2 rounded border border-blue-200">
                                        <span class="text-gray-500 text-xs">Request:</span>
                                        <p class="font-medium text-gray-900">#{{ $assignment->assignable_id }}</p>
                                    </div>
                                    <div class="bg-white p-2 rounded border border-blue-200">
                                        <span class="text-gray-500 text-xs">Due Date:</span>
                                        <p class="font-medium text-gray-900">{{ $assignment->due_date?->format('M d, Y') ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                @if($assignment->notes)
                                <div class="mt-3 p-2 bg-white rounded border border-blue-200">
                                    <span class="text-xs text-gray-500">Notes:</span>
                                    <p class="text-sm text-gray-700">{{ $assignment->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($assignment->status !== 'completed' && $assignment->status !== 'rejected')
                        <form action="{{ route('task-assignments.update-status', $assignment) }}" method="POST" class="mt-4 pt-4 border-t border-blue-200">
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
                        <p class="mt-1 text-sm text-gray-500">You don't have any onboarding tasks assigned to you.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Exit Clearance Tasks Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 bg-orange-50">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Exit Clearance Tasks</h3>
                        <span class="ml-auto px-3 py-1 bg-orange-200 text-orange-800 rounded-full text-sm font-medium">
                            {{ $exitTasks->count() }} tasks
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($exitTasks as $assignment)
                    <div class="border border-orange-200 rounded-lg p-4 mb-4 bg-orange-50">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $assignment->task->name }}</h4>
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
                                <p class="text-sm text-gray-600 mb-3">{{ $assignment->task->description }}</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                    <div class="bg-white p-2 rounded border border-orange-200">
                                        <span class="text-gray-500 text-xs">Employee ID:</span>
                                        <p class="font-medium text-gray-900">{{ $assignment->assignable->employee->employee_code ?? 'N/A' }}</p>
                                    </div>
                                    <div class="bg-white p-2 rounded border border-orange-200">
                                        <span class="text-gray-500 text-xs">Employee:</span>
                                        <p class="font-medium text-gray-900">{{ $assignment->assignable->employee->full_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="bg-white p-2 rounded border border-orange-200">
                                        <span class="text-gray-500 text-xs">Request:</span>
                                        <p class="font-medium text-gray-900">#{{ $assignment->assignable_id }}</p>
                                    </div>
                                    <div class="bg-white p-2 rounded border border-orange-200">
                                        <span class="text-gray-500 text-xs">Exit Date:</span>
                                        <p class="font-medium text-gray-900">{{ $assignment->due_date?->format('M d, Y') ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                @if($assignment->notes)
                                <div class="mt-3 p-2 bg-white rounded border border-orange-200">
                                    <span class="text-xs text-gray-500">Notes:</span>
                                    <p class="text-sm text-gray-700">{{ $assignment->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
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
                        <p class="mt-1 text-sm text-gray-500">You don't have any exit clearance tasks assigned to you.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @if($onboardingTasks->count() === 0 && $exitTasks->count() === 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No tasks assigned</h3>
                    <p class="mt-2 text-sm text-gray-500">You don't have any tasks assigned to you at the moment.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
