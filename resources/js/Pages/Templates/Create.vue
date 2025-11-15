<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import QuillEditor from '@/Components/QuillEditor.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    subject: '',
    body: '',
});

const submit = () => {
    form.post(route('templates.store'));
};
</script>

<template>
    <Head title="Create Template" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Create Template
                </h2>
                <Link
                    :href="route('templates.index')"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                >
                    Back to Templates
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="name" value="Template Name" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="subject" value="Email Subject" />
                                <TextInput
                                    id="subject"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.subject"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.subject" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    You can use variables like: <code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">&#123;&#123;name&#125;&#125;</code>, <code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">&#123;&#123;email&#125;&#125;</code>, <code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">&#123;&#123;phone&#125;&#125;</code>
                                </p>
                            </div>

                            <div>
                                <InputLabel for="body" value="Email Content" />
                                <div class="mt-1">
                                    <QuillEditor v-model="form.body" />
                                </div>
                                <InputError class="mt-2" :message="form.errors.body" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    You can use variables like: <code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">&#123;&#123;name&#125;&#125;</code>, <code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">&#123;&#123;email&#125;&#125;</code>, <code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">&#123;&#123;phone&#125;&#125;</code>
                                </p>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <PrimaryButton :disabled="form.processing">
                                    Create Template
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
