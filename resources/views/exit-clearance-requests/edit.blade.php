<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight"><span class="bg-gradient-to-r from-primary-600 to-cobalt-600 bg-clip-text text-transparent">
            {{ __('Edit Exit Clearance Request') }}
        </span></h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <form action="{{ route('exit-clearance-requests.update', $exitClearanceRequest) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Line Manager Name -->
                            <div>
                                <x-input-label for="line_manager_name" :value="__('Line Manager Name')" />
                                <x-text-input id="line_manager_name" name="line_manager_name" type="text" class="mt-1 block w-full" :value="old('line_manager_name', $exitClearanceRequest->line_manager_name)" placeholder="Enter line manager's full name" required />
                                <x-input-error class="mt-2" :messages="$errors->get('line_manager_name')" />
                            </div>

                            <!-- Line Manager Email -->
                            <div>
                                <x-input-label for="line_manager_email" :value="__('Line Manager Email')" />
                                <x-text-input id="line_manager_email" name="line_manager_email" type="email" class="mt-1 block w-full" :value="old('line_manager_email', $exitClearanceRequest->line_manager_email)" placeholder="manager@company.com" required />
                                <x-input-error class="mt-2" :messages="$errors->get('line_manager_email')" />
                            </div>

                            <div>
                                <x-input-label for="exit_date" :value="__('Exit Date')" />
                                <x-text-input id="exit_date" name="exit_date" type="date" class="mt-1 block w-full" :value="old('exit_date', $exitClearanceRequest->exit_date?->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('exit_date')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm" required>
                                    <option value="pending" {{ old('status', $exitClearanceRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status', $exitClearanceRequest->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="cleared" {{ old('status', $exitClearanceRequest->status) == 'cleared' ? 'selected' : '' }}>Cleared</option>
                                    <option value="rejected" {{ old('status', $exitClearanceRequest->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div>
                                <x-input-label for="reason" :value="__('Reason')" />
                                <textarea id="reason" name="reason" rows="4" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm">{{ old('reason', $exitClearanceRequest->reason) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('reason')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('exit-clearance-requests.show', $exitClearanceRequest) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
