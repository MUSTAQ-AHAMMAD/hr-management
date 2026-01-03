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
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Employee -->
                            <div class="md:col-span-2">
                                <x-input-label for="employee_id" :value="__('Employee')" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <select id="employee_id" name="employee_id" class="mt-1 block w-full pl-10 border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm" required onchange="updateEmployeeDetails()">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" 
                                                data-department="{{ $employee->department->name ?? 'N/A' }}"
                                                data-designation="{{ $employee->designation ?? 'N/A' }}"
                                                data-joining-date="{{ $employee->joining_date ? $employee->joining_date->format('F d, Y') : 'Not set' }}"
                                                data-email="{{ $employee->email }}"
                                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->full_name }} ({{ $employee->employee_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('employee_id')" />
                            </div>

                            <!-- Employee Details (Read-only display fields) -->
                            <div id="employee-details" class="md:col-span-2 hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Employee Information
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div>
                                            <p class="text-xs text-gray-600 font-medium">Department</p>
                                            <p id="display-department" class="text-sm text-gray-900 mt-1">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 font-medium">Designation</p>
                                            <p id="display-designation" class="text-sm text-gray-900 mt-1">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 font-medium">Joining Date</p>
                                            <p id="display-joining-date" class="text-sm text-gray-900 mt-1">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 font-medium">Email</p>
                                            <p id="display-email" class="text-sm text-gray-900 mt-1">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Expected Completion Date -->
                            <div>
                                <x-input-label for="expected_completion_date" :value="__('Expected Completion Date')" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <x-text-input id="expected_completion_date" name="expected_completion_date" type="date" class="mt-1 block w-full pl-10" :value="old('expected_completion_date')" required />
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Target date for completing all onboarding tasks</p>
                                <x-input-error class="mt-2" :messages="$errors->get('expected_completion_date')" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Initial Status')" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <select id="status" name="status" class="mt-1 block w-full pl-10 border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm">
                                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Status will be updated as tasks are assigned and completed</p>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Notes (Optional)')" />
                                <div class="relative">
                                    <div class="absolute top-3 left-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <textarea id="notes" name="notes" rows="4" placeholder="Add any special instructions or notes..." class="mt-1 block w-full pl-10 border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                            </div>
                        </div>

                        <!-- Custom Fields Section -->
                        @if(isset($customFields) && $customFields->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                @foreach($customFields as $field)
                                <div>
                                    <x-input-label for="custom_field_{{ $field->id }}" :value="$field->label" />
                                    
                                    @if($field->field_type === 'text')
                                        <x-text-input id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" type="text" class="mt-1 block w-full" :value="old('custom_fields.' . $field->id)" :required="$field->is_required" />
                                    @elseif($field->field_type === 'textarea')
                                        <textarea id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" rows="3" class="mt-1 block w-full border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm" {{ $field->is_required ? 'required' : '' }}>{{ old('custom_fields.' . $field->id) }}</textarea>
                                    @elseif($field->field_type === 'number')
                                        <x-text-input id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" type="number" class="mt-1 block w-full" :value="old('custom_fields.' . $field->id)" :required="$field->is_required" />
                                    @elseif($field->field_type === 'date')
                                        <x-text-input id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" type="date" class="mt-1 block w-full" :value="old('custom_fields.' . $field->id)" :required="$field->is_required" />
                                    @elseif($field->field_type === 'select')
                                        <select id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" class="mt-1 block w-full border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm" {{ $field->is_required ? 'required' : '' }}>
                                            <option value="">Select {{ $field->label }}</option>
                                            @if(is_array($field->options))
                                                @foreach($field->options as $option)
                                                    <option value="{{ $option }}" {{ old('custom_fields.' . $field->id) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    @elseif($field->field_type === 'checkbox')
                                        <label class="flex items-center mt-2">
                                            <input type="checkbox" name="custom_fields[{{ $field->id }}]" value="1" class="rounded border-gray-300 text-cobalt-600 shadow-sm focus:ring-cobalt-500" {{ old('custom_fields.' . $field->id) ? 'checked' : '' }}>
                                            <span class="ms-2 text-sm text-gray-600">{{ $field->label }}</span>
                                        </label>
                                    @endif
                                    
                                    @if($field->help_text)
                                        <p class="mt-1 text-sm text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

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

    <script>
        function updateEmployeeDetails() {
            const select = document.getElementById('employee_id');
            const selectedOption = select.options[select.selectedIndex];
            const detailsDiv = document.getElementById('employee-details');
            
            if (selectedOption.value) {
                // Show the details section
                detailsDiv.classList.remove('hidden');
                
                // Update the display fields
                document.getElementById('display-department').textContent = selectedOption.getAttribute('data-department');
                document.getElementById('display-designation').textContent = selectedOption.getAttribute('data-designation');
                document.getElementById('display-joining-date').textContent = selectedOption.getAttribute('data-joining-date');
                document.getElementById('display-email').textContent = selectedOption.getAttribute('data-email');
            } else {
                // Hide the details section if no employee is selected
                detailsDiv.classList.add('hidden');
            }
        }

        // Set min date for expected completion date to tomorrow
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('expected_completion_date');
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minDate = tomorrow.toISOString().split('T')[0];
            dateInput.setAttribute('min', minDate);
        });
    </script>
</x-app-layout>
