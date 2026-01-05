<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight"><span class="bg-gradient-to-r from-primary-600 to-cobalt-600 bg-clip-text text-transparent">
            {{ __('Edit Custom Field') }}
        </span></h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <form action="{{ route('custom-fields.update', $customField) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Model Type -->
                            <div>
                                <x-input-label for="model_type" :value="__('Model Type')" />
                                <select id="model_type" name="model_type" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm" required>
                                    <option value="">Select Model Type</option>
                                    <option value="Department" {{ old('model_type', $customField->model_type) == 'Department' ? 'selected' : '' }}>Department</option>
                                    <option value="User" {{ old('model_type', $customField->model_type) == 'User' ? 'selected' : '' }}>User</option>
                                    <option value="Employee" {{ old('model_type', $customField->model_type) == 'Employee' ? 'selected' : '' }}>Employee</option>
                                    <option value="OnboardingRequest" {{ old('model_type', $customField->model_type) == 'OnboardingRequest' ? 'selected' : '' }}>Onboarding Request</option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Which form should this field appear on?</p>
                                <x-input-error class="mt-2" :messages="$errors->get('model_type')" />
                            </div>

                            <!-- Field Name -->
                            <div>
                                <x-input-label for="field_name" :value="__('Field Name (Internal)')" />
                                <x-text-input id="field_name" name="field_name" type="text" class="mt-1 block w-full" :value="old('field_name', $customField->field_name)" required />
                                <p class="mt-1 text-sm text-gray-500">Unique identifier (e.g., employee_badge_number)</p>
                                <x-input-error class="mt-2" :messages="$errors->get('field_name')" />
                            </div>

                            <!-- Label -->
                            <div>
                                <x-input-label for="label" :value="__('Label (Display Name)')" />
                                <x-text-input id="label" name="label" type="text" class="mt-1 block w-full" :value="old('label', $customField->label)" required />
                                <p class="mt-1 text-sm text-gray-500">What users will see (e.g., "Employee Badge Number")</p>
                                <x-input-error class="mt-2" :messages="$errors->get('label')" />
                            </div>

                            <!-- Field Type -->
                            <div>
                                <x-input-label for="field_type" :value="__('Field Type')" />
                                <select id="field_type" name="field_type" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm" required>
                                    <option value="">Select Field Type</option>
                                    <option value="text" {{ old('field_type', $customField->field_type) == 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="textarea" {{ old('field_type', $customField->field_type) == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                    <option value="number" {{ old('field_type', $customField->field_type) == 'number' ? 'selected' : '' }}>Number</option>
                                    <option value="date" {{ old('field_type', $customField->field_type) == 'date' ? 'selected' : '' }}>Date</option>
                                    <option value="select" {{ old('field_type', $customField->field_type) == 'select' ? 'selected' : '' }}>Select (Dropdown)</option>
                                    <option value="checkbox" {{ old('field_type', $customField->field_type) == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('field_type')" />
                            </div>

                            <!-- Order -->
                            <div>
                                <x-input-label for="order" :value="__('Display Order')" />
                                <x-text-input id="order" name="order" type="number" min="0" class="mt-1 block w-full" :value="old('order', $customField->order)" />
                                <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                                <x-input-error class="mt-2" :messages="$errors->get('order')" />
                            </div>
                        </div>

                        <!-- Options (for select field) -->
                        <div id="options_container" class="mt-6" style="display: none;">
                            <x-input-label for="options" :value="__('Options (comma-separated)')" />
                            <x-text-input id="options" name="options" type="text" class="mt-1 block w-full" :value="old('options', is_array($customField->options) ? implode(', ', $customField->options) : '')" placeholder="Option 1, Option 2, Option 3" />
                            <p class="mt-1 text-sm text-gray-500">Separate each option with a comma (only for Select field type)</p>
                            <x-input-error class="mt-2" :messages="$errors->get('options')" />
                        </div>

                        <!-- Help Text -->
                        <div class="mt-6">
                            <x-input-label for="help_text" :value="__('Help Text (Optional)')" />
                            <textarea id="help_text" name="help_text" rows="2" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm">{{ old('help_text', $customField->help_text) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Additional information to help users fill this field</p>
                            <x-input-error class="mt-2" :messages="$errors->get('help_text')" />
                        </div>

                        <!-- Checkboxes -->
                        <div class="mt-6 space-y-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_required" value="1" class="rounded border-gray-300 text-navy-600 shadow-sm focus:ring-navy-500" {{ old('is_required', $customField->is_required) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Required Field') }}</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-navy-600 shadow-sm focus:ring-navy-500" {{ old('is_active', $customField->is_active) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Active') }}</span>
                            </label>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('custom-fields.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white border-2 border-gray-300 rounded-xl font-semibold text-sm text-gray-700 uppercase tracking-wider hover:bg-gray-50 hover:border-gray-400 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm hover:shadow transition-all duration-200">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Custom Field') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show/hide options field based on field type
        document.getElementById('field_type').addEventListener('change', function() {
            const optionsContainer = document.getElementById('options_container');
            if (this.value === 'select') {
                optionsContainer.style.display = 'block';
            } else {
                optionsContainer.style.display = 'none';
            }
        });

        // Trigger change on page load to handle old input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('field_type').dispatchEvent(new Event('change'));
        });
    </script>
</x-app-layout>
