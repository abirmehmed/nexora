<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4">
        <div class="bg-white rounded-2xl shadow-soft p-8 text-center">
            <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-tr from-primary-400 to-fuchsia-400 flex items-center justify-center text-white text-3xl font-bold mb-4">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="text-gray-500">@{{ $user->username }}</p>
            
            @if($user->bio)
                <p class="mt-4 text-gray-700 max-w-lg mx-auto">{{ $user->bio }}</p>
            @endif

            <div class="mt-6 flex justify-center gap-8">
                <div class="text-center">
                    <span class="block text-xl font-bold text-gray-900">{{ $user->posts()->count() }}</span>
                    <span class="text-sm text-gray-500">Posts</span>
                </div>
                <div class="text-center">
                    <span class="block text-xl font-bold text-gray-900">{{ $user->followers()->count() }}</span>
                    <span class="text-sm text-gray-500">Followers</span>
                </div>
                <div class="text-center">
                    <span class="block text-xl font-bold text-gray-900">{{ $user->following()->count() }}</span>
                    <span class="text-sm text-gray-500">Following</span>
                </div>
            </div>
        </div>

        <div class="mt-8 space-y-6">
            @forelse($posts as $post)
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h3 class="font-bold text-lg">{{ $post->title }}</h3>
                    <p class="text-gray-600 mt-2">{{ Str::limit($post->body, 150) }}</p>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">No posts yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
