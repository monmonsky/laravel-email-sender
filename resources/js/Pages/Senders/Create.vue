<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    from_email: '',
    from_name: '',
    smtp_host: '',
    smtp_port: '587',
    smtp_username: '',
    smtp_password: '',
    smtp_encryption: 'tls',
    is_default: false,
});

const submit = () => {
    form.post(route('senders.store'));
};
</script>

<template>
    <Head title="Add Sender" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Add Sender
                </h2>
                <Link
                    :href="route('senders.index')"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                >
                    Back to Senders
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <InputLabel for="name" value="Sender Name" />
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.name"
                                        placeholder="My SMTP Sender"
                                        required
                                        autofocus
                                    />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Internal name for this sender</p>
                                </div>

                                <div>
                                    <InputLabel for="from_name" value="From Name" />
                                    <TextInput
                                        id="from_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.from_name"
                                        placeholder="John Doe"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.from_name" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Name that appears in recipient's inbox</p>
                                </div>
                            </div>

                            <div>
                                <InputLabel for="from_email" value="From Email" />
                                <TextInput
                                    id="from_email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.from_email"
                                    placeholder="noreply@example.com"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.from_email" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Email address that appears as sender</p>
                            </div>

                            <div class="space-y-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <h3 class="text-lg font-medium">SMTP Configuration</h3>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <InputLabel for="smtp_host" value="SMTP Host" />
                                        <TextInput
                                            id="smtp_host"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.smtp_host"
                                            placeholder="smtp.gmail.com"
                                            required
                                        />
                                        <InputError class="mt-2" :message="form.errors.smtp_host" />
                                    </div>

                                    <div>
                                        <InputLabel for="smtp_port" value="SMTP Port" />
                                        <TextInput
                                            id="smtp_port"
                                            type="number"
                                            class="mt-1 block w-full"
                                            v-model="form.smtp_port"
                                            placeholder="587"
                                            required
                                        />
                                        <InputError class="mt-2" :message="form.errors.smtp_port" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="smtp_username" value="SMTP Username" />
                                    <TextInput
                                        id="smtp_username"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.smtp_username"
                                        placeholder="your-email@gmail.com"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.smtp_username" />
                                </div>

                                <div>
                                    <InputLabel for="smtp_password" value="SMTP Password" />
                                    <TextInput
                                        id="smtp_password"
                                        type="password"
                                        class="mt-1 block w-full"
                                        v-model="form.smtp_password"
                                        placeholder="••••••••"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.smtp_password" />
                                </div>

                                <div>
                                    <InputLabel for="smtp_encryption" value="Encryption" />
                                    <select
                                        id="smtp_encryption"
                                        v-model="form.smtp_encryption"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    >
                                        <option value="tls">TLS</option>
                                        <option value="ssl">SSL</option>
                                        <option value="">None</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.smtp_encryption" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">TLS (587) is recommended for most providers</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <input
                                    id="is_default"
                                    type="checkbox"
                                    v-model="form.is_default"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-600 border-gray-300 rounded"
                                />
                                <label for="is_default" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                    Set as default sender
                                </label>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <PrimaryButton :disabled="form.processing">
                                    Add Sender
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
