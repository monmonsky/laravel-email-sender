<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    campaigns: {
        type: Object,
        default: () => ({ data: [] })
    }
});

const sendCampaign = (id) => {
    if (confirm('Are you sure you want to send this campaign? This will send emails to all active contacts in the selected list.')) {
        router.post(route('campaigns.send', id));
    }
};
</script>

<template>
    <Head title="Campaigns" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Campaigns
                </h2>
                <Link
                    :href="route('campaigns.create')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                >
                    Create Campaign
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Subject
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Contact List
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Created
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    <tr v-if="campaigns.data.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No campaigns found. Create your first campaign to get started.
                                        </td>
                                    </tr>
                                    <tr v-for="campaign in campaigns.data" :key="campaign.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ campaign.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ campaign.subject }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100">
                                                {{ campaign.contact_list || 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100': campaign.status === 'draft',
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100': campaign.status === 'scheduled',
                                                    'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100': campaign.status === 'sent'
                                                }"
                                            >
                                                {{ campaign.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ campaign.created_at }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button
                                                v-if="campaign.status !== 'sent'"
                                                @click="sendCampaign(campaign.id)"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 mr-3"
                                            >
                                                Send
                                            </button>
                                            <Link
                                                :href="route('campaigns.edit', campaign.id)"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3"
                                            >
                                                Edit
                                            </Link>
                                            <Link
                                                :href="route('campaigns.destroy', campaign.id)"
                                                method="delete"
                                                as="button"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            >
                                                Delete
                                            </Link>
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
