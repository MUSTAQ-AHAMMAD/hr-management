<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Assignments by Employee') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg shadow-sm mb-4 flex items-start" role="alert">
                <svg class="h-5 w-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Search Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-cobalt-50 to-blue-50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Search Employee</h3>
                    <form action="{{ route('task-assignments.by-employee') }}" method="GET" class="flex gap-4">
                        <div class="flex-1">
                            <label for="employee_code" class="block text-sm font-medium text-gray-700 mb-2">Employee ID / Name</label>
                            <input 
                                type="text" 
                                name="employee_code" 
                                id="employee_code" 
                                value="{{ $employeeCode }}"
                                placeholder="Enter Employee ID or Name"
                                class="block w-full border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm"
                            >
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 active:bg-cobalt-900 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($employeeCode && $employees->count() > 0)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">Found <span class="font-semibold text-gray-900">{{ $employees->count() }}</span> employee(s)</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Employee ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Department
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($employees as $employee)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-cobalt-100">
                                                    <svg class="h-6 w-6 text-cobalt-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $employee->employee_code }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $employee->full_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $employee->department->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600">{{ $employee->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                   ($employee->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 
                                                   'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($employee->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('task-assignments.employee-detail', $employee->id) }}" class="inline-flex items-center px-3 py-2 bg-cobalt-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cobalt-700 focus:outline-none focus:ring-2 focus:ring-cobalt-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                </svg>
                                                View Tasks
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif($employeeCode && $employees->count() === 0)
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No employees found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try searching with a different Employee ID or Name.</p>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Search for an Employee</h3>
                            <p class="mt-2 text-sm text-gray-500">Enter an Employee ID or Name in the search box above to view their task assignments.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
