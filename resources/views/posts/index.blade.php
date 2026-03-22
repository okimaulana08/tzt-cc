<x-app-layout>
    <x-slot name="title">Posts</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Posts</h2>
            @can('create', App\Models\Post::class)
                <a href="{{ route('posts.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    + New Post
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 border border-green-200 p-4 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($posts->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <p class="text-lg">No posts yet.</p>
                    @can('create', App\Models\Post::class)
                        <a href="{{ route('posts.create') }}" class="mt-4 inline-block text-indigo-600 hover:underline">Create the first post</a>
                    @endcan
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                            <div class="p-6 flex-1">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                                        {{ $post->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ $post->user->name }}</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('posts.show', $post) }}" class="hover:text-indigo-600 transition">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                @if ($post->excerpt)
                                    <p class="text-sm text-gray-500 line-clamp-3">{{ $post->excerpt }}</p>
                                @endif
                            </div>
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                <div class="flex gap-2">
                                    <a href="{{ route('posts.show', $post) }}"
                                       class="text-xs text-indigo-600 hover:underline font-medium">View</a>
                                    @can('update', $post)
                                        <a href="{{ route('posts.edit', $post) }}"
                                           class="text-xs text-gray-600 hover:underline font-medium">Edit</a>
                                    @endcan
                                    @can('delete', $post)
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                              onsubmit="return confirm('Delete this post?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:underline font-medium">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
