<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    contactLists: {
        type: Object,
        default: () => ({ data: [] })
    }
});
</script>

<template>
    <Head title="Contact Lists" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Contact Lists
                </h2>
                <Link
                    :href="route('contact-lists.create')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                >
                    Create List
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="contactLists.data.length === 0" class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        No contact lists found. Create your first list to get started.
                    </div>
                </div>

                <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="list in contactLists.data"
                        :key="list.id"
                        class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200"
                    >
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ list.name }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" v-if="list.description">
                                        {{ list.description }}
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <Link
                                        :href="route('contact-lists.edit', list.id)"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </Link>
                                    <Link
                                        :href="route('contact-lists.destroy', list.id)"
                                        method="delete"
                                        as="button"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </Link>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                    {{ list.contacts_count || 0 }} contacts
                                </div>
                                <Link
                                    :href="route('contacts.index', { list: list.id })"
                                    class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium"
                                >
                                    View Contacts →
                                </Link>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>Created {{ list.created_at }}</span>
                                    <span
                                        class="px-2 py-1 rounded-full font-semibold"
                                        :class="{
                                            'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100': list.name === 'Standard',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100': list.name === 'VIP',
                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100': list.name !== 'Standard' && list.name !== 'VIP'
                                        }"
                                    >
                                        {{ list.name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
