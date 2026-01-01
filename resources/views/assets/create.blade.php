<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign New Asset') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('assets.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <x-input-label for="employee_id" :value="__('Employee')" />
                                <select id="employee_id" name="employee_id" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }} ({{ $employee->employee_code }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('employee_id')" />
                            </div>

                            <div>
                                <x-input-label for="asset_type" :value="__('Asset Type')" />
                                <select id="asset_type" name="asset_type" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                                    <option value="">Select Type</option>
                                    <option value="Laptop" {{ old('asset_type') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                    <option value="Desktop" {{ old('asset_type') == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                                    <option value="Mobile Phone" {{ old('asset_type') == 'Mobile Phone' ? 'selected' : '' }}>Mobile Phone</option>
                                    <option value="SIM Card" {{ old('asset_type') == 'SIM Card' ? 'selected' : '' }}>SIM Card</option>
                                    <option value="ID Card" {{ old('asset_type') == 'ID Card' ? 'selected' : '' }}>ID Card</option>
                                    <option value="Access Card" {{ old('asset_type') == 'Access Card' ? 'selected' : '' }}>Access Card</option>
                                    <option value="Keyboard" {{ old('asset_type') == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                                    <option value="Mouse" {{ old('asset_type') == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                                    <option value="Monitor" {{ old('asset_type') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                    <option value="Other" {{ old('asset_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('asset_type')" />
                            </div>

                            <div>
                                <x-input-label for="asset_name" :value="__('Asset Name')" />
                                <x-text-input id="asset_name" name="asset_name" type="text" class="mt-1 block w-full" :value="old('asset_name')" required placeholder="e.g., Dell Latitude 5420" />
                                <x-input-error class="mt-2" :messages="$errors->get('asset_name')" />
                            </div>

                            <div>
                                <x-input-label for="serial_number" :value="__('Serial Number (Optional)')" />
                                <x-text-input id="serial_number" name="serial_number" type="text" class="mt-1 block w-full" :value="old('serial_number')" placeholder="e.g., SN123456789" />
                                <x-input-error class="mt-2" :messages="$errors->get('serial_number')" />
                            </div>

                            <div>
                                <x-input-label for="assigned_date" :value="__('Assigned Date')" />
                                <x-text-input id="assigned_date" name="assigned_date" type="date" class="mt-1 block w-full" :value="old('assigned_date', date('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('assigned_date')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description (Optional)')" />
                                <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" placeholder="Additional details about the asset...">{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('assets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Assign Asset') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
