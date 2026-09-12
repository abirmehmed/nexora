<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form method="post" action="{{ route('notifications.read') }}">
                    @csrf
                    <button type="submit" class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1 px-3 rounded">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse($notifications as $notification)
                        <a href="{{ $notification->data['url'] }}" class="block border-b border-gray-200 last:border-b-0 py-4 hover:bg-gray-50 transition-colors {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <p class="text-gray-900 {{ is_null($notification->read_at) ? 'font-semibold' : '' }}">
                                        {{ $notification->data['message'] }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if(is_null($notification->read_at))
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        New
                                    </span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                            <p class="mt-1 text-sm text-gray-500">You're all caught up!</p>
                        </div>
                    @endforelse

                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
