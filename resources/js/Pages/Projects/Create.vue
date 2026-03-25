<template>
    <AppLayout>
        <div class="py-8 px-6 max-w-xl mx-auto">
            <h1 class="text-xl font-semibold text-gray-900 mb-6">Buat Project Baru</h1>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form class="space-y-4" @submit.prevent="submit">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Project <span class="text-red-500">*</span></label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="form.errors.name ? 'border-red-300' : 'border-gray-300'"
                            placeholder="E.g. E-Commerce Revamp"
                        />
                        <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            placeholder="Deskripsi singkat project..."
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Warna</label>
                        <input v-model="form.color" type="color" class="h-9 w-16 rounded border border-gray-300 cursor-pointer" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <Link :href="route('projects.index')" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Buat Project' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    description: '',
    color: '#378ADD',
})

function submit() {
    form.post(route('projects.store'))
}
</script>
