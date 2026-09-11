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
                            <a href="{{ route('posts.show', $post) }}" class="text-indigo-600 hover:underline text-sm">Read more</a>
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
