<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight"><span class="bg-gradient-to-r from-primary-600 to-cobalt-600 bg-clip-text text-transparent">
                {{ __('Task Details') }}
            </span></h2>
            <div class="flex space-x-2">
                @can('edit tasks')
                <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 active:bg-cobalt-900 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit
                </a>
                @endcan
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white border-2 border-gray-300 rounded-xl font-semibold text-sm text-gray-700 uppercase tracking-wider hover:bg-gray-50 hover:border-gray-400 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm hover:shadow transition-all duration-200">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100 mb-6">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Task Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Name</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $task->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Type</p>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-cobalt-100 text-cobalt-800">
                                    {{ ucfirst($task->type) }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Department</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $task->department->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Priority</p>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $task->priority }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <p class="mt-1">
                                @if($task->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Assignments</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $task->taskAssignments->count() }}</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">Description</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $task->description ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
