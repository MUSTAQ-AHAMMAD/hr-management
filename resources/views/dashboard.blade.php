<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Employees -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-navy-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Employees</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_employees'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Employees -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-cobalt-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-cobalt-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Active Employees</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_employees'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Onboarding -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-primary-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending Onboarding</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_onboarding'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Exit Clearance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-navy-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending Exit Clearance</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_exit_clearance'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Tasks -->
            @if($myTasks->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">My Pending Tasks</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($myTasks as $taskAssignment)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $taskAssignment->task->name }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $taskAssignment->task->type === 'onboarding' ? 'bg-cobalt-100 text-cobalt-800' : 'bg-navy-100 text-navy-800' }}">
                                            {{ ucfirst($taskAssignment->task->type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $taskAssignment->status === 'pending' ? 'bg-primary-100 text-primary-800' : 
                                               ($taskAssignment->status === 'in_progress' ? 'bg-cobalt-100 text-cobalt-800' : 'bg-navy-100 text-navy-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $taskAssignment->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                        {{ $taskAssignment->due_date ? $taskAssignment->due_date->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('my-tasks') }}" class="text-cobalt-600 hover:text-cobalt-800 text-sm font-medium transition-colors duration-150">
                            View All Tasks →
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Onboarding -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Onboarding Requests</h3>
                        @if($recentOnboarding->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentOnboarding as $request)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $request->employee->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $request->status === 'completed' ? 'bg-navy-100 text-navy-800' : 
                                       ($request->status === 'in_progress' ? 'bg-cobalt-100 text-cobalt-800' : 'bg-primary-100 text-primary-800') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('onboarding-requests.index') }}" class="text-cobalt-600 hover:text-cobalt-800 text-sm font-medium transition-colors duration-150">
                                View All →
                            </a>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">No onboarding requests yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Exit Clearance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Exit Clearance Requests</h3>
                        @if($recentExitClearance->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentExitClearance as $request)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $request->employee->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $request->status === 'cleared' ? 'bg-navy-100 text-navy-800' : 
                                       ($request->status === 'in_progress' ? 'bg-cobalt-100 text-cobalt-800' : 'bg-primary-100 text-primary-800') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('exit-clearance-requests.index') }}" class="text-cobalt-600 hover:text-cobalt-800 text-sm font-medium transition-colors duration-150">
                                View All →
                            </a>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">No exit clearance requests yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
