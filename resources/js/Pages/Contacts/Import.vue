<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    contactLists: {
        type: Object,
        default: () => ({ data: [] })
    }
});

const form = useForm({
    file: null,
    contact_list_id: '',
});

const fileName = ref('');

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.file = file;
        fileName.value = file.name;
    } else {
        form.file = null;
        fileName.value = '';
    }
};

const submit = () => {
    form.post(route('contacts.import'), {
        onSuccess: () => {
            form.reset();
            fileName.value = '';
        }
    });
};
</script>

<template>
    <Head title="Import Contacts" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Import Contacts
                </h2>
                <Link
                    :href="route('contacts.index')"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                >
                    Back to Contacts
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 space-y-6">
                <!-- Instructions Card -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">CSV Import Instructions</h3>
                            <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                <li>• CSV file must contain at least <strong>name</strong> and <strong>email</strong> columns</li>
                                <li>• Optional <strong>phone</strong> column can be included</li>
                                <li>• First row must be the header (column names)</li>
                                <li>• Duplicate emails will be skipped</li>
                                <li>• Maximum file size: 10 MB</li>
                            </ul>
                            <div class="mt-4">
                                <a
                                    :href="route('contacts.template')"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download CSV Template
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Import Form -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Contact List Selection -->
                            <div>
                                <InputLabel for="contact_list_id" value="Select Contact List *" />
                                <select
                                    id="contact_list_id"
                                    v-model="form.contact_list_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">Choose a contact list</option>
                                    <option v-for="list in contactLists.data" :key="list.id" :value="list.id">
                                        {{ list.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.contact_list_id" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    All imported contacts will be added to this list
                                </p>
                            </div>

                            <!-- File Upload -->
                            <div>
                                <InputLabel for="file" value="CSV File *" />
                                <div class="mt-2">
                                    <label
                                        for="file"
                                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 transition-colors"
                                        :class="{ 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20': fileName }"
                                    >
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg v-if="!fileName" class="w-16 h-16 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <svg v-else class="w-16 h-16 mb-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                            </svg>

                                            <p v-if="!fileName" class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-semibold">Click to upload</span> or drag and drop
                                            </p>
                                            <p v-else class="mb-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                                {{ fileName }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                CSV file (MAX. 10MB)
                                            </p>
                                        </div>
                                        <input
                                            id="file"
                                            type="file"
                                            class="hidden"
                                            accept=".csv,.txt"
                                            @change="handleFileChange"
                                            required
                                        />
                                    </label>
                                </div>
                                <InputError class="mt-2" :message="form.errors.file" />
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end gap-4">
                                <Link
                                    :href="route('contacts.index')"
                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200"
                                >
                                    Cancel
                                </Link>
                                <PrimaryButton
                                    :disabled="form.processing || !form.file || !form.contact_list_id"
                                    :class="{ 'opacity-50': form.processing || !form.file || !form.contact_list_id }"
                                >
                                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ form.processing ? 'Importing...' : 'Import Contacts' }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Example CSV Format -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Example CSV Format</h3>
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 overflow-x-auto">
                            <pre class="text-sm text-gray-700 dark:text-gray-300"><code>name,email,phone
John Doe,john@example.com,+1234567890
Jane Smith,jane@example.com,+0987654321
Bob Johnson,bob@example.com,</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
