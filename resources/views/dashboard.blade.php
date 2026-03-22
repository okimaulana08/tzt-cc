<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-gray-800 dark:text-gray-200">Dashboard</h1>
    </x-slot>

    <div class="p-6 space-y-6">

        {{-- Welcome Banner --}}
        <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg
                    bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-700">
            <!-- Decorative circles -->
            <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full bg-white/10 blur-xl"></div>
            <div class="absolute -bottom-10 -left-6 w-32 h-32 rounded-full bg-white/5 blur-lg"></div>
            <div class="relative flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                    <p class="mt-1.5 text-violet-200 text-sm">
                        Signed in as
                        @foreach (Auth::user()->getRoleNames() as $role)
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm ml-1">
                                {{ $role }}
                            </span>
                        @endforeach
                    </p>
                </div>
                <div class="shrink-0 w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm
                            flex items-center justify-center text-2xl font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            {{-- Total Posts --}}
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-violet-50 to-transparent dark:from-violet-900/10 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative flex items-center justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl bg-violet-100 dark:bg-violet-900/40
                                flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">All time</span>
                </div>
                <p class="relative text-3xl font-bold text-gray-900 dark:text-white">
                    {{ \App\Models\Post::count() }}
                </p>
                <p class="relative text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Total Posts</p>
            </div>

            {{-- Published --}}
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-transparent dark:from-emerald-900/10 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative flex items-center justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 dark:bg-emerald-900/40
                                flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Live
                    </span>
                </div>
                <p class="relative text-3xl font-bold text-gray-900 dark:text-white">
                    {{ \App\Models\Post::where('status', 'published')->count() }}
                </p>
                <p class="relative text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Published</p>
            </div>

            {{-- Drafts --}}
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-50 to-transparent dark:from-amber-900/10 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative flex items-center justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 dark:bg-amber-900/40
                                flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2 py-0.5 rounded-full">Draft</span>
                </div>
                <p class="relative text-3xl font-bold text-gray-900 dark:text-white">
                    {{ \App\Models\Post::where('status', 'draft')->count() }}
                </p>
                <p class="relative text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Drafts</p>
            </div>

        </div>

        {{-- Quick Actions --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Quick Actions</h4>
            <div class="flex flex-wrap gap-3">

                <a href="{{ route('posts.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                          bg-violet-50 text-violet-700 hover:bg-violet-100
                          dark:bg-violet-900/30 dark:text-violet-300 dark:hover:bg-violet-900/50
                          transition-colors duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    All Posts
                </a>

                @can('create', \App\Models\Post::class)
                    <a href="{{ route('posts.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                              bg-gradient-to-r from-violet-600 to-purple-600 text-white
                              hover:from-violet-700 hover:to-purple-700
                              shadow-sm hover:shadow-md transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Post
                    </a>
                @endcan

                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                          bg-gray-50 text-gray-700 hover:bg-gray-100
                          dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600
                          transition-colors duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    My Profile
                </a>

            </div>
        </div>

    </div>
</x-app-layout>
