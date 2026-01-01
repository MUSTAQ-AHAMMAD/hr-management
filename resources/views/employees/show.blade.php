<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employee Details') }}
            </h2>
            <div class="flex space-x-2">
                @can('edit employees')
                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-4 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Employee
                </a>
                @endcan
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Employee Code</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->employee_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Designation</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->designation }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Joining Date</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->joining_date?->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Exit Date</p>
                            <p class="text-base font-medium text-gray-900">{{ $employee->exit_date?->format('F d, Y') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <div class="mt-1">
                                @if($employee->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                                @elseif($employee->status === 'inactive')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    On Leave
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onboarding Requests -->
            @if($employee->onboardingRequests->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Onboarding Requests</h3>
                    <div class="space-y-4">
                        @foreach($employee->onboardingRequests as $request)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">Request #{{ $request->id }}</p>
                                    <p class="text-sm text-gray-500">Created: {{ $request->created_at->format('F d, Y') }}</p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $request->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($request->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Exit Clearance Requests -->
            @if($employee->exitClearanceRequests->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Exit Clearance Requests</h3>
                    <div class="space-y-4">
                        @foreach($employee->exitClearanceRequests as $request)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">Request #{{ $request->id }}</p>
                                    <p class="text-sm text-gray-500">Created: {{ $request->created_at->format('F d, Y') }}</p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $request->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($request->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
