<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Email ID for Employee') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Information Banner -->
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 px-6 py-4 rounded-lg mb-6 shadow-sm">
                <div class="flex items-start">
                    <svg class="h-6 w-6 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold mb-1">IT Team - Email Creation Task</h3>
                        <p class="text-sm">You are creating an email ID for this employee. Once you save the email, the HR team will be automatically notified that the employee is ready for onboarding.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Employee Information (Read-only) -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Employee Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Employee Code</p>
                                <p class="text-base font-medium text-gray-900">{{ $employee->employee_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Full Name</p>
                                <p class="text-base font-medium text-gray-900">{{ $employee->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Department</p>
                                <p class="text-base font-medium text-gray-900">{{ $employee->department->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Designation</p>
                                <p class="text-base font-medium text-gray-900">{{ $employee->designation }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Phone</p>
                                <p class="text-base font-medium text-gray-900">{{ $employee->phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Joining Date</p>
                                <p class="text-base font-medium text-gray-900">{{ $employee->joining_date?->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Form -->
                    <form action="{{ route('employees.update-email', $employee) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-6">
                            <x-input-label for="email" :value="__('Email ID')" />
                            <x-text-input 
                                id="email" 
                                name="email" 
                                type="email" 
                                class="mt-1 block w-full" 
                                :value="old('email')" 
                                required 
                                autofocus 
                                placeholder="e.g., {{ strtolower(str_replace(' ', '.', $employee->full_name)) }}@yourcompany.com"
                            />
                            <p class="mt-1 text-sm text-gray-500">Enter the company email address you've created for this employee</p>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create Email & Notify HR') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
