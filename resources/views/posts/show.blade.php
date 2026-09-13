<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold mr-3">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <a href="{{ route('profile.show', $post->user->username) }}" class="font-semibold text-gray-900 hover:text-indigo-600">
                                {{ $post->user->name }}
                            </a>
                            <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>

                    <div class="prose max-w-none mb-6">
                        <div class="prose prose-slate max-w-none text-gray-700">{!! AppServicesTagParser::renderBody(e($post->body)) !!}</div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t">
                        <form method="post" action="{{ route('posts.like', $post) }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 hover:text-red-600 transition-colors">
                                @if($user && $user->hasLiked($post))
                                    <svg class="w-6 h-6 text-red-600 fill-current" viewBox="0 0 20 20">
                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                    </svg>
                                    <span class="text-sm font-medium">Liked</span>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span class="text-sm font-medium">Like</span>
                                @endif
                                <span class="text-sm text-gray-600">{{ $post->likes->count() }}</span>
                            </button>
                        </form>

                        @if(auth()->id() === $post->user_id)
                            <div class="flex gap-4">
                                <a href="{{ route('posts.edit', $post) }}" class="text-indigo-600 hover:underline">Edit</a>
                                <form method="post" action="{{ route('posts.destroy', $post) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Comments Section --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Comments ({{ $post->comments->count() }})</h3>

                    {{-- Comment Form --}}
                    <form method="post" action="{{ route('comments.store', $post) }}" class="mb-6">
                        @csrf
                        <div class="mb-4">
                            <textarea name="body" rows="3" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Write a comment..." required>{{ old('body') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('body')" />
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded">
                            Post Comment
                        </button>
                    </form>

                    {{-- Comments List --}}
                    @forelse($post->comments as $comment)
                        <div class="border-t pt-4 mb-4 last:mb-0">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold mr-3 flex-shrink-0">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center mb-1">
                                        <a href="{{ route('profile.show', $comment->user->username) }}" class="font-semibold text-gray-900 hover:text-indigo-600">
                                            {{ $comment->user->name }}
                                        </a>
                                        <span class="text-sm text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">{{ $comment->body }}</p>
                                    
                                    @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                        <form method="post" action="{{ route('comments.destroy', $comment) }}" class="mt-2 inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:underline" onclick="return confirm('Delete this comment?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No comments yet. Be the first to comment!</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:underline">← Back to posts</a>
            </div>
        </div>
    </div>
</x-app-layout>
