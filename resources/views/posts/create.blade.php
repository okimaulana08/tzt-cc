<x-app-layout>
    <x-slot name="title">Create Post</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('posts.index') }}"
               class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100
                      dark:hover:text-gray-300 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-base font-semibold text-gray-800 dark:text-gray-200">Create New Post</h1>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-8">
                <form action="{{ route('posts.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="title">Title <span class="text-red-500">*</span></x-input-label>
                        <x-text-input type="text" id="title" name="title" value="{{ old('title') }}"
                                      class="mt-1.5 @error('title') border-red-400 dark:border-red-500 @enderror"
                                      placeholder="Enter post title..." required />
                        @error('title')
                            <x-input-error :messages="$message" class="mt-1" />
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="excerpt">Excerpt</x-input-label>
                        <textarea id="excerpt" name="excerpt" rows="2"
                                  class="mt-1.5 w-full rounded-xl border border-gray-300 dark:border-gray-600
                                         bg-white dark:bg-gray-700/50 text-gray-900 dark:text-gray-100
                                         placeholder-gray-400 dark:placeholder-gray-500
                                         shadow-sm focus:border-violet-500 focus:ring-violet-500 transition"
                                  placeholder="Short summary (optional)...">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <x-input-error :messages="$message" class="mt-1" />
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="content">Content <span class="text-red-500">*</span></x-input-label>
                        <textarea id="content" name="content" rows="12"
                                  class="mt-1.5 w-full rounded-xl border border-gray-300 dark:border-gray-600
                                         bg-white dark:bg-gray-700/50 text-gray-900 dark:text-gray-100
                                         placeholder-gray-400 dark:placeholder-gray-500
                                         shadow-sm focus:border-violet-500 focus:ring-violet-500 transition
                                         @error('content') border-red-400 dark:border-red-500 @enderror"
                                  placeholder="Write your post content here..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <x-input-error :messages="$message" class="mt-1" />
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="status">Status <span class="text-red-500">*</span></x-input-label>
                        <select id="status" name="status"
                                class="mt-1.5 w-full rounded-xl border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700/50 text-gray-900 dark:text-gray-100
                                       shadow-sm focus:border-violet-500 focus:ring-violet-500 transition
                                       @error('status') border-red-400 @enderror">
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <x-input-error :messages="$message" class="mt-1" />
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                        <x-secondary-button tag="a" href="{{ route('posts.index') }}">
                            Cancel
                        </x-secondary-button>
                        <x-primary-button
                            x-data
                            x-on:click="$el.disabled = true; $el.textContent = 'Creating…'; $el.closest('form').submit()">
                            Create Post
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
