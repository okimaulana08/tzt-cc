<template>
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside
            :class="[
                'flex flex-col bg-gray-900 text-gray-100 transition-all duration-300 z-30',
                sidebarOpen ? 'w-52' : 'w-0 overflow-hidden md:w-52',
            ]"
        >
            <!-- Logo -->
            <div class="flex items-center gap-2 px-4 py-4 border-b border-gray-700">
                <div class="w-7 h-7 rounded-lg bg-blue-500 flex items-center justify-center font-bold text-white text-sm">
                    D
                </div>
                <span class="font-semibold text-white">DevFlow</span>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-1">
                <Link
                    :href="route('dashboard')"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition"
                    :class="{ 'bg-gray-800 text-white': $page.url === '/' }"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </Link>

                <Link
                    :href="route('projects.index')"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition"
                    :class="{ 'bg-gray-800 text-white': $page.url.startsWith('/projects') }"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Projects
                </Link>
            </nav>

            <!-- User info -->
            <div class="px-4 py-3 border-t border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-xs font-medium text-white">
                        {{ userInitials }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-white truncate">{{ auth.user?.name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth.user?.email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="h-12 bg-white border-b border-gray-200 flex items-center px-4 gap-3 shrink-0">
                <button
                    class="md:hidden p-1 rounded text-gray-500 hover:bg-gray-100"
                    @click="sidebarOpen = !sidebarOpen"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex-1" />
                <slot name="header-actions" />
            </header>

            <!-- Flash messages -->
            <div v-if="flash.success || flash.error" class="px-6 pt-4">
                <div
                    v-if="flash.success"
                    class="flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800"
                >
                    <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ flash.success }}
                </div>
                <div
                    v-if="flash.error"
                    class="flex items-center gap-2 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800"
                >
                    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    {{ flash.error }}
                </div>
            </div>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { computed, provide, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const auth = computed(() => page.props.auth)
const flash = computed(() => page.props.flash)
const sidebarOpen = ref(true)

const userInitials = computed(() => {
    const name = auth.value.user?.name ?? ''
    return name
        .split(' ')
        .slice(0, 2)
        .map((n) => n[0]?.toUpperCase() ?? '')
        .join('')
})

provide('auth', auth)
</script>
