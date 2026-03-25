<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: localStorage.getItem('darkMode') === 'true',
          sidebarOpen: false
      }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-200">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar overlay (mobile) -->
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-20 bg-black/60 backdrop-blur-sm lg:hidden"
         style="display:none;"></div>

    <!-- Sidebar -->
    @include('layouts.navigation')

    <!-- Main area -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

        <!-- Top bar -->
        <header class="h-16 shrink-0 flex items-center gap-3 px-4 sm:px-6
                        bg-white/80 dark:bg-gray-900/80 backdrop-blur-md
                        border-b border-gray-200 dark:border-gray-800
                        shadow-sm z-10">

            <!-- Mobile sidebar toggle -->
            <button @click="sidebarOpen = !sidebarOpen"
                    aria-label="Toggle sidebar"
                    class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100
                           dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Page heading slot -->
            <div class="flex-1 min-w-0">
                @isset($header)
                    {{ $header }}
                @endisset
            </div>

            <!-- Right actions -->
            <div class="flex items-center gap-1">

                <!-- Dark / light toggle -->
                <button @click="darkMode = !darkMode"
                        aria-label="Toggle dark mode"
                        class="relative p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100
                               dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800 transition">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <!-- User dropdown -->
                @auth
                <x-dropdown align="right" width="56" contentClasses="py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 pl-1 pr-2 py-1.5 rounded-lg
                                       hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-purple-600
                                        flex items-center justify-center text-white text-sm font-bold shrink-0 shadow">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:block text-sm font-semibold text-gray-700 dark:text-gray-300 max-w-[120px] truncate">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-0.5">Signed in as</p>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            <svg class="w-4 h-4 mr-2 inline-block opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();">
                                <svg class="w-4 h-4 mr-2 inline-block opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @endauth
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>

</div>

</body>
</html>
