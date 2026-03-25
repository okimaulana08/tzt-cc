<template>
    <AppLayout>
        <template #header-actions>
            <h1 class="text-sm font-semibold text-gray-700">{{ project.name }}</h1>
        </template>

        <div class="p-6 h-full flex flex-col">
            <!-- Filter bar -->
            <div class="flex items-center gap-3 mb-4 flex-wrap">
                <button
                    class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition"
                    @click="openCreate(null)"
                >
                    + New Task
                </button>

                <!-- Assignee filter -->
                <select
                    v-model="filterAssignee"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All Assignees</option>
                    <option v-for="m in members" :key="m.id" :value="m.id">{{ m.name }}</option>
                </select>

                <!-- Priority filter -->
                <select
                    v-model="filterPriority"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All Priorities</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>

                <!-- Label filter -->
                <select
                    v-if="labels.length"
                    v-model="filterLabel"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All Labels</option>
                    <option v-for="l in labels" :key="l.id" :value="l.id">{{ l.name }}</option>
                </select>
            </div>

            <!-- Kanban Board -->
            <div class="flex gap-4 overflow-x-auto pb-4 flex-1">
                <div
                    v-for="status in statuses"
                    :key="status.value"
                    class="flex flex-col w-72 shrink-0"
                    @dragover.prevent
                    @drop="onDrop($event, status.value)"
                >
                    <!-- Column header -->
                    <div class="flex items-center gap-2 mb-3">
                        <span
                            class="w-2.5 h-2.5 rounded-full"
                            :style="{ backgroundColor: status.color }"
                        />
                        <span class="text-sm font-medium text-gray-700">{{ status.label }}</span>
                        <span class="text-xs text-gray-400 bg-gray-100 rounded-full px-2 py-0.5 ml-1">
                            {{ filteredTasks(status.value).length }}
                        </span>
                        <button
                            class="ml-auto text-gray-400 hover:text-blue-600 transition"
                            title="Add task"
                            @click="openCreate(status.value)"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>

                    <!-- Tasks -->
                    <div class="flex flex-col gap-2 flex-1">
                        <TaskCard
                            v-for="task in filteredTasks(status.value)"
                            :key="task.id"
                            :task="task"
                            @dragstart="onDragStart($event, task)"
                            @dragend="dragging = null"
                        />

                        <!-- Empty state -->
                        <div
                            v-if="filteredTasks(status.value).length === 0"
                            class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center text-xs text-gray-400"
                        >
                            Drag task here
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Task Modal -->
        <CreateTaskModal
            :show="showCreateModal"
            :project="project"
            :statuses="statuses"
            :members="members"
            :labels="labels"
            :sprints="sprints"
            :default-status="createDefaultStatus"
            @close="showCreateModal = false"
            @created="onTaskCreated"
        />
    </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import TaskCard from '@/Components/Tasks/TaskCard.vue'
import CreateTaskModal from '@/Components/Tasks/CreateTaskModal.vue'

const props = defineProps({
    project: { type: Object, required: true },
    tasksByStatus: { type: Object, default: () => ({}) },
    statuses: { type: Array, default: () => [] },
    members: { type: Array, default: () => [] },
    labels: { type: Array, default: () => [] },
    sprints: { type: Array, default: () => [] },
})

const filterAssignee = ref('')
const filterPriority = ref('')
const filterLabel = ref('')
const showCreateModal = ref(false)
const createDefaultStatus = ref('backlog')
const dragging = ref(null)

function filteredTasks(statusValue) {
    return (props.tasksByStatus[statusValue] ?? []).filter((task) => {
        if (filterAssignee.value && task.assignee?.id !== filterAssignee.value) return false
        if (filterPriority.value && task.priority !== filterPriority.value) return false
        if (filterLabel.value && !task.labels?.some((l) => l.id === filterLabel.value)) return false
        return true
    })
}

function openCreate(status) {
    createDefaultStatus.value = status ?? 'backlog'
    showCreateModal.value = true
}

function onDragStart(event, task) {
    dragging.value = task
    event.dataTransfer.effectAllowed = 'move'
}

function onDrop(event, newStatus) {
    if (!dragging.value || dragging.value.status === newStatus) return

    router.patch(
        route('projects.tasks.updateStatus', {
            project: props.project.id,
            task: dragging.value.id,
        }),
        { status: newStatus },
        { preserveScroll: true }
    )
    dragging.value = null
}

function onTaskCreated() {
    // Inertia will refresh the page props via back() redirect
}
</script>
