<x-app-layout>
    <x-slot name="title">{{ $post->title }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('posts.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Back to Posts</a>
                <h2 class="mt-1 font-semibold text-xl text-gray-800 leading-tight">{{ $post->title }}</h2>
            </div>
            <div class="flex gap-2">
                @can('update', $post)
                    <a href="{{ route('posts.edit', $post) }}"
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition">
                        Edit
                    </a>
                @endcan
                @can('delete', $post)
                    <form action="{{ route('posts.destroy', $post) }}" method="POST"
                          onsubmit="return confirm('Delete this post?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition">
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6 text-sm text-gray-500">
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $post->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($post->status) }}
                        </span>
                        <span>By <strong class="text-gray-700">{{ $post->user->name }}</strong></span>
                        <span>&bull;</span>
                        <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                    </div>

                    @if ($post->excerpt)
                        <p class="text-lg text-gray-600 italic border-l-4 border-indigo-300 pl-4 mb-6">
                            {{ $post->excerpt }}
                        </p>
                    @endif

                    {{-- Trusted HTML: content must be sanitised before saving (e.g. HTMLPurifier or strip_tags in the model/action). --}}
                    <div class="prose prose-gray max-w-none">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
