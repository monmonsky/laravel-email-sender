<script setup>
import { ref, watch, onMounted } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['update:modelValue']);

const content = ref(props.modelValue);
const quillEditor = ref(null);

watch(() => props.modelValue, (newValue) => {
    if (newValue !== content.value) {
        content.value = newValue;
    }
});

watch(content, (newValue) => {
    emit('update:modelValue', newValue);
});

const imageHandler = function() {
    const url = prompt('Enter image URL:');
    if (url) {
        const range = quillEditor.value.getQuill().getSelection();
        quillEditor.value.getQuill().insertEmbed(range.index, 'image', url);
    }
};

const editorOptions = {
    theme: 'snow',
    modules: {
        toolbar: {
            container: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ],
            handlers: {
                image: imageHandler
            }
        }
    },
    placeholder: 'Write your email content here...'
};
</script>

<template>
    <QuillEditor
        ref="quillEditor"
        v-model:content="content"
        :options="editorOptions"
        contentType="html"
        class="bg-white dark:bg-gray-900 rounded-md"
    />
</template>

<style>
.ql-toolbar {
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
    background: white;
}

.dark .ql-toolbar {
    background: rgb(17 24 39);
    border-color: rgb(55 65 81);
}

.ql-container {
    border-bottom-left-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
    font-size: 1rem;
    min-height: 300px;
}

.dark .ql-container {
    border-color: rgb(55 65 81);
}

.ql-editor {
    min-height: 300px;
}

.dark .ql-editor {
    color: rgb(209 213 219);
}

.dark .ql-snow .ql-stroke {
    stroke: rgb(209 213 219);
}

.dark .ql-snow .ql-fill {
    fill: rgb(209 213 219);
}

.dark .ql-snow .ql-picker {
    color: rgb(209 213 219);
}

.dark .ql-snow .ql-picker-options {
    background: rgb(17 24 39);
    border-color: rgb(55 65 81);
}

.dark .ql-snow .ql-picker-item:hover {
    color: rgb(99 102 241);
}
</style>
