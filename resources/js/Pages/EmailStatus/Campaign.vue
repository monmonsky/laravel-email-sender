<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    campaign: {
        type: Object,
        required: true
    },
    logs: {
        type: Object,
        required: true
    },
    stats: {
        type: Object,
        default: () => ({})
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
    <Head :title="`Email Status - ${campaign.name}`" />

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
                    Email Status for Campaign: {{ campaign.name }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Campaign Info -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Campaign Name</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ campaign.name }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Subject</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ campaign.subject }}</div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 gap-4 mb-6 md:grid-cols-3 lg:grid-cols-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ stats.total }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Sent</div>
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ stats.sent }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Pending</div>
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ stats.pending }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Failed</div>
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ stats.failed }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Opened</div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ stats.opened }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Clicked</div>
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ stats.clicked }}</div>
                    </div>
                </div>

                <!-- Delivery Details -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Delivery Details</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Contact Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Sent At
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Opened
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Clicked
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    <tr v-for="log in logs.data" :key="log.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ log.contact_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ log.contact_email }}
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
                                            <span v-if="log.opened_at" class="text-green-600 dark:text-green-400">✓ {{ log.opened_at }}</span>
                                            <span v-else class="text-gray-400">-</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <span v-if="log.clicked_at" class="text-blue-600 dark:text-blue-400">✓ {{ log.clicked_at }}</span>
                                            <span v-else class="text-gray-400">-</span>
                                        </td>
                                    </tr>
                                    <tr v-if="logs.data.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No emails sent for this campaign yet.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="logs.links && logs.links.length > 3" class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                Showing {{ logs.from }} to {{ logs.to }} of {{ logs.total }} results
                            </div>
                            <div class="flex gap-1">
                                <Link
                                    v-for="(link, index) in logs.links"
                                    :key="index"
                                    :href="link.url"
                                    :class="[
                                        'px-3 py-2 text-sm rounded',
                                        link.active
                                            ? 'bg-gray-800 text-white dark:bg-gray-200 dark:text-gray-800'
                                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600',
                                        !link.url && 'opacity-50 cursor-not-allowed'
                                    ]"
                                    v-html="link.label"
                                    :disabled="!link.url"
                                    preserve-scroll
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
