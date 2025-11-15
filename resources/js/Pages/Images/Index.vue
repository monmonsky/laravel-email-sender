<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

defineProps({
    images: {
        type: Array,
        default: () => []
    }
});

const uploading = ref(false);
const fileInput = ref(null);

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);
    uploading.value = true;

    try {
        await axios.post(route('images.upload'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        router.reload({ only: ['images'] });
    } catch (error) {
        console.error('Error uploading image:', error);
        alert('Failed to upload image. Please try again.');
    } finally {
        uploading.value = false;
        event.target.value = '';
    }
};

const copyToClipboard = (url) => {
    navigator.clipboard.writeText(url);
    alert('Image URL copied to clipboard!');
};

const deleteImage = (filename) => {
    if (confirm('Are you sure you want to delete this image?')) {
        router.delete(route('images.destroy'), {
            data: { filename },
            preserveScroll: true
        });
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
};
</script>

<template>
    <Head title="Image Library" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Image Library
                </h2>
                <button
                    @click="triggerFileInput"
                    :disabled="uploading"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 disabled:opacity-50"
                >
                    {{ uploading ? 'Uploading...' : 'Upload Image' }}
                </button>
                <input
                    ref="fileInput"
                    type="file"
                    accept="image/*"
                    @change="handleFileUpload"
                    class="hidden"
                />
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="images.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No images</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by uploading an image.</p>
                        </div>

                        <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            <div
                                v-for="image in images"
                                :key="image.name"
                                class="relative group bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow"
                            >
                                <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-600">
                                    <img
                                        :src="image.url"
                                        :alt="image.name"
                                        class="w-full h-48 object-cover"
                                    />
                                </div>
                                <div class="p-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate" :title="image.name">
                                        {{ image.name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ formatFileSize(image.size) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ image.created_at }}
                                    </p>
                                    <div class="mt-3 flex gap-2">
                                        <button
                                            @click="copyToClipboard(image.url)"
                                            class="flex-1 inline-flex justify-center items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition-colors"
                                        >
                                            Copy URL
                                        </button>
                                        <button
                                            @click="deleteImage(image.name)"
                                            class="inline-flex justify-center items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="images.length > 0" class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200">How to use images in your templates:</h3>
                            <ol class="mt-2 text-sm text-blue-800 dark:text-blue-300 list-decimal list-inside space-y-1">
                                <li>Click "Copy URL" on any image above</li>
                                <li>In the template editor, click the image icon in the toolbar</li>
                                <li>The image will be automatically uploaded and inserted</li>
                                <li>Or you can paste the copied URL when inserting an image manually</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
