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
    const isDark = document.documentElement.classList.contains('dark');

    // Email Statistics Chart - Modern Design
    if (emailChartRef.value && props.emailStats.length > 0) {
        const ctx = emailChartRef.value.getContext('2d');

        // Create gradient for Sent
        const gradientSent = ctx.createLinearGradient(0, 0, 0, 400);
        gradientSent.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
        gradientSent.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

        // Create gradient for Opened
        const gradientOpened = ctx.createLinearGradient(0, 0, 0, 400);
        gradientOpened.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
        gradientOpened.addColorStop(1, 'rgba(59, 130, 246, 0.01)');

        // Create gradient for Clicked
        const gradientClicked = ctx.createLinearGradient(0, 0, 0, 400);
        gradientClicked.addColorStop(0, 'rgba(168, 85, 247, 0.4)');
        gradientClicked.addColorStop(1, 'rgba(168, 85, 247, 0.01)');

        // Create gradient for Failed
        const gradientFailed = ctx.createLinearGradient(0, 0, 0, 400);
        gradientFailed.addColorStop(0, 'rgba(239, 68, 68, 0.4)');
        gradientFailed.addColorStop(1, 'rgba(239, 68, 68, 0.01)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: props.emailStats.map(stat => new Date(stat.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                datasets: [
                    {
                        label: 'Sent',
                        data: props.emailStats.map(stat => stat.sent),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: gradientSent,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(16, 185, 129)',
                        pointHoverBorderWidth: 3,
                    },
                    {
                        label: 'Opened',
                        data: props.emailStats.map(stat => stat.opened),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: gradientOpened,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(59, 130, 246)',
                        pointHoverBorderWidth: 3,
                    },
                    {
                        label: 'Clicked',
                        data: props.emailStats.map(stat => stat.clicked),
                        borderColor: 'rgb(168, 85, 247)',
                        backgroundColor: gradientClicked,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(168, 85, 247)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(168, 85, 247)',
                        pointHoverBorderWidth: 3,
                    },
                    {
                        label: 'Failed',
                        data: props.emailStats.map(stat => stat.failed),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: gradientFailed,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(239, 68, 68)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(239, 68, 68)',
                        pointHoverBorderWidth: 3,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: isDark ? '#d1d5db' : '#374151',
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '500',
                                family: "'Inter', 'system-ui', 'sans-serif'"
                            },
                            usePointStyle: true,
                            pointStyle: 'circle',
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: isDark ? 'rgba(31, 41, 55, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                        titleColor: isDark ? '#f3f4f6' : '#111827',
                        bodyColor: isDark ? '#d1d5db' : '#374151',
                        borderColor: isDark ? '#4b5563' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        bodyFont: {
                            size: 13,
                            weight: '500'
                        },
                        titleFont: {
                            size: 13,
                            weight: '600'
                        },
                        cornerRadius: 8,
                        boxPadding: 6,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        border: {
                            display: false
                        },
                        grid: {
                            color: isDark ? 'rgba(75, 85, 99, 0.3)' : 'rgba(229, 231, 235, 0.8)',
                            drawTicks: false,
                        },
                        ticks: {
                            color: isDark ? '#9ca3af' : '#6b7280',
                            padding: 10,
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    },
                    x: {
                        border: {
                            display: false
                        },
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: isDark ? '#9ca3af' : '#6b7280',
                            padding: 10,
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart',
                }
            }
        });
    }

    // Contact Status Chart - Modern Doughnut Design
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
                        'rgb(16, 185, 129)',
                        'rgb(251, 191, 36)',
                        'rgb(239, 68, 68)',
                    ],
                    borderColor: isDark ? '#1f2937' : '#fff',
                    borderWidth: 4,
                    hoverOffset: 15,
                    hoverBorderColor: isDark ? '#374151' : '#f9fafb',
                    hoverBorderWidth: 4,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: isDark ? '#d1d5db' : '#374151',
                            padding: 20,
                            font: {
                                size: 12,
                                weight: '500',
                                family: "'Inter', 'system-ui', 'sans-serif'"
                            },
                            usePointStyle: true,
                            pointStyle: 'circle',
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: isDark ? 'rgba(31, 41, 55, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                        titleColor: isDark ? '#f3f4f6' : '#111827',
                        bodyColor: isDark ? '#d1d5db' : '#374151',
                        borderColor: isDark ? '#4b5563' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        bodyFont: {
                            size: 13,
                            weight: '500'
                        },
                        titleFont: {
                            size: 13,
                            weight: '600'
                        },
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1000,
                    easing: 'easeInOutQuart',
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
                    <div class="group overflow-hidden bg-gradient-to-br from-white to-gray-50 shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl dark:from-gray-800 dark:to-gray-800/80 border border-gray-100 dark:border-gray-700/50">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Campaigns</p>
                                    <p class="mt-3 text-4xl font-bold bg-gradient-to-r from-blue-600 to-blue-500 bg-clip-text text-transparent dark:from-blue-400 dark:to-blue-300">{{ stats.campaigns }}</p>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg group-hover:shadow-blue-500/50 group-hover:scale-110 transition-all duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-5 flex gap-4 text-sm font-medium">
                                <span class="text-gray-600 dark:text-gray-400">Draft: <span class="text-gray-900 dark:text-gray-200">{{ campaignStats.draft }}</span></span>
                                <span class="text-gray-600 dark:text-gray-400">Sent: <span class="text-gray-900 dark:text-gray-200">{{ campaignStats.sent }}</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Contacts Card -->
                    <div class="group overflow-hidden bg-gradient-to-br from-white to-gray-50 shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl dark:from-gray-800 dark:to-gray-800/80 border border-gray-100 dark:border-gray-700/50">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Active Contacts</p>
                                    <p class="mt-3 text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-500 bg-clip-text text-transparent dark:from-green-400 dark:to-emerald-300">{{ stats.contacts }}</p>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg group-hover:shadow-green-500/50 group-hover:scale-110 transition-all duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-5">
                                <Link :href="route('contacts.index')" class="inline-flex items-center text-sm font-semibold text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 group-hover:translate-x-1 transition-transform duration-200">
                                    View all contacts →
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Lists Card -->
                    <div class="group overflow-hidden bg-gradient-to-br from-white to-gray-50 shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl dark:from-gray-800 dark:to-gray-800/80 border border-gray-100 dark:border-gray-700/50">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Contact Lists</p>
                                    <p class="mt-3 text-4xl font-bold bg-gradient-to-r from-purple-600 to-violet-500 bg-clip-text text-transparent dark:from-purple-400 dark:to-violet-300">{{ stats.contact_lists }}</p>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl shadow-lg group-hover:shadow-purple-500/50 group-hover:scale-110 transition-all duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-5">
                                <Link :href="route('contact-lists.index')" class="inline-flex items-center text-sm font-semibold text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 group-hover:translate-x-1 transition-transform duration-200">
                                    Manage lists →
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Templates Card -->
                    <div class="group overflow-hidden bg-gradient-to-br from-white to-gray-50 shadow-lg hover:shadow-xl transition-all duration-300 sm:rounded-xl dark:from-gray-800 dark:to-gray-800/80 border border-gray-100 dark:border-gray-700/50">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email Templates</p>
                                    <p class="mt-3 text-4xl font-bold bg-gradient-to-r from-amber-600 to-yellow-500 bg-clip-text text-transparent dark:from-amber-400 dark:to-yellow-300">{{ stats.templates }}</p>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-2xl shadow-lg group-hover:shadow-amber-500/50 group-hover:scale-110 transition-all duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-5">
                                <Link :href="route('templates.create')" class="inline-flex items-center text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300 group-hover:translate-x-1 transition-transform duration-200">
                                    Create template →
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
                    <!-- Email Statistics Chart -->
                    <div class="lg:col-span-2 overflow-hidden bg-gradient-to-br from-white to-gray-50 shadow-lg hover:shadow-xl transition-shadow duration-300 sm:rounded-xl dark:from-gray-800 dark:to-gray-800/80 border border-gray-100 dark:border-gray-700/50">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Email Activity</h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-gray-100 dark:bg-gray-700/50 px-3 py-1 rounded-full">Last 7 Days</span>
                            </div>
                            <div class="h-72 relative">
                                <canvas ref="emailChartRef"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Status Chart -->
                    <div class="overflow-hidden bg-gradient-to-br from-white to-gray-50 shadow-lg hover:shadow-xl transition-shadow duration-300 sm:rounded-xl dark:from-gray-800 dark:to-gray-800/80 border border-gray-100 dark:border-gray-700/50">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6">Contact Status</h3>
                            <div class="h-72 flex items-center justify-center">
                                <canvas ref="contactChartRef"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Campaigns -->
                <div class="overflow-hidden bg-gradient-to-br from-white to-gray-50 shadow-lg hover:shadow-xl transition-shadow duration-300 sm:rounded-xl dark:from-gray-800 dark:to-gray-800/80 border border-gray-100 dark:border-gray-700/50">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Recent Campaigns</h3>
                            <Link :href="route('campaigns.index')" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:translate-x-1 transition-transform duration-200">
                                View all →
                            </Link>
                        </div>

                        <div v-if="recentCampaigns.length > 0" class="overflow-x-auto -mx-6 px-6">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                                            Campaign
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                                            Contact List
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                                            Status
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                                            Created
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="campaign in recentCampaigns" :key="campaign.id" class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-white/50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link :href="route('campaigns.edit', campaign.id)" class="text-sm font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:underline">
                                                {{ campaign.name }}
                                            </Link>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-200">
                                            {{ campaign.contact_list }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusColor(campaign.status)" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full uppercase tracking-wide">
                                                {{ campaign.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 font-medium">
                                            {{ campaign.created_at }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-else class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 font-medium mb-3">No campaigns yet</p>
                            <Link :href="route('campaigns.create')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                Create your first campaign →
                            </Link>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
