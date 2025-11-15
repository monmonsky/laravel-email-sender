<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const show = ref(false);
const message = ref('');
const type = ref('success');

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            showMessage(flash.success, 'success');
        } else if (flash?.error) {
            showMessage(flash.error, 'error');
        } else if (flash?.warning) {
            showMessage(flash.warning, 'warning');
        } else if (flash?.info) {
            showMessage(flash.info, 'info');
        }
    },
    { deep: true, immediate: true }
);

const showMessage = (msg, msgType) => {
    message.value = msg;
    type.value = msgType;
    show.value = true;

    setTimeout(() => {
        show.value = false;
    }, 3000);
};

const close = () => {
    show.value = false;
};

const getIcon = () => {
    const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ',
    };
    return icons[type.value] || icons.success;
};

const getColors = () => {
    const colors = {
        success: 'border-l-green-500 dark:border-l-green-400',
        error: 'border-l-red-500 dark:border-l-red-400',
        warning: 'border-l-yellow-500 dark:border-l-yellow-400',
        info: 'border-l-blue-500 dark:border-l-blue-400',
    };
    return colors[type.value] || colors.success;
};

const getIconColors = () => {
    const colors = {
        success: 'text-green-600 dark:text-green-400',
        error: 'text-red-600 dark:text-red-400',
        warning: 'text-yellow-600 dark:text-yellow-400',
        info: 'text-blue-600 dark:text-blue-400',
    };
    return colors[type.value] || colors.success;
};
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="translate-x-full opacity-0"
        enter-to-class="translate-x-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="translate-x-0 opacity-100"
        leave-to-class="translate-x-full opacity-0"
    >
        <div
            v-if="show"
            class="fixed top-4 right-4 z-50 max-w-sm w-full"
        >
            <div
                :class="[
                    'bg-gray-800 dark:bg-gray-900 border-l-4 shadow-lg rounded-lg p-4 flex items-start gap-3',
                    getColors()
                ]"
            >
                <div :class="['text-2xl font-bold flex-shrink-0', getIconColors()]">
                    {{ getIcon() }}
                </div>
                <div class="flex-1 text-gray-100 dark:text-gray-200 font-medium">
                    {{ message }}
                </div>
                <button
                    @click="close"
                    class="text-gray-400 hover:text-gray-200 flex-shrink-0 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </Transition>
</template>
