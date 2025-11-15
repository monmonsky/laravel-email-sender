<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
    stats: Object,
    campaignStats: Object,
    recentCampaigns: Array,
    emailStats: Array,
    contactStats: Object,
});

const emailChartRef = ref(null);
const contactChartRef = ref(null);

onMounted(() => {
    // Email Statistics Chart
    if (emailChartRef.value && props.emailStats.length > 0) {
        const ctx = emailChartRef.value.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: props.emailStats.map(stat => new Date(stat.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                datasets: [
                    {
                        label: 'Sent',
                        data: props.emailStats.map(stat => stat.sent),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                    },
                    {
                        label: 'Opened',
                        data: props.emailStats.map(stat => stat.opened),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                    },
                    {
                        label: 'Clicked',
                        data: props.emailStats.map(stat => stat.clicked),
                        borderColor: 'rgb(168, 85, 247)',
                        backgroundColor: 'rgba(168, 85, 247, 0.1)',
                        tension: 0.4,
                    },
                    {
                        label: 'Failed',
                        data: props.emailStats.map(stat => stat.failed),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
                        },
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                        }
                    },
                    x: {
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
                        },
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                        }
                    }
                }
            }
        });
    }

    // Contact Status Chart
    if (contactChartRef.value) {
        const ctx = contactChartRef.value.getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Unsubscribed', 'Bounced'],
                datasets: [{
                    data: [
                        props.contactStats.active,
                        props.contactStats.unsubscribed,
                        props.contactStats.bounced,
                    ],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)',
                    ],
                    borderColor: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                    borderWidth: 2,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
                            padding: 15,
                        }
                    }
                }
            }
        });
    }
});

const getStatusColor = (status) => {
    const colors = {
        draft: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        scheduled: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        sent: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    };
    return colors[status] || colors.draft;
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Campaigns Card -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Campaigns</p>
                                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ stats.campaigns }}</p>
                                </div>
                                <div class="p-3 bg-blue-100 rounded-full dark:bg-blue-900">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4 flex gap-4 text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Draft: {{ campaignStats.draft }}</span>
                                <span class="text-gray-600 dark:text-gray-400">Sent: {{ campaignStats.sent }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contacts Card -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Contacts</p>
                                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ stats.contacts }}</p>
                                </div>
                                <div class="p-3 bg-green-100 rounded-full dark:bg-green-900">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <Link :href="route('contacts.index')" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    View all contacts →
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Lists Card -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Lists</p>
                                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ stats.contact_lists }}</p>
                                </div>
                                <div class="p-3 bg-purple-100 rounded-full dark:bg-purple-900">
                                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <Link :href="route('contact-lists.index')" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    Manage lists →
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Templates Card -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Templates</p>
                                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ stats.templates }}</p>
                                </div>
                                <div class="p-3 bg-yellow-100 rounded-full dark:bg-yellow-900">
                                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <Link :href="route('templates.create')" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    Create template →
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
                    <!-- Email Statistics Chart -->
                    <div class="lg:col-span-2 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Email Activity (Last 7 Days)</h3>
                            <div class="h-64">
                                <canvas ref="emailChartRef"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Status Chart -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Contact Status</h3>
                            <div class="h-64 flex items-center justify-center">
                                <canvas ref="contactChartRef"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Campaigns -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Campaigns</h3>
                            <Link :href="route('campaigns.index')" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                View all
                            </Link>
                        </div>

                        <div v-if="recentCampaigns.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                            Campaign
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                            Contact List
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                            Created
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    <tr v-for="campaign in recentCampaigns" :key="campaign.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link :href="route('campaigns.edit', campaign.id)" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ campaign.name }}
                                            </Link>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ campaign.contact_list }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusColor(campaign.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ campaign.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ campaign.created_at }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-else class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No campaigns yet</p>
                            <Link :href="route('campaigns.create')" class="mt-2 inline-block text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                Create your first campaign →
                            </Link>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
