<template>
    <AppLayout>
        <div class="py-8 px-6 max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-semibold text-gray-900">Projects</h1>
                <Link
                    :href="route('projects.create')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition"
                >
                    + New Project
                </Link>
            </div>

            <div v-if="projects.length === 0" class="text-center py-16">
                <p class="text-gray-500 text-sm">Belum ada project. Buat yang pertama!</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link
                    v-for="project in projects"
                    :key="project.id"
                    :href="route('projects.board', { project: project.id })"
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition"
                >
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-9 h-9 rounded-lg flex items-center justify-center text-white font-bold text-sm"
                            :style="{ backgroundColor: project.color }"
                        >
                            {{ project.name[0]?.toUpperCase() }}
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">{{ project.name }}</h3>
                            <p class="text-xs text-gray-500">{{ project.tasks_count }} tasks · {{ project.members_count }} members</p>
                        </div>
                    </div>
                    <p v-if="project.description" class="text-sm text-gray-600 line-clamp-2">{{ project.description }}</p>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({ projects: { type: Array, default: () => [] } })
</script>
