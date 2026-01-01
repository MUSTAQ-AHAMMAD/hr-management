<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-navy-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-navy-700 focus:outline-none focus:ring-2 focus:ring-navy-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Mark All as Read
                </button>
            </form>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse($notifications as $notification)
                    <div class="border-b border-gray-200 pb-4 mb-4 last:border-0">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-base font-medium {{ $notification->is_read ? 'text-gray-700' : 'text-gray-900' }}">
                                    {{ $notification->title }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notification->is_read)
                            <form action="{{ route('notifications.mark-read', $notification) }}" method="POST">
                                @csrf
                                <button type="submit" class="ml-4 text-sm text-navy-600 hover:text-navy-900">
                                    Mark as Read
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-500 py-8">
                        <p>No notifications yet.</p>
                    </div>
                    @endforelse

                    @if($notifications->hasPages())
                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
