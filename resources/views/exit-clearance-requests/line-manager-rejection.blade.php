<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            <span class="bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent">
                {{ __('Reject Exit Clearance Request') }}
            </span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Rejection Notice -->
            <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 rounded-lg shadow-lg">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-red-900">Reject Exit Clearance</h3>
                            <p class="mt-2 text-sm text-red-800">
                                You are about to reject this exit clearance request. Please provide a reason for the rejection.
                                The HR department will be notified of your decision.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Details -->
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="bg-gradient-to-r from-cobalt-600 to-primary-600 px-8 py-4">
                    <h3 class="text-xl font-bold text-white">Employee Information</h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-full bg-cobalt-100 flex items-center justify-center">
                                    <span class="text-cobalt-700 font-bold text-lg">
                                        {{ substr($exitClearanceRequest->employee->first_name, 0, 1) }}{{ substr($exitClearanceRequest->employee->last_name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Employee Name</p>
                                <p class="text-lg font-bold text-gray-900">{{ $exitClearanceRequest->employee->full_name }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Employee Code</p>
                            <p class="text-base font-semibold text-gray-900">{{ $exitClearanceRequest->employee->employee_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Designation</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->employee->designation ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exit Details -->
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="bg-gradient-to-r from-orange-600 to-red-600 px-8 py-4">
                    <h3 class="text-xl font-bold text-white">Exit Clearance Details</h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Exit Date</p>
                            <p class="text-base font-semibold text-gray-900">{{ $exitClearanceRequest->exit_date?->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Initiated By</p>
                            <p class="text-base font-medium text-gray-900">{{ $exitClearanceRequest->initiatedBy->name }}</p>
                        </div>
                        @if($exitClearanceRequest->reason)
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500 mb-2">Reason for Exit</p>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <p class="text-base text-gray-900">{{ $exitClearanceRequest->reason }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Rejection Form -->
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="bg-gradient-to-r from-red-600 to-orange-600 px-8 py-4">
                    <h3 class="text-xl font-bold text-white">Rejection Details</h3>
                </div>
                <div class="p-8">
                    <form action="{{ route('exit-clearance-requests.line-manager-reject', ['exitClearanceRequest' => $exitClearanceRequest->id, 'token' => $token]) }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Rejection Reason <span class="text-red-600">*</span>
                                </label>
                                <textarea 
                                    id="notes" 
                                    name="notes" 
                                    rows="5" 
                                    required
                                    class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                    placeholder="Please provide a detailed reason for rejecting this exit clearance request..."></textarea>
                                <p class="mt-2 text-sm text-gray-500">
                                    This reason will be shared with HR and may be reviewed by other stakeholders.
                                </p>
                            </div>
                            <div class="flex gap-4">
                                <button 
                                    type="submit" 
                                    class="flex-1 inline-flex justify-center items-center px-6 py-4 bg-gradient-to-r from-red-600 to-orange-600 border border-transparent rounded-lg font-bold text-base text-white uppercase tracking-wider hover:from-red-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-200">
                                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reject Exit Clearance
                                </button>
                                <a 
                                    href="{{ route('exit-clearance-requests.line-manager-approve', ['exitClearanceRequest' => $exitClearanceRequest->id, 'token' => $token]) }}" 
                                    class="inline-flex justify-center items-center px-6 py-4 bg-gray-200 border border-gray-300 rounded-lg font-semibold text-base text-gray-700 uppercase tracking-wider hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                                    Back to Approval
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
