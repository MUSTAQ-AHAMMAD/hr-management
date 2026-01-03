<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Tasks') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse($taskAssignments as $assignment)
                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $assignment->task->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $assignment->task->description }}</p>
                                <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-500">
                                    <span>Type: {{ ucfirst($assignment->task->type) }}</span>
                                    <span>Department: {{ $assignment->task->department->name }}</span>
                                    <span>Due: {{ $assignment->due_date?->format('M d, Y') }}</span>
                                    @if($assignment->assignable)
                                    <span>Request: #{{ $assignment->assignable_id }}</span>
                                    @endif
                                </div>
                                @if($assignment->notes)
                                <p class="text-sm text-gray-600 mt-2">Notes: {{ $assignment->notes }}</p>
                                @endif
                            </div>
                            <div class="ml-4">
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
                        
                        @if($assignment->status !== 'completed' && $assignment->status !== 'rejected')
                        <form action="{{ route('task-assignments.update-status', $assignment) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Update Status</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <select name="status" class="block w-full pl-10 border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm text-sm">
                                            <option value="in_progress" {{ $assignment->status === 'in_progress' ? 'selected' : '' }}>Mark In Progress</option>
                                            <option value="completed">Mark Completed</option>
                                            <option value="rejected">Reject Task</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                    <input type="text" name="notes" placeholder="Add notes..." class="block w-full border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm text-sm" value="{{ $assignment->notes }}">
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
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
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks assigned</h3>
                        <p class="mt-1 text-sm text-gray-500">You don't have any tasks assigned to you yet.</p>
                    </div>
                    @endforelse

                    @if($taskAssignments->hasPages())
                    <div class="mt-4">
                        {{ $taskAssignments->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
