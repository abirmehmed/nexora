<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Tag Header -->
        <div class="bg-white rounded-2xl shadow-soft p-8 mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 text-primary-600 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">#{{ $tag->name }}</h1>
            <p class="text-gray-500 mt-2">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</p>
        </div>

        <!-- Posts List -->
        <div class="space-y-6">
            @forelse($posts as $post)
                <article class="bg-white rounded-2xl shadow-soft hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-3 mb-4">
                            <a href="{{ route('profile.show', $post->user->username) }}" class="w-12 h-12 rounded-full bg-gradient-to-tr from-primary-400 to-fuchsia-400 flex items-center justify-center text-white font-bold text-lg shrink-0 hover:scale-105 transition-transform">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </a>
                            <div>
                                <a href="{{ route('profile.show', $post->user->username) }}" class="font-bold text-gray-900 hover:text-primary-600 transition-colors">
                                    {{ $post->user->name }}
                                </a>
                                <p class="text-sm text-gray-500">@{{ $post->user->username }} · {{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold text-gray-900 mb-2">
                            <a href="{{ route('posts.show', $post) }}" class="hover:text-primary-600 transition-colors">{{ $post->title }}</a>
                        </h2>

                        <div class="prose prose-slate max-w-none text-gray-600 mb-4">
                            <p>{!! \App\Services\TagParser::renderBody(e($post->body)) !!}</p>
                        </div>

                        <!-- Tags -->
                        @if($post->tags->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('tags.show', $tag->name) }}" class="px-3 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-full hover:bg-primary-100 transition-colors">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <!-- Action Bar -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-6">
                                <form method="post" action="{{ route('posts.like', $post) }}" class="group">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 text-gray-500 hover:text-pink-600 transition-colors">
                                        <div class="p-2 rounded-full group-hover:bg-pink-50 transition-colors">
                                            @if(auth()->check() && auth()->user()->hasLiked($post))
                                                <svg class="w-5 h-5 fill-current text-pink-600" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            @endif
                                        </div>
                                        <span class="text-sm font-medium">{{ $post->likes->count() }}</span>
                                    </button>
                                </form>

                                <a href="{{ route('posts.show', $post) }}" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition-colors group">
                                    <div class="p-2 rounded-full group-hover:bg-blue-50 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    </div>
                                    <span class="text-sm font-medium">{{ $post->comments_count ?? $post->comments->count() }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl shadow-soft">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No posts with this tag yet</h3>
                    <p class="text-gray-500 mt-1">Be the first to use #{{ $tag->name }}!</p>
                </div>
            @endforelse

            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>
