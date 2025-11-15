<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    logs: {
        type: Object,
        required: true
    },
    campaigns: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    stats: {
        type: Object,
        default: () => ({})
    }
});

const search = ref(props.filters.search || '');
const campaignFilter = ref(props.filters.campaign_id || '');
const statusFilter = ref(props.filters.status || '');

const applyFilters = () => {
    router.get(route('email-status.index'), {
        search: search.value,
        campaign_id: campaignFilter.value,
        status: statusFilter.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    search.value = '';
    campaignFilter.value = '';
    statusFilter.value = '';
    applyFilters();
};

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
    <Head title="Email Delivery Status" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Email Delivery Status
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
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

                <!-- Filters -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Search
                            </label>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Name or email..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                @keyup.enter="applyFilters"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Campaign
                            </label>
                            <select
                                v-model="campaignFilter"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">All Campaigns</option>
                                <option v-for="campaign in campaigns" :key="campaign.id" :value="campaign.id">
                                    {{ campaign.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <select
                                v-model="statusFilter"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">All Status</option>
                                <option value="sent">Sent</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button
                                @click="applyFilters"
                                class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition"
                            >
                                Filter
                            </button>
                            <button
                                @click="resetFilters"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 transition"
                            >
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Campaign
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Contact
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
                                            {{ log.campaign_name }}
                                        </td>
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
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No email logs found.
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
