<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Post Card -->
        <article class="bg-white rounded-2xl shadow-soft overflow-hidden mb-8">
            <div class="p-8">
                <!-- Author Info -->
                <div class="flex items-center gap-4 mb-6">
                    <a href="{{ route('profile.show', $post->user->username) }}" class="w-14 h-14 rounded-full bg-gradient-to-tr from-primary-400 to-fuchsia-400 flex items-center justify-center text-white font-bold text-xl hover:scale-105 transition-transform">
                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                    </a>
                    <div>
                        <a href="{{ route('profile.show', $post->user->username) }}" class="text-lg font-bold text-gray-900 hover:text-primary-600 transition-colors">
                            {{ $post->user->name }}
                        </a>
                        <p class="text-sm text-gray-500">@{{ $post->user->username }} · {{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Content -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
                
                <div class="prose prose-slate max-w-none text-gray-700 mb-6">
                    <p class="whitespace-pre-wrap">{!! \App\Services\TagParser::renderBody(e($post->body)) !!}</p>
                </div>

                <!-- Image Display -->
                @if($post->image)
                    <div class="mb-6 rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-auto max-h-[600px] object-cover">
                    </div>
                @endif

                <!-- Tags -->
                @if($post->tags->count() > 0)
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('tags.show', $tag->name) }}" class="px-3 py-1 bg-primary-50 text-primary-700 text-sm font-medium rounded-full hover:bg-primary-100 transition-colors">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-6">
                        <form method="post" action="{{ route('posts.like', $post) }}" class="group">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-gray-500 hover:text-pink-600 transition-colors">
                                <div class="p-2 rounded-full group-hover:bg-pink-50 transition-colors">
                                    @if(auth()->check() && auth()->user()->hasLiked($post))
                                        <svg class="w-6 h-6 fill-current text-pink-600" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    @endif
                                </div>
                                <span class="text-base font-medium">{{ $post->likes->count() }} Likes</span>
                            </button>
                        </form>
                    </div>
                    
                    @if(auth()->id() === $post->user_id)
                        <div class="flex gap-3">
                            <a href="{{ route('posts.edit', $post) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Edit</a>
                            <form method="post" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <div class="bg-white rounded-2xl shadow-soft p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Comments ({{ $post->comments->count() }})</h3>
            
            @auth
            <form method="post" action="{{ route('comments.store', $post) }}" class="mb-8">
                @csrf
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary-400 to-fuchsia-400 flex items-center justify-center text-white font-bold shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <textarea name="body" rows="3" placeholder="Write a comment..." class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none" required></textarea>
                        <div class="flex justify-end mt-2">
                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium px-5 py-2 rounded-full transition-colors">
                                Post Comment
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            @endauth

            <div class="space-y-6">
                @forelse($post->comments as $comment)
                    <div class="flex gap-4">
                        <a href="{{ route('profile.show', $comment->user->username) }}" class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold shrink-0 hover:bg-gray-300 transition-colors">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </a>
                        <div class="flex-1 bg-gray-50 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-1">
                                <a href="{{ route('profile.show', $comment->user->username) }}" class="font-bold text-gray-900 hover:text-primary-600 transition-colors">
                                    {{ $comment->user->name }}
                                </a>
                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700">{{ $comment->body }}</p>
                            
                            @if(auth()->id() === $comment->user_id)
                                <form method="post" action="{{ route('comments.destroy', $comment) }}" class="mt-2" onsubmit="return confirm('Delete this comment?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">No comments yet. Be the first to comment!</p>
                @endforelse
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('feed') }}" class="text-primary-600 hover:text-primary-700 font-medium">← Back to Feed</a>
        </div>
    </div>
</x-app-layout>
