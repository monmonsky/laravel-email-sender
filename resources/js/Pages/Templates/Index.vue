<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    templates: Array,
});

const deleteTemplate = (id) => {
    if (confirm('Are you sure you want to delete this template?')) {
        router.delete(route('templates.destroy', id));
    }
};

const duplicateTemplate = (id) => {
    router.post(route('templates.duplicate', id));
};
</script>

<template>
    <Head title="Templates" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Email Templates
                </h2>
                <Link
                    :href="route('templates.create')"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                >
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Template
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="templates.length === 0" class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">No templates</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new email template.</p>
                        <div class="mt-6">
                            <Link
                                :href="route('templates.create')"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                            >
                                <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create Template
                            </Link>
                        </div>
                    </div>
                </div>

                <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="template in templates"
                        :key="template.id"
                        class="group relative overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg transition-all hover:shadow-md"
                    >
                        <div class="p-6">
                            <div class="mb-3 flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ template.name }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 line-clamp-1">
                                        {{ template.subject }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                <div v-html="template.body" class="line-clamp-3 prose prose-sm dark:prose-invert max-w-none"></div>
                            </div>

                            <div class="mt-4 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ template.created_at }}</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-4 py-3 flex items-center justify-end gap-2">
                            <button
                                @click="duplicateTemplate(template.id)"
                                class="inline-flex items-center rounded-md bg-white dark:bg-gray-800 px-2.5 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700"
                            >
                                <svg class="mr-1 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Duplicate
                            </button>
                            <Link
                                :href="route('templates.edit', template.id)"
                                class="inline-flex items-center rounded-md bg-white dark:bg-gray-800 px-2.5 py-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700"
                            >
                                <svg class="mr-1 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </Link>
                            <button
                                @click="deleteTemplate(template.id)"
                                class="inline-flex items-center rounded-md bg-white dark:bg-gray-800 px-2.5 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700"
                            >
                                <svg class="mr-1 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
