<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Exit Clearance Request') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
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
                                                {{ $employee->full_name }} ({{ $employee->employee_code }})
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
    </script>
</x-app-layout>
