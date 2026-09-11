<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
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
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $post->body }}</p>
                    </div>

                    @if(auth()->id() === $post->user_id)
                        <div class="flex gap-4 pt-4 border-t">
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

            <div class="mt-4">
                <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:underline">← Back to posts</a>
            </div>
        </div>
    </div>
</x-app-layout>
