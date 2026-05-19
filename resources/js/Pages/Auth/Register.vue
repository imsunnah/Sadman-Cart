<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 p-6 font-sans">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-3xl shadow-xl border border-gray-100">
            
            <div class="text-center">
                <Link href="/" class="inline-block">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                        <span class="text-white font-bold text-2xl">S</span>
                    </div>
                </Link>
                <h2 class="mt-6 text-2xl font-bold text-gray-900">Create Account</h2>
                <p class="mt-1 text-gray-500">Join Sadman Cart today</p>
            </div>

            <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 p-4 rounded-xl text-sm border border-red-100">
                <p class="font-bold text-red-800 mb-1">Please fix the following:</p>
                <ul class="text-red-700 list-inside list-disc opacity-90">
                    <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                </ul>
            </div>

            <form class="mt-8 space-y-5" @submit.prevent="submit">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <div class="relative">
                            <User class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                            <input id="name" v-model="form.name" type="text" required 
                                class="block w-full rounded-xl bg-gray-50 border-gray-200 pl-12 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none" 
                                placeholder="Your Name">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                            <input id="email" v-model="form.email" type="email" required 
                                class="block w-full rounded-xl bg-gray-50 border-gray-200 pl-12 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none" 
                                placeholder="name@example.com">
                        </div>
                    </div>

                    <div>
                        <label for="mobile" class="block text-sm font-semibold text-gray-700 mb-2">Mobile Number</label>
                        <div class="relative">
                            <Phone class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                            <input id="mobile" v-model="form.mobile" type="text" required 
                                class="block w-full rounded-xl bg-gray-50 border-gray-200 pl-12 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none" 
                                placeholder="017xxxxxxxx">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                            <input id="password" v-model="form.password" type="password" required 
                                class="block w-full rounded-xl bg-gray-50 border-gray-200 pl-12 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                            <input id="password_confirmation" v-model="form.password_confirmation" type="password" required 
                                class="block w-full rounded-xl bg-gray-50 border-gray-200 pl-12 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none" 
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <button type="submit" :disabled="form.processing" 
                    class="w-full flex justify-center py-4 px-4 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition-all active:scale-[0.98] disabled:opacity-50">
                    Register
                </button>

                <p class="text-center text-sm text-gray-500">
                    Already have an account? 
                    <Link href="/login" class="text-blue-600 font-bold hover:underline">Login</Link>
                </p>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { User, Mail, Lock, Phone } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    mobile: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
