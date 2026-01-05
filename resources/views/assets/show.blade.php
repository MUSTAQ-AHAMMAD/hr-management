<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight"><span class="bg-gradient-to-r from-primary-600 to-cobalt-600 bg-clip-text text-transparent">
                {{ __('Asset Details') }}
            </span></h2>
            <a href="{{ route('assets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Asset Type</p>
                            <p class="text-base font-medium text-gray-900">{{ $asset->asset_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Asset Name</p>
                            <p class="text-base font-medium text-gray-900">{{ $asset->asset_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Serial Number</p>
                            <p class="text-base font-medium text-gray-900">{{ $asset->serial_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            @php
                                $statusColors = [
                                    'assigned' => 'bg-blue-100 text-blue-800',
                                    'returned' => 'bg-green-100 text-green-800',
                                    'damaged' => 'bg-yellow-100 text-yellow-800',
                                    'lost' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$asset->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($asset->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Employee</p>
                            <p class="text-base font-medium text-gray-900">{{ $asset->employee->full_name }}</p>
                            <p class="text-sm text-gray-500">{{ $asset->employee->employee_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="text-base font-medium text-gray-900">{{ $asset->employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Assigned Date</p>
                            <p class="text-base font-medium text-gray-900">{{ $asset->assigned_date?->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Assigned By</p>
                            <p class="text-base font-medium text-gray-900">{{ $asset->assignedBy->name ?? 'N/A' }}</p>
                        </div>
                        @if($asset->return_date)
                        <div>
                            <p class="text-sm text-gray-500">Return Date</p>
                            <p class="text-base font-medium text-gray-900">{{ $asset->return_date?->format('F d, Y') }}</p>
                        </div>
                        @endif
                        @if($asset->description)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Description</p>
                            <p class="text-base text-gray-900">{{ $asset->description }}</p>
                        </div>
                        @endif
                        @if($asset->return_notes)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Return Notes</p>
                            <p class="text-base text-gray-900">{{ $asset->return_notes }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end mt-6 space-x-3">
                        <a href="{{ route('assets.edit', $asset) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Asset
                        </a>
                        @if($asset->status === 'assigned')
                        <form action="{{ route('assets.mark-returned', $asset) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Mark this asset as returned?')">
                                Mark as Returned
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
