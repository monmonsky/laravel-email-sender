<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({})
    }
});

const form = useForm({
    app_name: props.settings.app_name || 'Email Sender',
    default_sender_email: props.settings.default_sender_email || '',
    default_sender_name: props.settings.default_sender_name || '',
    emails_per_hour: props.settings.emails_per_hour || 100,
    enable_tracking: props.settings.enable_tracking !== undefined ? props.settings.enable_tracking : true,
    enable_analytics: props.settings.enable_analytics !== undefined ? props.settings.enable_analytics : true,
});

const submit = () => {
    form.put(route('settings.update'));
};
</script>

<template>
    <Head title="Settings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Application Settings
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <!-- General Settings -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">General Settings</h3>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="app_name" value="Application Name" />
                                <TextInput
                                    id="app_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.app_name"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.app_name" />
                            </div>

                            <div>
                                <InputLabel for="default_sender_name" value="Default Sender Name" />
                                <TextInput
                                    id="default_sender_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.default_sender_name"
                                />
                                <InputError class="mt-2" :message="form.errors.default_sender_name" />
                            </div>

                            <div>
                                <InputLabel for="default_sender_email" value="Default Sender Email" />
                                <TextInput
                                    id="default_sender_email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.default_sender_email"
                                />
                                <InputError class="mt-2" :message="form.errors.default_sender_email" />
                            </div>

                            <div>
                                <InputLabel for="emails_per_hour" value="Emails Per Hour Limit" />
                                <TextInput
                                    id="emails_per_hour"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.emails_per_hour"
                                    min="1"
                                />
                                <InputError class="mt-2" :message="form.errors.emails_per_hour" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Maximum number of emails that can be sent per hour
                                </p>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input
                                        id="enable_tracking"
                                        type="checkbox"
                                        v-model="form.enable_tracking"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                    />
                                    <InputLabel for="enable_tracking" value="Enable Email Tracking" class="ms-2" />
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 ml-6">
                                    Track email opens and clicks
                                </p>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input
                                        id="enable_analytics"
                                        type="checkbox"
                                        v-model="form.enable_analytics"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                    />
                                    <InputLabel for="enable_analytics" value="Enable Analytics" class="ms-2" />
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 ml-6">
                                    Collect campaign performance analytics
                                </p>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <PrimaryButton :disabled="form.processing">
                                    Save Settings
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
