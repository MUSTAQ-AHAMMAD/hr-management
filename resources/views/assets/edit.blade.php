<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight"><span class="bg-gradient-to-r from-primary-600 to-cobalt-600 bg-clip-text text-transparent">
            {{ __('Edit Asset') }}
        </span></h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <form action="{{ route('assets.update', $asset) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <x-input-label for="employee_id" :value="__('Employee')" />
                                <select id="employee_id" name="employee_id" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id', $asset->employee_id) == $employee->id ? 'selected' : '' }}>
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
                                    <option value="Laptop" {{ old('asset_type', $asset->asset_type) == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                    <option value="Desktop" {{ old('asset_type', $asset->asset_type) == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                                    <option value="Mobile Phone" {{ old('asset_type', $asset->asset_type) == 'Mobile Phone' ? 'selected' : '' }}>Mobile Phone</option>
                                    <option value="SIM Card" {{ old('asset_type', $asset->asset_type) == 'SIM Card' ? 'selected' : '' }}>SIM Card</option>
                                    <option value="ID Card" {{ old('asset_type', $asset->asset_type) == 'ID Card' ? 'selected' : '' }}>ID Card</option>
                                    <option value="Access Card" {{ old('asset_type', $asset->asset_type) == 'Access Card' ? 'selected' : '' }}>Access Card</option>
                                    <option value="Keyboard" {{ old('asset_type', $asset->asset_type) == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                                    <option value="Mouse" {{ old('asset_type', $asset->asset_type) == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                                    <option value="Monitor" {{ old('asset_type', $asset->asset_type) == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                    <option value="Other" {{ old('asset_type', $asset->asset_type) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('asset_type')" />
                            </div>

                            <div>
                                <x-input-label for="asset_name" :value="__('Asset Name')" />
                                <x-text-input id="asset_name" name="asset_name" type="text" class="mt-1 block w-full" :value="old('asset_name', $asset->asset_name)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('asset_name')" />
                            </div>

                            <div>
                                <x-input-label for="serial_number" :value="__('Serial Number (Optional)')" />
                                <x-text-input id="serial_number" name="serial_number" type="text" class="mt-1 block w-full" :value="old('serial_number', $asset->serial_number)" />
                                <x-input-error class="mt-2" :messages="$errors->get('serial_number')" />
                            </div>

                            <div>
                                <x-input-label for="asset_value" :value="__('Asset Value (Optional)')" />
                                <x-text-input id="asset_value" name="asset_value" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('asset_value', $asset->asset_value)" />
                                <x-input-error class="mt-2" :messages="$errors->get('asset_value')" />
                            </div>

                            <div>
                                <x-input-label for="purchase_date" :value="__('Purchase Date (Optional)')" />
                                <x-text-input id="purchase_date" name="purchase_date" type="date" class="mt-1 block w-full" :value="old('purchase_date', $asset->purchase_date?->format('Y-m-d'))" />
                                <x-input-error class="mt-2" :messages="$errors->get('purchase_date')" />
                            </div>

                            <div>
                                <x-input-label for="condition" :value="__('Condition')" />
                                <select id="condition" name="condition" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                    <option value="new" {{ old('condition', $asset->condition) == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="good" {{ old('condition', $asset->condition) == 'good' ? 'selected' : '' }}>Good</option>
                                    <option value="fair" {{ old('condition', $asset->condition) == 'fair' ? 'selected' : '' }}>Fair</option>
                                    <option value="poor" {{ old('condition', $asset->condition) == 'poor' ? 'selected' : '' }}>Poor</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('condition')" />
                            </div>

                            <div>
                                <x-input-label for="warranty_period" :value="__('Warranty Period (Optional)')" />
                                <x-text-input id="warranty_period" name="warranty_period" type="text" class="mt-1 block w-full" :value="old('warranty_period', $asset->warranty_period)" />
                                <x-input-error class="mt-2" :messages="$errors->get('warranty_period')" />
                            </div>

                            <div>
                                <x-input-label for="warranty_expiry" :value="__('Warranty Expiry (Optional)')" />
                                <x-text-input id="warranty_expiry" name="warranty_expiry" type="date" class="mt-1 block w-full" :value="old('warranty_expiry', $asset->warranty_expiry?->format('Y-m-d'))" />
                                <x-input-error class="mt-2" :messages="$errors->get('warranty_expiry')" />
                            </div>

                            <div>
                                <x-input-label for="assigned_date" :value="__('Assigned Date')" />
                                <x-text-input id="assigned_date" name="assigned_date" type="date" class="mt-1 block w-full" :value="old('assigned_date', $asset->assigned_date?->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('assigned_date')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                                    <option value="assigned" {{ old('status', $asset->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                    <option value="returned" {{ old('status', $asset->status) == 'returned' ? 'selected' : '' }}>Returned</option>
                                    <option value="damaged" {{ old('status', $asset->status) == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                    <option value="lost" {{ old('status', $asset->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            @if($asset->status === 'returned')
                            <div>
                                <x-input-label for="return_date" :value="__('Return Date')" />
                                <x-text-input id="return_date" name="return_date" type="date" class="mt-1 block w-full" :value="old('return_date', $asset->return_date?->format('Y-m-d'))" />
                                <x-input-error class="mt-2" :messages="$errors->get('return_date')" />
                            </div>
                            @endif

                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description (Optional)')" />
                                <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">{{ old('description', $asset->description) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            @if(in_array($asset->status, ['returned', 'damaged', 'lost']))
                            <div class="md:col-span-2">
                                <x-input-label for="return_notes" :value="__('Return Notes')" />
                                <textarea id="return_notes" name="return_notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">{{ old('return_notes', $asset->return_notes) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('return_notes')" />
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('assets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Asset') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
