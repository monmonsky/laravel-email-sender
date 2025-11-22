<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    campaign: {
        type: Object,
        required: true
    },
    subject: {
        type: String,
        required: true
    },
    content: {
        type: String,
        required: true
    },
    senderName: {
        type: String,
        default: ''
    },
    previewContact: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <Head :title="`Preview - ${campaign.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('campaigns.index')"
                        class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                    >
                        ← Back
                    </Link>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        Email Preview: {{ campaign.name }}
                    </h2>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <!-- Preview Info -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h3 class="font-semibold text-blue-900 dark:text-blue-100">Preview Mode</h3>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                This is how your email will look when sent. Variables have been replaced with data from: <strong>{{ previewContact.name }}</strong> ({{ previewContact.email }})
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Email Preview Container -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                    <!-- Email Headers -->
                    <div class="border-b border-gray-200 dark:border-gray-700 p-6">
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 w-20">From:</span>
                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ senderName || 'Email Campaign Manager' }}</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 w-20">To:</span>
                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ previewContact.email }}</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 w-20">Subject:</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ subject }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Email Body Preview -->
                    <div class="p-6 bg-gray-50 dark:bg-gray-900">
                        <div class="max-w-3xl mx-auto">
                            <!-- Email Template Preview -->
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                <!-- Header -->
                                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-center">
                                    <div class="inline-block mb-4">
                                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                    </div>
                                    <h1 class="text-2xl font-bold text-white">Email Campaign</h1>
                                </div>

                                <!-- Content -->
                                <div class="p-8">
                                    <div v-html="content" class="prose prose-sm max-w-none dark:prose-invert"></div>
                                </div>

                                <!-- Footer -->
                                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 text-center">
                                    <p class="text-sm text-gray-600 mb-2">You received this email because you're subscribed to our mailing list.</p>
                                    <p class="text-sm text-gray-500">
                                        <a href="#" class="text-indigo-600 hover:underline">Unsubscribe</a> |
                                        <a href="#" class="text-indigo-600 hover:underline">Update Preferences</a>
                                    </p>
                                    <p class="text-xs text-gray-400 mt-4">
                                        &copy; {{ new Date().getFullYear() }} {{ senderName || 'Email Campaign Manager' }}. All rights reserved.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-4">
                    <Link
                        :href="route('campaigns.edit', campaign.id)"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    >
                        Edit Campaign
                    </Link>
                    <Link
                        :href="route('campaigns.index')"
                        method="post"
                        as="button"
                        :data="{ campaign_id: campaign.id }"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Send Campaign
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.prose {
    color: #374151;
}

.prose h1,
.prose h2,
.prose h3 {
    color: #1f2937;
    font-weight: 700;
}

.prose a {
    color: #667eea;
    text-decoration: none;
}

.prose a:hover {
    text-decoration: underline;
}

.prose p {
    margin: 1em 0;
}

.prose ul,
.prose ol {
    padding-left: 1.5em;
}
</style>
