<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight"><span class="bg-gradient-to-r from-primary-600 to-cobalt-600 bg-clip-text text-transparent">
            {{ __('Create Onboarding Request') }}
        </span></h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
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
                                                data-has-login="{{ $employee->user_id ? 'Yes' : 'No' }}"
                                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->employee_code }} - {{ $employee->full_name }}
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
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
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
                                        <div>
                                            <p class="text-xs text-gray-600 font-medium">Has Login</p>
                                            <p id="display-has-login" class="text-sm text-gray-900 mt-1">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Create Login Checkbox -->
                            <div class="md:col-span-2" id="create-login-section" style="display: none;">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <label class="flex items-start cursor-pointer">
                                        <input type="checkbox" name="create_login" id="create_login" value="1" class="mt-1 rounded border-gray-300 text-cobalt-600 focus:ring-cobalt-500">
                                        <div class="ml-3">
                                            <span class="font-medium text-gray-900">Create Employee Login Account</span>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Check this box to create a login account for the employee. The account will be created with email: <span id="login-email" class="font-medium"></span> and default password: <span class="font-mono bg-gray-200 px-1 rounded">password</span>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Note: The employee should change this password on first login.
                                            </p>
                                        </div>
                                    </label>
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

                        <!-- Department and Task Selection Section -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <svg class="h-5 w-5 text-cobalt-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Select Departments & Tasks for Onboarding
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">Choose which departments will be involved in the onboarding process and select their specific tasks.</p>
                            </div>

                            <div class="space-y-4">
                                @foreach($departments as $department)
                                    @php
                                        $departmentTasks = $onboardingTasks->where('department_id', $department->id);
                                    @endphp
                                    @if($departmentTasks->count() > 0)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 hover:border-cobalt-300 transition-all duration-150">
                                        <div class="flex items-start mb-3">
                                            <input type="checkbox" 
                                                name="department_ids[]" 
                                                value="{{ $department->id }}" 
                                                id="dept_{{ $department->id }}" 
                                                class="mt-1 rounded border-gray-300 text-cobalt-600 focus:ring-cobalt-500 department-checkbox"
                                                data-department-id="{{ $department->id }}"
                                                onchange="toggleDepartmentTasks({{ $department->id }})">
                                            <label for="dept_{{ $department->id }}" class="ml-3 flex-1 cursor-pointer">
                                                <span class="font-semibold text-gray-900 flex items-center">
                                                    <svg class="h-4 w-4 mr-2 text-cobalt-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    {{ $department->name }}
                                                </span>
                                                @if($department->description)
                                                    <span class="block text-gray-500 text-sm mt-1">{{ $department->description }}</span>
                                                @endif
                                            </label>
                                        </div>

                                        <!-- Department Tasks -->
                                        <div id="tasks_{{ $department->id }}" class="ml-7 space-y-2 hidden">
                                            <p class="text-xs text-gray-600 font-medium mb-2">Select tasks for this department:</p>
                                            @foreach($departmentTasks as $task)
                                            <label class="flex items-start p-2 bg-white rounded border border-gray-200 hover:bg-blue-50 cursor-pointer">
                                                <input type="checkbox" 
                                                    name="task_ids[]" 
                                                    value="{{ $task->id }}" 
                                                    class="mt-1 rounded border-gray-300 text-navy-600 focus:ring-navy-500 task-checkbox"
                                                    data-department-id="{{ $department->id }}"
                                                    checked>
                                                <span class="ml-2 text-sm flex-1">
                                                    <strong class="text-gray-900">{{ $task->name }}</strong>
                                                    <span class="text-gray-600"> - {{ $task->description }}</span>
                                                    @if($task->priority)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                                            Priority {{ $task->priority }}
                                                        </span>
                                                    @endif
                                                </span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-900 flex items-start">
                                    <svg class="h-5 w-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Check the departments to include in the onboarding process. Tasks are automatically selected but can be unchecked if not needed. Tasks will be assigned to department users upon creation.</span>
                                </p>
                            </div>
                            
                            <x-input-error class="mt-2" :messages="$errors->get('department_ids')" />
                            <x-input-error class="mt-2" :messages="$errors->get('task_ids')" />
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
            const createLoginSection = document.getElementById('create-login-section');
            const createLoginCheckbox = document.getElementById('create_login');
            
            if (selectedOption.value) {
                // Show the details section
                detailsDiv.classList.remove('hidden');
                
                // Update the display fields
                document.getElementById('display-department').textContent = selectedOption.getAttribute('data-department');
                document.getElementById('display-designation').textContent = selectedOption.getAttribute('data-designation');
                document.getElementById('display-joining-date').textContent = selectedOption.getAttribute('data-joining-date');
                document.getElementById('display-email').textContent = selectedOption.getAttribute('data-email');
                document.getElementById('display-has-login').textContent = selectedOption.getAttribute('data-has-login');
                
                // Show/hide create login section based on whether employee has login
                const hasLogin = selectedOption.getAttribute('data-has-login');
                if (hasLogin === 'No') {
                    createLoginSection.style.display = 'block';
                    document.getElementById('login-email').textContent = selectedOption.getAttribute('data-email');
                } else {
                    createLoginSection.style.display = 'none';
                    createLoginCheckbox.checked = false;
                }
            } else {
                // Hide the details section if no employee is selected
                detailsDiv.classList.add('hidden');
                createLoginSection.style.display = 'none';
            }
        }

        function toggleDepartmentTasks(departmentId) {
            const departmentCheckbox = document.getElementById('dept_' + departmentId);
            const tasksDiv = document.getElementById('tasks_' + departmentId);
            const taskCheckboxes = tasksDiv.querySelectorAll('.task-checkbox');
            
            if (departmentCheckbox.checked) {
                // Show tasks section
                tasksDiv.classList.remove('hidden');
                // Check all tasks by default
                taskCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            } else {
                // Hide tasks section
                tasksDiv.classList.add('hidden');
                // Uncheck all tasks
                taskCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        }

        // Set min date for expected completion date to tomorrow
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('expected_completion_date');
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minDate = tomorrow.toISOString().split('T')[0];
            dateInput.setAttribute('min', minDate);

            // Initialize department checkboxes based on old input or default
            document.querySelectorAll('.department-checkbox').forEach(checkbox => {
                if (checkbox.checked) {
                    toggleDepartmentTasks(checkbox.dataset.departmentId);
                }
            });
        });
    </script>
</x-app-layout>
