<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email (Optional)')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                <p class="mt-1 text-sm text-gray-500">Leave empty if email ID is not yet created. IT team will be notified.</p>
                            </div>

                            <!-- Password -->
                            <div>
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                            </div>

                            <!-- Department -->
                            <div>
                                <x-input-label for="department_id" :value="__('Department')" />
                                <select id="department_id" name="department_id" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <!-- Role -->
                            <div>
                                <x-input-label for="role" :value="__('Role')" />
                                <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm" required>
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('role')" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>
                        </div>

                        <!-- Custom Fields Section -->
                        @if(isset($customFields) && $customFields->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($customFields as $field)
                                <div>
                                    <x-input-label for="custom_field_{{ $field->id }}" :value="$field->label" />
                                    
                                    @if($field->field_type === 'text')
                                        <x-text-input id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" type="text" class="mt-1 block w-full" :value="old('custom_fields.' . $field->id)" :required="$field->is_required" />
                                    @elseif($field->field_type === 'textarea')
                                        <textarea id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" rows="3" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm" {{ $field->is_required ? 'required' : '' }}>{{ old('custom_fields.' . $field->id) }}</textarea>
                                    @elseif($field->field_type === 'number')
                                        <x-text-input id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" type="number" class="mt-1 block w-full" :value="old('custom_fields.' . $field->id)" :required="$field->is_required" />
                                    @elseif($field->field_type === 'date')
                                        <x-text-input id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" type="date" class="mt-1 block w-full" :value="old('custom_fields.' . $field->id)" :required="$field->is_required" />
                                    @elseif($field->field_type === 'select')
                                        <select id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm" {{ $field->is_required ? 'required' : '' }}>
                                            <option value="">Select {{ $field->label }}</option>
                                            @foreach(json_decode($field->options, true) ?? [] as $option)
                                                <option value="{{ $option }}" {{ old('custom_fields.' . $field->id) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($field->field_type === 'checkbox')
                                        <label class="flex items-center mt-2">
                                            <input type="checkbox" name="custom_fields[{{ $field->id }}]" value="1" class="rounded border-gray-300 text-navy-600 shadow-sm focus:ring-navy-500" {{ old('custom_fields.' . $field->id) ? 'checked' : '' }}>
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

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
