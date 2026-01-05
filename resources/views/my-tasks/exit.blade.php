<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center">
            <span class="bg-gradient-to-r from-primary-600 to-cobalt-600 bg-clip-text text-transparent">
                {{ __('My Clearance Tasks') }}
            </span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Summary Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl">
                        <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-semibold text-gray-600">Total Clearance Tasks</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $assignments->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gradient-to-r from-gray-50 to-white">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            Employee ID
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gradient-to-r from-gray-50 to-white">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Name
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gradient-to-r from-gray-50 to-white">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            Department
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gradient-to-r from-gray-50 to-white">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Task Type
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gradient-to-r from-gray-50 to-white">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Status
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider bg-gradient-to-r from-gray-50 to-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($assignments as $assignment)
                                <tr class="hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 transition-all duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $assignment->assignable->employee->employee_code ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gradient-to-br from-orange-100 to-orange-200 shadow-sm">
                                                <span class="text-orange-700 font-bold text-sm">
                                                    {{ substr($assignment->assignable->employee->first_name ?? 'N', 0, 1) }}{{ substr($assignment->assignable->employee->last_name ?? 'A', 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $assignment->assignable->employee->full_name ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $assignment->assignable->employee->department->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $assignment->task->name }}
                                        </div>
                                        @if($assignment->task->description)
                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($assignment->task->description, 40) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($assignment->status === 'completed')
                                        <span class="px-3 py-1.5 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 shadow-sm">
                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Completed
                                        </span>
                                        @elseif($assignment->status === 'in_progress')
                                        <span class="px-3 py-1.5 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 shadow-sm">
                                            <svg class="h-3 w-3 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            In Progress
                                        </span>
                                        @elseif($assignment->status === 'rejected')
                                        <span class="px-3 py-1.5 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gradient-to-r from-red-100 to-red-200 text-red-800 shadow-sm">
                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Rejected
                                        </span>
                                        @else
                                        <span class="px-3 py-1.5 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 shadow-sm">
                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                            Pending
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($assignment->status === 'pending' || $assignment->status === 'in_progress')
                                        <button onclick="toggleTaskDetail('task-{{ $assignment->id }}')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-600 to-cobalt-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wider hover:from-primary-700 hover:to-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Update Task
                                        </button>
                                        @else
                                        <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                </tr>
                                <!-- Task Detail Row (Initially Hidden) -->
                                <tr id="task-{{ $assignment->id }}" class="hidden bg-orange-50">
                                    <td colspan="6" class="px-6 py-4">
                                        <div class="border border-orange-200 rounded-lg p-4 bg-white">
                                            <h4 class="font-semibold text-gray-900 mb-3">Update Task Status</h4>
                                            <form action="{{ route('task-assignments.update-status', $assignment) }}" method="POST">
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
                                                    <div class="flex items-end gap-2">
                                                        <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                                            Update
                                                        </button>
                                                        <button type="button" onclick="toggleTaskDetail('task-{{ $assignment->id }}')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold uppercase hover:bg-gray-300 transition-all duration-200">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-gray-500 text-sm">
                                                No clearance tasks assigned to you.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($assignments->hasPages())
                    <div class="mt-6 border-t border-gray-100 pt-4">
                        {{ $assignments->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTaskDetail(id) {
            const element = document.getElementById(id);
            if (element) {
                element.classList.toggle('hidden');
            }
        }
    </script>
</x-app-layout>
