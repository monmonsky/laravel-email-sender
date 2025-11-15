<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    senders: Array,
});

const deleteSender = (id) => {
    if (confirm('Are you sure you want to delete this sender?')) {
        router.delete(route('senders.destroy', id));
    }
};

const setDefault = (id) => {
    router.post(route('senders.set-default', id));
};
</script>

<template>
    <Head title="Senders" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    SMTP Senders
                </h2>
                <Link
                    :href="route('senders.create')"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                >
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Sender
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="senders.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">No senders</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new SMTP sender.</p>
                            <div class="mt-6">
                                <Link
                                    :href="route('senders.create')"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                                >
                                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Sender
                                </Link>
                            </div>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            From Email
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            SMTP Host
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Port
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Encryption
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                    <tr v-for="sender in senders" :key="sender.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ sender.name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ sender.from_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ sender.from_email }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ sender.smtp_host }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ sender.smtp_port }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            <span class="inline-flex rounded-full px-2 text-xs font-semibold uppercase leading-5"
                                                :class="{
                                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': sender.smtp_encryption === 'tls',
                                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': sender.smtp_encryption === 'ssl',
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': !sender.smtp_encryption
                                                }">
                                                {{ sender.smtp_encryption || 'none' }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span v-if="sender.is_default" class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                Default
                                            </span>
                                            <button
                                                v-else
                                                @click="setDefault(sender.id)"
                                                class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                            >
                                                Set as Default
                                            </button>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                            <Link
                                                :href="route('senders.edit', sender.id)"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-4"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                @click="deleteSender(sender.id)"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                :disabled="sender.is_default"
                                                :class="{ 'opacity-50 cursor-not-allowed': sender.is_default }"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
