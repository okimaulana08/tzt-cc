<template>
    <div
        class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm cursor-grab active:cursor-grabbing hover:shadow-md transition group"
        draggable="true"
        @dragstart="$emit('dragstart', task)"
        @dragend="$emit('dragend')"
    >
        <!-- Priority dot + task code -->
        <div class="flex items-center gap-2 mb-2">
            <span
                class="w-2 h-2 rounded-full shrink-0"
                :style="{ backgroundColor: priorityColor }"
            />
            <span class="text-xs text-gray-400 font-mono">{{ task.task_code }}</span>
            <span v-if="task.is_overdue" class="ml-auto text-xs text-red-500 font-medium">Overdue</span>
        </div>

        <!-- Title -->
        <p class="text-sm font-medium text-gray-800 mb-2 line-clamp-2">{{ task.title }}</p>

        <!-- Labels -->
        <div v-if="task.labels?.length" class="flex flex-wrap gap-1 mb-2">
            <span
                v-for="label in task.labels"
                :key="label.id"
                class="px-1.5 py-0.5 rounded text-xs font-medium text-white"
                :style="{ backgroundColor: label.color }"
            >
                {{ label.name }}
            </span>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between mt-2">
            <!-- Subtask progress -->
            <span v-if="task.subtask_progress?.total > 0" class="text-xs text-gray-500">
                {{ task.subtask_progress.done }}/{{ task.subtask_progress.total }} subtasks
            </span>
            <span v-else-if="task.comments_count > 0" class="text-xs text-gray-500 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                {{ task.comments_count }}
            </span>
            <span v-else />

            <!-- Assignee avatar -->
            <div
                v-if="task.assignee"
                class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-xs text-white font-medium"
                :title="task.assignee.name"
            >
                {{ task.assignee.name[0]?.toUpperCase() }}
            </div>
        </div>

        <!-- Due date -->
        <div v-if="task.due_date" class="mt-1.5">
            <span
                class="text-xs"
                :class="task.is_overdue ? 'text-red-500' : 'text-gray-400'"
            >
                Due: {{ formatDate(task.due_date) }}
            </span>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    task: { type: Object, required: true },
})

defineEmits(['dragstart', 'dragend'])

const priorityColors = {
    urgent: '#A32D2D',
    high: '#BA7517',
    medium: '#185FA5',
    low: '#5F5E5A',
}

const priorityColor = computed(() => priorityColors[props.task.priority] ?? '#888')

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
}
</script>
