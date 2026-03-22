<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 flex flex-col w-64
              bg-gray-900 dark:bg-gray-950
              transform transition-transform duration-300 ease-in-out
              lg:translate-x-0 lg:static lg:inset-auto lg:z-auto">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-5 h-16 shrink-0 border-b border-white/10">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-purple-700
                    flex items-center justify-center shadow-lg shrink-0">
            <x-application-logo class="w-5 h-5 fill-current text-white" />
        </div>
        <span class="text-white font-bold text-base tracking-tight">{{ config('app.name') }}</span>
    </div>

    <!-- Navigation links -->
    <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">

        <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Menu</p>

        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>{{ __('Dashboard') }}</span>
        </x-nav-link>

        <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*')">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>{{ __('Posts') }}</span>
        </x-nav-link>

    </nav>

    <!-- User info at bottom -->
    @auth
    <div class="px-3 py-4 shrink-0 border-t border-white/10">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 transition group">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-purple-600
                        flex items-center justify-center text-white text-sm font-bold shrink-0 shadow">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-white truncate leading-tight">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
            </div>
            <svg class="w-4 h-4 text-gray-500 group-hover:text-gray-400 shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    @endauth

</aside>
