<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight"><span class="bg-gradient-to-r from-primary-600 to-cobalt-600 bg-clip-text text-transparent">
            {{ __('Create Exit Clearance Request') }}
        </span></h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <form action="{{ route('exit-clearance-requests.store') }}" method="POST" id="exitForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="employee_id" :value="__('Employee')" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                     <select id="employee_id" name="employee_id" class="mt-1 block w-full pl-10 border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" data-assets="{{ $employee->assets->count() }}">
                                                {{ $employee->employee_code }} - {{ $employee->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('employee_id')" />
                                <p class="mt-1 text-sm text-gray-500" id="asset-info"></p>
                            </div>

                            <div>
                                <x-input-label for="exit_date" :value="__('Exit Date')" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <x-text-input id="exit_date" name="exit_date" type="date" class="mt-1 block w-full pl-10" required />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('exit_date')" />
                            </div>

                            <div>
                                <x-input-label for="reason" :value="__('Reason (Optional)')" />
                                <div class="relative">
                                    <div class="absolute top-3 left-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <textarea id="reason" name="reason" rows="4" placeholder="Enter reason for exit..." class="mt-1 block w-full pl-10 border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm"></textarea>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('reason')" />
                            </div>

                            <!-- Line Manager -->
                            <div>
                                <x-input-label for="line_manager_id" :value="__('Line Manager')" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <select id="line_manager_id" name="line_manager_id" class="mt-1 block w-full pl-10 border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm" required onchange="updateLineManagerEmail()">
                                        <option value="">Select Line Manager</option>
                                        @php
                                            $managers = \App\Models\User::whereHas('roles', function($q) {
                                                $q->whereIn('name', ['Admin', 'Super Admin', 'Department User']);
                                            })->orderBy('name')->get();
                                        @endphp
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}" data-email="{{ $manager->email }}" {{ old('line_manager_id') == $manager->id ? 'selected' : '' }}>
                                                {{ $manager->name }} ({{ $manager->department->name ?? 'No Dept' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Line manager must approve before HR can assign clearance tasks</p>
                                <x-input-error class="mt-2" :messages="$errors->get('line_manager_id')" />
                            </div>

                            <!-- Line Manager Email -->
                            <div>
                                <x-input-label for="line_manager_email" :value="__('Line Manager Email')" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <x-text-input id="line_manager_email" name="line_manager_email" type="email" class="mt-1 block w-full pl-10" :value="old('line_manager_email')" placeholder="manager@company.com" required readonly />
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Auto-filled from selected line manager</p>
                                <x-input-error class="mt-2" :messages="$errors->get('line_manager_email')" />
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1 md:flex md:justify-between">
                                            <p class="text-sm text-blue-700">
                                                <strong>Important:</strong> The line manager will receive an email notification to approve this exit clearance request. Only after approval can departments be assigned for clearance.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <x-input-label :value="__('Select Departments for Clearance')" class="mb-2" />
                                <div class="space-y-2 border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    @foreach($departments as $department)
                                        <div class="flex items-start p-3 bg-white rounded-lg border border-gray-200 hover:border-cobalt-300 hover:shadow-sm transition-all duration-150">
                                            <input type="checkbox" name="department_ids[]" value="{{ $department->id }}" id="dept_{{ $department->id }}" class="mt-1 rounded border-gray-300 text-cobalt-600 focus:ring-cobalt-500">
                                            <label for="dept_{{ $department->id }}" class="ml-3 text-sm cursor-pointer flex-1">
                                                <span class="font-semibold text-gray-900 flex items-center">
                                                    <svg class="h-4 w-4 mr-2 text-cobalt-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    {{ $department->name }}
                                                </span>
                                                @if($department->description)
                                                    <span class="block text-gray-500 mt-1">{{ $department->description }}</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-sm text-gray-600 flex items-start">
                                    <svg class="h-5 w-5 text-cobalt-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Select departments that need to clear the employee. Tasks will be auto-assigned to selected departments.</span>
                                </p>
                                <x-input-error class="mt-2" :messages="$errors->get('department_ids')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('exit-clearance-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
        document.getElementById('employee_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const assetCount = selectedOption.getAttribute('data-assets');
            const assetInfo = document.getElementById('asset-info');
            
            if (assetCount && assetCount > 0) {
                assetInfo.textContent = `⚠️ This employee has ${assetCount} assigned asset(s) that need to be returned.`;
                assetInfo.classList.add('text-orange-600', 'font-medium');
            } else {
                assetInfo.textContent = '';
                assetInfo.classList.remove('text-orange-600', 'font-medium');
            }
        });

        function updateLineManagerEmail() {
            const select = document.getElementById('line_manager_id');
            const selectedOption = select.options[select.selectedIndex];
            const emailInput = document.getElementById('line_manager_email');
            
            if (selectedOption.value) {
                emailInput.value = selectedOption.getAttribute('data-email') || '';
            } else {
                emailInput.value = '';
            }
        }
    </script>
</x-app-layout>
