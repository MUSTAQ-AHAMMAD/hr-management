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
                                <select id="employee_id" name="employee_id" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" data-assets="{{ $employee->assets->count() }}">
                                            {{ $employee->full_name }} ({{ $employee->employee_code }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('employee_id')" />
                                <p class="mt-1 text-sm text-gray-500" id="asset-info"></p>
                            </div>

                            <div>
                                <x-input-label for="exit_date" :value="__('Exit Date')" />
                                <x-text-input id="exit_date" name="exit_date" type="date" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('exit_date')" />
                            </div>

                            <div>
                                <x-input-label for="reason" :value="__('Reason (Optional)')" />
                                <textarea id="reason" name="reason" rows="4" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"></textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('reason')" />
                            </div>

                            <div>
                                <x-input-label :value="__('Select Departments for Clearance')" class="mb-2" />
                                <div class="space-y-2 border border-gray-300 rounded-md p-4">
                                    @foreach($departments as $department)
                                        <div class="flex items-start">
                                            <input type="checkbox" name="department_ids[]" value="{{ $department->id }}" id="dept_{{ $department->id }}" class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label for="dept_{{ $department->id }}" class="ml-2 text-sm">
                                                <span class="font-medium text-gray-900">{{ $department->name }}</span>
                                                @if($department->description)
                                                    <span class="block text-gray-500">{{ $department->description }}</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Select departments that need to clear the employee. Tasks will be auto-assigned to selected departments.</p>
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
