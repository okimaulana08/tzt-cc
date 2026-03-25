<template>
    <Teleport to="body">
        <div
            v-if="show"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            @click.self="$emit('close')"
        >
            <div class="absolute inset-0 bg-black/40" />
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Buat Task Baru</h2>
                    <button class="p-1 rounded-lg hover:bg-gray-100 text-gray-500" @click="$emit('close')">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form class="px-6 py-4 space-y-4" @submit.prevent="submit">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                        <input
                            v-model="form.title"
                            ref="titleInput"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="form.errors.title ? 'border-red-300' : 'border-gray-300'"
                            placeholder="Judul task..."
                            autofocus
                        />
                        <p v-if="form.errors.title" class="text-xs text-red-500 mt-1">{{ form.errors.title }}</p>
                    </div>

                    <!-- Status & Priority -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div class="flex flex-wrap gap-1">
                                <button
                                    v-for="s in statuses"
                                    :key="s.value"
                                    type="button"
                                    class="px-2 py-1 rounded-full text-xs font-medium border transition"
                                    :class="form.status === s.value
                                        ? 'text-white border-transparent'
                                        : 'text-gray-600 border-gray-200 hover:border-gray-300'"
                                    :style="form.status === s.value ? { backgroundColor: s.color } : {}"
                                    @click="form.status = s.value"
                                >
                                    {{ s.label }}
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <div class="flex flex-wrap gap-1">
                                <button
                                    v-for="p in priorities"
                                    :key="p.value"
                                    type="button"
                                    class="px-2 py-1 rounded-full text-xs font-medium border transition"
                                    :class="form.priority === p.value
                                        ? 'text-white border-transparent'
                                        : 'text-gray-600 border-gray-200 hover:border-gray-300'"
                                    :style="form.priority === p.value ? { backgroundColor: p.color } : {}"
                                    @click="form.priority = p.value"
                                >
                                    {{ p.label }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Assignee & Sprint -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assignee</label>
                            <select
                                v-model="form.assignee_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option :value="null">— Unassigned —</option>
                                <option v-for="m in members" :key="m.id" :value="m.id">{{ m.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sprint</label>
                            <select
                                v-model="form.sprint_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option :value="null">— No Sprint —</option>
                                <option v-for="s in sprints" :key="s.id" :value="s.id">{{ s.name }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Due date & Estimated hours -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                            <input
                                v-model="form.due_date"
                                type="date"
                                :min="today"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <p v-if="form.errors.due_date" class="text-xs text-red-500 mt-1">{{ form.errors.due_date }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estimasi (jam)</label>
                            <input
                                v-model.number="form.estimated_hours"
                                type="number"
                                min="1"
                                max="999"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="0"
                            />
                        </div>
                    </div>

                    <!-- Labels -->
                    <div v-if="labels.length">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Labels</label>
                        <div class="flex flex-wrap gap-1">
                            <button
                                v-for="label in labels"
                                :key="label.id"
                                type="button"
                                class="px-2 py-1 rounded-full text-xs font-medium border transition"
                                :class="selectedLabelIds.includes(label.id)
                                    ? 'text-white border-transparent'
                                    : 'text-gray-600 border-gray-200 hover:border-gray-300'"
                                :style="selectedLabelIds.includes(label.id) ? { backgroundColor: label.color } : {}"
                                @click="toggleLabel(label.id)"
                            >
                                {{ label.name }}
                            </button>
                        </div>
                    </div>

                    <!-- GitHub -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">GitHub Branch</label>
                            <input
                                v-model="form.github_branch"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="feature/my-branch"
                            />
                            <p v-if="form.errors.github_branch" class="text-xs text-red-500 mt-1">{{ form.errors.github_branch }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PR URL</label>
                            <input
                                v-model="form.github_pr_url"
                                type="url"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="https://github.com/..."
                            />
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            placeholder="Deskripsi task..."
                        />
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <button
                            type="button"
                            class="px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition"
                            @click="$emit('close')"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Buat Task' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { computed, onMounted, onUnmounted, reactive, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    show: { type: Boolean, default: false },
    project: { type: Object, required: true },
    statuses: { type: Array, default: () => [] },
    members: { type: Array, default: () => [] },
    labels: { type: Array, default: () => [] },
    sprints: { type: Array, default: () => [] },
    defaultStatus: { type: String, default: 'backlog' },
})

const emit = defineEmits(['close', 'created'])

const priorities = [
    { value: 'urgent', label: 'Urgent', color: '#A32D2D' },
    { value: 'high', label: 'High', color: '#BA7517' },
    { value: 'medium', label: 'Medium', color: '#185FA5' },
    { value: 'low', label: 'Low', color: '#5F5E5A' },
]

const today = new Date().toISOString().split('T')[0]
const selectedLabelIds = ref([])

const form = useForm({
    title: '',
    description: null,
    status: props.defaultStatus,
    priority: 'medium',
    assignee_id: null,
    sprint_id: null,
    due_date: null,
    estimated_hours: null,
    label_ids: [],
    github_branch: null,
    github_pr_url: null,
    parent_id: null,
})

watch(() => props.defaultStatus, (val) => { form.status = val })

function toggleLabel(id) {
    const idx = selectedLabelIds.value.indexOf(id)
    if (idx === -1) {
        selectedLabelIds.value.push(id)
    } else {
        selectedLabelIds.value.splice(idx, 1)
    }
    form.label_ids = [...selectedLabelIds.value]
}

function submit() {
    form.post(route('projects.tasks.store', { project: props.project.id }), {
        preserveScroll: true,
        onSuccess: () => {
            emit('created')
            emit('close')
            form.reset()
            selectedLabelIds.value = []
        },
    })
}

function onKeydown(e) {
    if (e.key === 'Escape') emit('close')
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') submit()
}

onMounted(() => window.addEventListener('keydown', onKeydown))
onUnmounted(() => window.removeEventListener('keydown', onKeydown))
</script>
