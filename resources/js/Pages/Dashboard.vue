<template>
    <AppLayout>
        <div class="py-8 px-6 max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
                <Link
                    :href="route('projects.create')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition"
                >
                    + New Project
                </Link>
            </div>

            <!-- Empty state -->
            <div v-if="projects.length === 0" class="text-center py-16">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-gray-900 font-medium mb-1">Belum ada project</h3>
                <p class="text-sm text-gray-500 mb-4">Buat project pertama kamu untuk mulai bekerja</p>
                <Link
                    :href="route('projects.create')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition"
                >
                    Buat Project
                </Link>
            </div>

            <!-- Project grid -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link
                    v-for="project in projects"
                    :key="project.id"
                    :href="route('projects.board', { project: project.id })"
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition group"
                >
                    <div class="flex items-start gap-3 mb-3">
                        <div
                            class="w-9 h-9 rounded-lg flex items-center justify-center text-white font-bold text-sm shrink-0"
                            :style="{ backgroundColor: project.color }"
                        >
                            {{ project.name[0]?.toUpperCase() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-900 truncate group-hover:text-blue-600 transition">
                                {{ project.name }}
                            </h3>
                            <p class="text-xs text-gray-500 truncate">{{ project.description ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span>{{ project.tasks_count }} tasks</span>
                        <span>{{ project.members_count }} members</span>
                    </div>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    projects: { type: Array, default: () => [] },
})
</script>
