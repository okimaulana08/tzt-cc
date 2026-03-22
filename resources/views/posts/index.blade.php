<x-app-layout>
    <x-slot name="title">Posts</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-base font-semibold text-gray-800 dark:text-gray-200">Posts</h1>
            @can('create', App\Models\Post::class)
                <a href="{{ route('posts.create') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold
                          bg-gradient-to-r from-violet-600 to-purple-600 text-white
                          hover:from-violet-700 hover:to-purple-700 shadow-sm hover:shadow-md transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Post
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="p-6">

        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/30
                        border border-emerald-200 dark:border-emerald-700 p-4
                        text-emerald-800 dark:text-emerald-300 text-sm font-medium">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-center gap-3 rounded-xl bg-red-50 dark:bg-red-900/30
                        border border-red-200 dark:border-red-700 p-4
                        text-red-800 dark:text-red-300 text-sm font-medium">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if ($posts->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 rounded-2xl bg-violet-100 dark:bg-violet-900/40
                            flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">No posts yet</h3>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Start writing your first post.</p>
                @can('create', App\Models\Post::class)
                    <a href="{{ route('posts.create') }}"
                       class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold
                              bg-gradient-to-r from-violet-600 to-purple-600 text-white
                              hover:from-violet-700 hover:to-purple-700 shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create first post
                    </a>
                @endcan
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl
                                border border-gray-100 dark:border-gray-700
                                shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col">
                        <div class="p-5 flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                    {{ $post->status === 'published'
                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400'
                                        : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' }}">
                                    @if ($post->status === 'published')
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span>
                                    @endif
                                    {{ ucfirst($post->status) }}
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $post->user->name }}</span>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 leading-snug">
                                <a href="{{ route('posts.show', $post) }}"
                                   class="hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            @if ($post->excerpt)
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">{{ $post->excerpt }}</p>
                            @endif
                        </div>
                        <div class="px-5 py-3.5 bg-gray-50 dark:bg-gray-800/80 border-t border-gray-100 dark:border-gray-700
                                    flex items-center justify-between">
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('posts.show', $post) }}"
                                   class="text-xs font-semibold text-violet-600 dark:text-violet-400 hover:text-violet-800 dark:hover:text-violet-300 transition-colors">
                                    View
                                </a>
                                @can('update', $post)
                                    <a href="{{ route('posts.edit', $post) }}"
                                       class="text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                                        Edit
                                    </a>
                                @endcan
                                @can('delete', $post)
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                          onsubmit="return confirm('Delete this post?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-xs font-semibold text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-colors">
                                            Delete
                                        </button>
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
</x-app-layout>
