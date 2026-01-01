<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Employee') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Employee Code -->
                            <div>
                                <x-input-label for="employee_code" :value="__('Employee Code')" />
                                <x-text-input id="employee_code" name="employee_code" type="text" class="mt-1 block w-full" :value="old('employee_code', $employee->employee_code)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('employee_code')" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $employee->email)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <!-- First Name -->
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $employee->first_name)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                            </div>

                            <!-- Last Name -->
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $employee->last_name)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $employee->phone)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <!-- Department -->
                            <div>
                                <x-input-label for="department_id" :value="__('Department')" />
                                <select id="department_id" name="department_id" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                            </div>

                            <!-- Designation -->
                            <div>
                                <x-input-label for="designation" :value="__('Designation')" />
                                <x-text-input id="designation" name="designation" type="text" class="mt-1 block w-full" :value="old('designation', $employee->designation)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('designation')" />
                            </div>

                            <!-- Joining Date -->
                            <div>
                                <x-input-label for="joining_date" :value="__('Joining Date')" />
                                <x-text-input id="joining_date" name="joining_date" type="date" class="mt-1 block w-full" :value="old('joining_date', $employee->joining_date?->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('joining_date')" />
                            </div>

                            <!-- Exit Date -->
                            <div>
                                <x-input-label for="exit_date" :value="__('Exit Date (Optional)')" />
                                <x-text-input id="exit_date" name="exit_date" type="date" class="mt-1 block w-full" :value="old('exit_date', $employee->exit_date?->format('Y-m-d'))" />
                                <x-input-error class="mt-2" :messages="$errors->get('exit_date')" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm" required>
                                    <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="on_leave" {{ old('status', $employee->status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Employee') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
