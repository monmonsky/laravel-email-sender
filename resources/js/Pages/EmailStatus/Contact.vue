<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    contact: {
        type: Object,
        required: true
    },
    logs: {
        type: Array,
        default: () => []
    }
});

const getStatusBadgeClass = (status) => {
    const classes = {
        sent: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        failed: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};
</script>

<template>
    <Head :title="`Email Status - ${contact.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link
                    :href="route('email-status.index')"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                >
                    ← Back
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Email Status for {{ contact.name }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Contact Info -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Name</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ contact.name }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Email</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ contact.email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Email History -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Email History</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Campaign
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Subject
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Sent At
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Opened At
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Clicked At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    <tr v-for="log in logs" :key="log.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ log.campaign_name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ log.campaign_subject }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusBadgeClass(log.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ log.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ log.sent_at }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <span v-if="log.opened_at" class="text-green-600 dark:text-green-400">{{ log.opened_at }}</span>
                                            <span v-else class="text-gray-400">-</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <span v-if="log.clicked_at" class="text-blue-600 dark:text-blue-400">{{ log.clicked_at }}</span>
                                            <span v-else class="text-gray-400">-</span>
                                        </td>
                                    </tr>
                                    <tr v-if="logs.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No emails sent to this contact yet.
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
