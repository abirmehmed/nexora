<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Posts') }}
            </h2>
            <a href="{{ route('posts.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Create Post
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse($posts as $post)
                        <article class="border-b border-gray-200 pb-6 mb-6 last:border-b-0 last:pb-0 last:mb-0">
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('profile.show', $post->user->username) }}" class="font-semibold text-gray-900 hover:text-indigo-600">
                                        {{ $post->user->name }}
                                    </a>
                                    <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold mb-2">
                                <a href="{{ route('posts.show', $post) }}" class="hover:text-indigo-600">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p class="text-gray-700 mb-3">{{ Str::limit($post->body, 200) }}</p>
                            
                            <div class="flex items-center justify-between mt-3">
                                <a href="{{ route('posts.show', $post) }}" class="text-indigo-600 hover:underline text-sm">Read more</a>
                                
                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    <span>{{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}</span>
                                    
                                    <form method="post" action="{{ route('posts.like', $post) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-1 hover:text-red-600 transition-colors">
                                            @if($user && $user->hasLiked($post))
                                                <svg class="w-5 h-5 text-red-600 fill-current" viewBox="0 0 20 20">
                                                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                            @endif
                                            <span>{{ $post->likes->count() }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="text-gray-500">No posts yet.</p>
                    @endforelse

                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
