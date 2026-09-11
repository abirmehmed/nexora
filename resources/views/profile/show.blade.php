<x-guest-layout>
    <div class="max-w-4xl mx-auto py-8">
        {{-- Cover Image --}}
        <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-t-lg relative">
            @if($user->cover_image)
                <img src="{{ asset('storage/' . $user->cover_image) }}" alt="Cover" class="w-full h-full object-cover rounded-t-lg">
            @endif
        </div>

        {{-- Profile Info --}}
        <div class="bg-white shadow rounded-b-lg p-6">
            <div class="flex items-start gap-6">
                {{-- Avatar --}}
                <div class="-mt-16">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                    @else
                        <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg bg-indigo-500 flex items-center justify-center text-white text-4xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Details --}}
                <div class="flex-1 pt-4">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->display_name ?? $user->name }}</h1>
                    <p class="text-gray-500 text-sm">{{ '@' . $user->username }}</p>

                    @if($user->bio)
                        <p class="mt-3 text-gray-700">{{ $user->bio }}</p>
                    @endif

                    <div class="mt-4 flex gap-6 text-sm text-gray-600">
                        @if($user->location)
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $user->location }}
                            </div>
                        @endif

                        @if($user->website)
                            <a href="{{ $user->website }}" target="_blank" class="flex items-center gap-1 text-indigo-600 hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                {{ parse_url($user->website, PHP_URL_HOST) }}
                            </a>
                        @endif
                    </div>

                    <p class="mt-3 text-xs text-gray-400">
                        Joined {{ $user->created_at->format('F Y') }}
                    </p>
                </div>
            </div>

            {{-- Stats --}}
            <div class="mt-6 border-t pt-4 flex gap-8">
                <div><span class="font-bold text-gray-900">0</span><span class="text-gray-500 text-sm ml-1">Posts</span></div>
                <div><span class="font-bold text-gray-900">0</span><span class="text-gray-500 text-sm ml-1">Followers</span></div>
                <div><span class="font-bold text-gray-900">0</span><span class="text-gray-500 text-sm ml-1">Following</span></div>
            </div>
        </div>
    </div>
</x-guest-layout>
