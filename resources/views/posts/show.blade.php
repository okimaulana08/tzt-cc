<x-app-layout>
    <x-slot name="title">{{ $post->title }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('posts.index') }}"
                   class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100
                          dark:hover:text-gray-300 dark:hover:bg-gray-800 transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-base font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $post->title }}</h1>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @can('update', $post)
                    <a href="{{ route('posts.edit', $post) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold
                              bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                              hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Edit
                    </a>
                @endcan
                @can('delete', $post)
                    <form action="{{ route('posts.destroy', $post) }}" method="POST"
                          onsubmit="return confirm('Delete this post?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold
                                       bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400
                                       hover:bg-red-100 dark:hover:bg-red-900/50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6 text-sm">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $post->status === 'published'
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400'
                                : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $post->status === 'published' ? 'bg-emerald-500' : 'bg-amber-500' }} inline-block"></span>
                            {{ ucfirst($post->status) }}
                        </span>
                        <span class="text-gray-500 dark:text-gray-400">
                            By <strong class="text-gray-700 dark:text-gray-300">{{ $post->user->name }}</strong>
                        </span>
                        <span class="text-gray-300 dark:text-gray-600">&bull;</span>
                        <span class="text-gray-500 dark:text-gray-400">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                        </span>
                    </div>

                    @if ($post->excerpt)
                        <p class="text-base text-gray-600 dark:text-gray-400 italic
                                  border-l-4 border-violet-400 dark:border-violet-600 pl-4 mb-6 leading-relaxed">
                            {{ $post->excerpt }}
                        </p>
                    @endif

                    {{-- Trusted HTML: content must be sanitised before saving --}}
                    <div class="prose prose-gray dark:prose-invert max-w-none leading-relaxed">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
