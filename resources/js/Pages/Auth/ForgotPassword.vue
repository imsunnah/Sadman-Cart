<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 p-6 font-sans">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-3xl shadow-xl border border-gray-100">
            
            <div class="text-center">
                <Link href="/" class="inline-block">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                        <span class="text-white font-bold text-2xl">S</span>
                    </div>
                </Link>
                <h2 class="mt-6 text-2xl font-bold text-gray-900">{{ $t('Password Recovery') }}</h2>
                <p class="mt-1 text-gray-500">{{ $t('Recover your admin or customer account') }}</p>
            </div>

            <!-- Error Alerts -->
            <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 p-4 rounded-xl text-sm border border-red-100">
                <p class="font-bold text-red-800 mb-1">{{ $t('Please fix the following:') }}</p>
                <ul class="text-red-700 list-inside list-disc opacity-90">
                    <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                </ul>
            </div>

            <!-- Success Alert -->
            <div v-if="flashSuccess" class="bg-emerald-50 p-4 rounded-xl text-sm border border-emerald-100 text-emerald-800 font-bold">
                {{ flashSuccess }}
            </div>

            <!-- Step 1: Input Email/Mobile -->
            <form v-if="!otpSent" class="mt-8 space-y-5" @submit.prevent="submitIdentifier">
                <div>
                    <label for="identifier" class="block text-sm font-semibold text-gray-700 mb-2">{{ $t('Email Address or Mobile Number') }}</label>
                    <div class="relative">
                        <User class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                        <input 
                            id="identifier" 
                            v-model="form.identifier" 
                            type="text" 
                            required 
                            class="block w-full rounded-xl bg-gray-50 border-gray-200 pl-12 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none" 
                            :placeholder="$t('Enter email or mobile number')"
                        >
                    </div>
                </div>

                <button 
                    type="submit" 
                    :disabled="form.processing" 
                    class="w-full flex justify-center py-4 px-4 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition-all active:scale-[0.98] disabled:opacity-50"
                >
                    {{ $t('Request Recovery Code') }}
                </button>

                <p class="text-center text-sm text-gray-500">
                    {{ $t('Remembered password?') }} 
                    <Link href="/login" class="text-blue-600 font-bold hover:underline">{{ $t('Login here') }}</Link>
                </p>
            </form>

            <!-- Step 2: Input OTP & New Password -->
            <form v-else class="mt-8 space-y-5" @submit.prevent="submitReset">
                <div class="space-y-4">
                    <div>
                        <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2">{{ $t('Verification Code (OTP)') }}</label>
                        <div class="relative">
                            <KeyRound class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                            <input 
                                id="otp" 
                                v-model="resetForm.otp" 
                                type="text" 
                                required 
                                class="block w-full rounded-xl bg-gray-50 border-gray-200 pl-12 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none" 
                                :placeholder="$t('Enter code (123456)')"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">{{ $t('New Password') }}</label>
                        <div class="relative">
                            <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                            <input 
                                id="password" 
                                v-model="resetForm.password" 
                                type="password" 
                                required 
                                class="block w-full rounded-xl bg-gray-50 border-gray-200 pl-12 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none" 
                                placeholder="••••••••"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">{{ $t('Confirm New Password') }}</label>
                        <div class="relative">
                            <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                            <input 
                                id="password_confirmation" 
                                v-model="resetForm.password_confirmation" 
                                type="password" 
                                required 
                                class="block w-full rounded-xl bg-gray-50 border-gray-200 pl-12 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none" 
                                placeholder="••••••••"
                            >
                        </div>
                    </div>
                </div>

                <button 
                    type="submit" 
                    :disabled="resetForm.processing" 
                    class="w-full flex justify-center py-4 px-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all active:scale-[0.98] disabled:opacity-50"
                >
                    {{ $t('Reset Password') }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { User, Lock, KeyRound } from 'lucide-vue-next';

const page = usePage();
const otpSent = ref(false);

const flashSuccess = computed(() => page.props.flash?.success);

const form = useForm({
    identifier: '',
});

const resetForm = useForm({
    otp: '',
    password: '',
    password_confirmation: '',
});

const submitIdentifier = () => {
    form.post('/forgot-password', {
        onSuccess: () => {
            otpSent.value = true;
        }
    });
};

const submitReset = () => {
    resetForm.post('/reset-password', {
        onFinish: () => resetForm.reset('password', 'password_confirmation'),
    });
};
</script>
