<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Feed --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            @forelse($posts as $post)
                                <article class="border-b border-gray-200 pb-6 mb-6 last:border-b-0 last:pb-0 last:mb-0">
                                    <div class="flex items-center mb-3">
                                        @if($post->user->avatar)
                                            <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full object-cover mr-3">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold mr-3">
                                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('profile.show', $post->user->username) }}" class="font-semibold text-gray-900 hover:text-indigo-600">
                                                {{ $post->user->display_name ?? $post->user->name }}
                                            </a>
                                            <p class="text-sm text-gray-500">{{ '@' . $post->user->username }} · {{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-bold mb-2">
                                        <a href="{{ route('posts.show', $post) }}" class="hover:text-indigo-600">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-700 mb-3">{{ Str::limit($post->body, 200) }}</p>
                                    <a href="{{ route('posts.show', $post) }}" class="text-indigo-600 hover:underline text-sm">Read more</a>
                                </article>
                            @empty
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Your feed is empty</h3>
                                    <p class="mt-1 text-sm text-gray-500">Follow some users to see their posts here.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                            Browse all posts
                                        </a>
                                    </div>
                                </div>
                            @endforelse

                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>

                {{-- Sidebar: Suggested Users --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Who to follow</h3>
                            @forelse($suggestedUsers as $suggestedUser)
                                <div class="flex items-center mb-4 last:mb-0">
                                    @if($suggestedUser->avatar)
                                        <img src="{{ asset('storage/' . $suggestedUser->avatar) }}" alt="{{ $suggestedUser->name }}" class="w-10 h-10 rounded-full object-cover mr-3">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold mr-3">
                                            {{ strtoupper(substr($suggestedUser->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <a href="{{ route('profile.show', $suggestedUser->username) }}" class="font-semibold text-gray-900 hover:text-indigo-600 block">
                                            {{ $suggestedUser->display_name ?? $suggestedUser->name }}
                                        </a>
                                        <p class="text-sm text-gray-500">{{ '@' . $suggestedUser->username }}</p>
                                    </div>
                                    <form method="post" action="{{ route('users.follow', $suggestedUser) }}">
                                        @csrf
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-1 px-3 rounded">
                                            Follow
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No suggestions available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
