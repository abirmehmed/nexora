<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Sidebar -->
            <div class="hidden lg:block lg:col-span-3 space-y-6">
                <div class="bg-white rounded-2xl shadow-soft p-6 sticky top-24">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Menu</h3>
                    <nav class="space-y-2">
                        <a href="{{ route('feed') }}" class="flex items-center gap-3 px-4 py-3 bg-primary-50 text-primary-700 rounded-xl font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            Feed
                        </a>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('posts.create') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Create Post
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Feed Column -->
            <div class="lg:col-span-6 space-y-6">
                
                <!-- Create Post Quick Input -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-primary-400 to-fuchsia-400 flex items-center justify-center text-white font-bold shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <input type="text" name="title" placeholder="What's on your mind?" class="w-full text-lg font-semibold placeholder-gray-400 border-none focus:ring-0 p-0 bg-transparent" required>
                                <textarea name="body" rows="2" placeholder="Share your thoughts..." class="w-full mt-2 text-gray-600 placeholder-gray-400 border-none focus:ring-0 p-0 bg-transparent resize-none" required></textarea>
                                
                                <!-- Image Preview Area -->
                                <div id="image-preview-container" class="hidden mt-4 relative">
                                    <img id="image-preview" src="" alt="Preview" class="w-full h-64 object-cover rounded-xl">
                                    <button type="button" id="remove-image" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex gap-2">
                                        <label for="image-upload" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-full transition-colors cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <input type="file" name="image" id="image-upload" accept="image/*" class="hidden">
                                        </label>
                                        <button type="button" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-full transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                        </button>
                                    </div>
                                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium px-6 py-2 rounded-full transition-colors shadow-lg shadow-primary-500/30">
                                        Post
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Posts List -->
                @forelse($posts as $post)
                    <article class="bg-white rounded-2xl shadow-soft hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('profile.show', $post->user->username) }}" class="w-12 h-12 rounded-full bg-gradient-to-tr from-primary-400 to-fuchsia-400 flex items-center justify-center text-white font-bold text-lg hover:scale-105 transition-transform">
                                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                    </a>
                                    <div>
                                        <a href="{{ route('profile.show', $post->user->username) }}" class="font-bold text-gray-900 hover:text-primary-600 transition-colors">
                                            {{ $post->user->name }}
                                        </a>
                                        <p class="text-sm text-gray-500">@{{ $post->user->username }} · {{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @if(auth()->id() === $post->user_id)
                                    <div class="relative group">
                                        <button class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                        </button>
                                        <div class="absolute right-0 mt-1 w-32 bg-white rounded-lg shadow-lg ring-1 ring-black/5 hidden group-hover:block z-10">
                                            <a href="{{ route('posts.edit', $post) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                                            <form method="post" action="{{ route('posts.destroy', $post) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50" onclick="return confirm('Delete this post?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <h2 class="text-xl font-bold text-gray-900 mb-2 leading-tight">
                                <a href="{{ route('posts.show', $post) }}" class="hover:text-primary-600 transition-colors">{{ $post->title }}</a>
                            </h2>
                            
                            <div class="prose prose-slate max-w-none text-gray-600 mb-4">
                                <p>{!! \App\Services\TagParser::renderBody(e($post->body)) !!}</p>
                            </div>

                            <!-- Display Image if exists -->
                            @if($post->image)
                                <div class="mb-4 rounded-xl overflow-hidden border border-gray-100">
                                    <a href="{{ route('posts.show', $post) }}">
                                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-auto max-h-96 object-cover hover:scale-105 transition-transform duration-500">
                                    </a>
                                </div>
                            @endif

                            <!-- Tags -->
                            @if($post->tags->count() > 0)
                                <div class="flex flex-wrap gap-2 mt-3 mb-4">
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
                                
                                <a href="{{ route('posts.show', $post) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Read full post →</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-12 bg-white rounded-2xl shadow-soft">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Your feed is empty</h3>
                        <p class="text-gray-500 mt-1">Follow some people or create your first post!</p>
                    </div>
                @endforelse

                {{ $posts->links() }}
            </div>

            <!-- Right Sidebar -->
            <div class="hidden lg:block lg:col-span-3 space-y-6">
                <div class="bg-white rounded-2xl shadow-soft p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Who to follow</h3>
                    <div class="space-y-4">
                        @foreach($suggestedUsers ?? [] as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary-400 to-fuchsia-400 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">@{{ $user->username }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('users.follow', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-primary-600 bg-primary-50 hover:bg-primary-100 px-3 py-1.5 rounded-full transition-colors">
                                        Follow
                                    </button>
                                </form>
                            </div>
                        @endforeach
                        
                        @if(empty($suggestedUsers))
                            <p class="text-sm text-gray-500 italic">No suggestions available right now.</p>
                        @endif
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Trending Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            <a href="#" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-full transition-colors">#laravel</a>
                            <a href="#" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-full transition-colors">#php</a>
                            <a href="#" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-full transition-colors">#design</a>
                            <a href="#" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-full transition-colors">#startup</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Alpine.js for Image Preview -->
    <script>
        document.getElementById('image-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                    document.getElementById('image-preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('remove-image').addEventListener('click', function() {
            document.getElementById('image-upload').value = '';
            document.getElementById('image-preview-container').classList.add('hidden');
        });
    </script>
</x-app-layout>
