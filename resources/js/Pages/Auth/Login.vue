<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

// Animation state
const isLoaded = ref(false);
const floatingEmails = ref([
    { delay: 0, duration: 20 },
    { delay: 2, duration: 25 },
    { delay: 4, duration: 22 },
    { delay: 6, duration: 28 },
    { delay: 8, duration: 24 },
]);

onMounted(() => {
    setTimeout(() => {
        isLoaded.value = true;
    }, 100);
});
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 dark:from-gray-900 dark:via-purple-900 dark:to-violet-900 flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Floating Email Icons -->
            <div
                v-for="(email, index) in floatingEmails"
                :key="index"
                class="absolute opacity-10 dark:opacity-5"
                :style="{
                    left: `${(index * 20) + 10}%`,
                    animation: `float ${email.duration}s ease-in-out infinite`,
                    animationDelay: `${email.delay}s`
                }"
            >
                <svg class="w-16 h-16 md:w-24 md:h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                </svg>
            </div>

            <!-- Animated Circles -->
            <div class="absolute top-10 left-10 w-72 h-72 bg-white dark:bg-purple-500 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute top-20 right-10 w-72 h-72 bg-yellow-200 dark:bg-pink-500 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-200 dark:bg-indigo-500 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <!-- Login Card -->
        <div
            :class="[
                'w-full max-w-md transform transition-all duration-700',
                isLoaded ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
            ]"
        >
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-lg bg-opacity-95 dark:bg-opacity-95">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-900 dark:to-purple-900 p-8 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
                    </div>
                    <div class="relative">
                        <!-- Email Icon with Animation -->
                        <div class="inline-block mb-4 transform transition-transform duration-500 hover:scale-110 hover:rotate-12">
                            <div class="bg-white dark:bg-gray-700 rounded-full p-4 shadow-lg">
                                <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400 animate-pulse-slow" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2">
                            Email Campaign Manager
                        </h1>
                        <p class="text-indigo-100 dark:text-indigo-200 text-sm">
                            Sign in to manage your campaigns
                        </p>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="p-8">
                    <div v-if="status" class="mb-4 text-sm font-medium text-green-600 bg-green-50 dark:bg-green-900/20 dark:text-green-400 p-3 rounded-lg animate-fade-in">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Email Input -->
                        <div class="transform transition-all duration-300 hover:translate-x-1">
                            <InputLabel for="email" value="Email Address" class="text-gray-700 dark:text-gray-300 font-medium" />

                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="pl-10 block w-full transition-all duration-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="you@example.com"
                                />
                            </div>

                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <!-- Password Input -->
                        <div class="transform transition-all duration-300 hover:translate-x-1">
                            <InputLabel for="password" value="Password" class="text-gray-700 dark:text-gray-300 font-medium" />

                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="pl-10 block w-full transition-all duration-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                />
                            </div>

                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center group cursor-pointer">
                                <Checkbox
                                    name="remember"
                                    v-model:checked="form.remember"
                                    class="transition-transform duration-200 group-hover:scale-110"
                                />
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200 transition-colors">
                                    Remember me
                                </span>
                            </label>

                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 font-medium transition-colors duration-200 hover:underline"
                            >
                                Forgot password?
                            </Link>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <PrimaryButton
                                class="w-full justify-center py-3 text-base font-semibold transform transition-all duration-200 hover:scale-105 active:scale-95 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-500/50"
                                :class="{ 'opacity-70 cursor-not-allowed': form.processing }"
                                :disabled="form.processing"
                            >
                                <span v-if="!form.processing">Sign In</span>
                                <span v-else class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Signing in...
                                </span>
                            </PrimaryButton>
                        </div>
                    </form>

                    <!-- Register Link -->
                    <div v-if="$page.props.canRegister" class="mt-6 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Don't have an account?
                            <Link
                                :href="route('register')"
                                class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors duration-200 hover:underline ml-1"
                            >
                                Sign up now
                            </Link>
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 dark:bg-gray-900/50 px-8 py-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-center text-gray-500 dark:text-gray-400">
                       Made with
                        <svg class="inline-block w-4 h-4 mx-1 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                        by
                        <span class="font-semibold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent dark:from-blue-400 dark:to-purple-400">Codemons</span>
                    </p>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-6 text-center">
                <p class="text-white dark:text-gray-300 text-sm backdrop-blur-sm bg-black/10 dark:bg-white/10 rounded-lg p-3 inline-block">
                    <svg class="inline-block w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    Your data is protected with end-to-end encryption
                </p>
            </div>
        </div>
    </div>

    <style scoped>
    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        25% {
            transform: translateY(-20px) rotate(5deg);
        }
        50% {
            transform: translateY(-40px) rotate(-5deg);
        }
        75% {
            transform: translateY(-20px) rotate(3deg);
        }
    }

    @keyframes blob {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }
        25% {
            transform: translate(20px, -50px) scale(1.1);
        }
        50% {
            transform: translate(-20px, -100px) scale(0.9);
        }
        75% {
            transform: translate(-50px, -50px) scale(1.05);
        }
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-blob {
        animation: blob 7s infinite;
    }

    .animation-delay-2000 {
        animation-delay: 2s;
    }

    .animation-delay-4000 {
        animation-delay: 4s;
    }

    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
    </style>
</template>
