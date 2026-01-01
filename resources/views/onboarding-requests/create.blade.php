<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Onboarding Request') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('onboarding-requests.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Employee -->
                            <div>
                                <x-input-label for="employee_id" :value="__('Employee')" />
                                <select id="employee_id" name="employee_id" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }} ({{ $employee->employee_code }}) - {{ $employee->department->name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('employee_id')" />
                            </div>

                            <!-- Expected Completion Date -->
                            <div>
                                <x-input-label for="expected_completion_date" :value="__('Expected Completion Date')" />
                                <x-text-input id="expected_completion_date" name="expected_completion_date" type="date" class="mt-1 block w-full" :value="old('expected_completion_date')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('expected_completion_date')" />
                            </div>

                            <!-- Notes -->
                            <div>
                                <x-input-label for="notes" :value="__('Notes (Optional)')" />
                                <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('onboarding-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
