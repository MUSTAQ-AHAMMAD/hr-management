<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight"><span class="bg-gradient-to-r from-primary-600 to-cobalt-600 bg-clip-text text-transparent">
            {{ __('Edit Onboarding Request #') }}{{ $onboardingRequest->id }}
        </span></h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <form action="{{ route('onboarding-requests.update', $onboardingRequest) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Line Manager Name -->
                            <div>
                                <x-input-label for="line_manager_name" :value="__('Line Manager Name (Optional)')" />
                                <x-text-input id="line_manager_name" name="line_manager_name" type="text" class="mt-1 block w-full" :value="old('line_manager_name', $onboardingRequest->line_manager_name)" placeholder="Enter line manager's full name" />
                                <x-input-error class="mt-2" :messages="$errors->get('line_manager_name')" />
                            </div>

                            <!-- Line Manager Email -->
                            <div>
                                <x-input-label for="line_manager_email" :value="__('Line Manager Email (Optional)')" />
                                <x-text-input id="line_manager_email" name="line_manager_email" type="email" class="mt-1 block w-full" :value="old('line_manager_email', $onboardingRequest->line_manager_email)" placeholder="manager@company.com" />
                                <x-input-error class="mt-2" :messages="$errors->get('line_manager_email')" />
                            </div>

                            <!-- Expected Completion Date -->
                            <div>
                                <x-input-label for="expected_completion_date" :value="__('Expected Completion Date')" />
                                <x-text-input id="expected_completion_date" name="expected_completion_date" type="date" class="mt-1 block w-full" :value="old('expected_completion_date', $onboardingRequest->expected_completion_date?->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('expected_completion_date')" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm" required>
                                    <option value="pending" {{ old('status', $onboardingRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status', $onboardingRequest->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $onboardingRequest->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $onboardingRequest->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <!-- Notes -->
                            <div>
                                <x-input-label for="notes" :value="__('Notes')" />
                                <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 focus:border-navy-500 focus:ring-navy-500 rounded-md shadow-sm">{{ old('notes', $onboardingRequest->notes) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('onboarding-requests.show', $onboardingRequest) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
