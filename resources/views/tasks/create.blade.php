<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Task') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Task Name')" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full pl-10" :value="old('name')" required autofocus placeholder="Enter task name" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-input-label for="type" :value="__('Task Type')" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <select id="type" name="type" class="mt-1 block w-full pl-10 border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm" required>
                                        <option value="">Select Task Type</option>
                                        <option value="onboarding" {{ old('type') == 'onboarding' ? 'selected' : '' }}>Onboarding</option>
                                        <option value="exit" {{ old('type') == 'exit' ? 'selected' : '' }}>Exit</option>
                                        <option value="training" {{ old('type') == 'training' ? 'selected' : '' }}>Training</option>
                                        <option value="compliance" {{ old('type') == 'compliance' ? 'selected' : '' }}>Compliance</option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                                <p class="mt-1 text-sm text-gray-500">Select the type of task for proper categorization</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Department -->
                            <div>
                                <x-input-label for="department_id" :value="__('Department')" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <select id="department_id" name="department_id" class="mt-1 block w-full pl-10 border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                            </div>

                            <!-- Priority -->
                            <div>
                                <x-input-label for="priority" :value="__('Priority')" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                                        </svg>
                                    </div>
                                    <x-text-input id="priority" name="priority" type="number" min="1" class="mt-1 block w-full pl-10" :value="old('priority', 1)" required />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('priority')" />
                                <p class="mt-1 text-sm text-gray-500">Lower numbers indicate higher priority (1 = highest)</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <x-input-label for="description" :value="__('Description')" />
                            <div class="relative">
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                </div>
                                <textarea id="description" name="description" rows="4" placeholder="Provide detailed instructions for completing this task..." class="mt-1 block w-full pl-10 border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Status -->
                        <div class="mt-6">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-cobalt-600 shadow-sm focus:ring-cobalt-500" @checked(old('is_active', true))>
                                    <span class="ms-3 text-sm">
                                        <span class="font-medium text-gray-900 group-hover:text-cobalt-600">{{ __('Active Task') }}</span>
                                        <span class="block text-xs text-gray-500 mt-0.5">This task will be available for assignment to requests</span>
                                    </span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('is_active')" />
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create Task') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
