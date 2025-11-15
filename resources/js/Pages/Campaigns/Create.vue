<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import QuillEditor from '@/Components/QuillEditor.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    contactLists: {
        type: Object,
        default: () => ({ data: [] })
    },
    templates: {
        type: Object,
        default: () => ({ data: [] })
    },
    senders: {
        type: Object,
        default: () => ({ data: [] })
    }
});

const form = useForm({
    name: '',
    contact_list_id: '',
    template_id: '',
    sender_id: '',
    subject: '',
    content: '',
    status: 'draft',
});

// Watch for template selection and auto-fill subject and content
const onTemplateChange = () => {
    if (form.template_id) {
        const selectedTemplate = props.templates.data.find(t => t.id === parseInt(form.template_id));
        if (selectedTemplate) {
            form.subject = selectedTemplate.subject;
            form.content = selectedTemplate.body;
        }
    } else {
        // Clear fields when "Create custom email" is selected
        form.subject = '';
        form.content = '';
    }
};

const submit = () => {
    form.post(route('campaigns.store'));
};
</script>

<template>
    <Head title="Create Campaign" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Create Campaign
                </h2>
                <Link
                    :href="route('campaigns.index')"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                >
                    Back to Campaigns
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="name" value="Campaign Name" />
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
                                <InputLabel for="contact_list_id" value="Contact List" />
                                <select
                                    id="contact_list_id"
                                    v-model="form.contact_list_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">Select a contact list</option>
                                    <option v-for="list in contactLists.data" :key="list.id" :value="list.id">
                                        {{ list.name }} ({{ list.contacts_count || 0 }})
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.contact_list_id" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Choose which contact list to send this campaign to
                                </p>
                            </div>

                            <div>
                                <InputLabel for="template_id" value="Email Template (Optional)" />
                                <select
                                    id="template_id"
                                    v-model="form.template_id"
                                    @change="onTemplateChange"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                >
                                    <option value="">Create custom email or select template</option>
                                    <option v-for="template in templates.data" :key="template.id" :value="template.id">
                                        {{ template.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.template_id" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Selecting a template will auto-fill the subject and content fields
                                </p>
                            </div>

                            <div>
                                <InputLabel for="sender_id" value="Email Sender (Optional)" />
                                <select
                                    id="sender_id"
                                    v-model="form.sender_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                >
                                    <option value="">Use default sender</option>
                                    <option v-for="sender in senders.data" :key="sender.id" :value="sender.id">
                                        {{ sender.name }} ({{ sender.from_email }})
                                        <span v-if="sender.is_default"> - Default</span>
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.sender_id" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Choose which email sender to use for this campaign
                                </p>
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
                                <InputLabel for="content" value="Email Content" />
                                <div class="mt-1">
                                    <QuillEditor v-model="form.content" />
                                </div>
                                <InputError class="mt-2" :message="form.errors.content" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    You can use variables like: <code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">&#123;&#123;name&#125;&#125;</code>, <code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">&#123;&#123;email&#125;&#125;</code>, <code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">&#123;&#123;phone&#125;&#125;</code>
                                </p>
                            </div>

                            <div>
                                <InputLabel for="status" value="Status" />
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                >
                                    <option value="draft">Draft</option>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="sent">Sent</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <PrimaryButton :disabled="form.processing">
                                    Create Campaign
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
