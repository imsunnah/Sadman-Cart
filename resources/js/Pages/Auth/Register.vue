<template>
    <div class="min-h-screen flex items-center justify-center bg-[#F8F9FA] p-6 font-sans antialiased">
        <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-[2.5rem] shadow-[0_20px_50px_-20px_rgba(0,0,0,0.05)] border border-slate-100 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#003366]/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-[#FF6600]/5 rounded-full blur-3xl"></div>

            <div class="relative text-center">
                <Link href="/" class="inline-block group">
                    <div class="w-20 h-20 bg-[#003366] rounded-3xl flex items-center justify-center shadow-xl shadow-[#003366]/20 group-hover:scale-105 transition-transform duration-500 rotate-3">
                        <span class="text-white font-black text-3xl -rotate-3 italic">S</span>
                    </div>
                </Link>
                <h2 class="mt-8 text-2xl font-black text-slate-900 uppercase tracking-tight italic">{{ $t('Create Account') }}</h2>
                <p class="mt-2 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">{{ $t('Join Sadman Cart today') }}</p>
            </div>

            <!-- Error Alerts -->
            <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 p-4 rounded-2xl text-xs border border-red-100 italic">
                <ul class="text-red-700 space-y-1 font-bold">
                    <li v-for="(error, key) in form.errors" :key="key" class="flex items-center gap-2">
                        <div class="w-1 h-1 bg-red-400 rounded-full"></div> {{ error }}
                    </li>
                </ul>
            </div>

            <form class="mt-8 space-y-5" @submit.prevent="submit">
                <div class="space-y-4">
                    <div class="group">
                        <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-[#003366] transition-colors">{{ $t('Full Name') }}</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:text-[#003366] transition-colors">
                                <User class="w-5 h-5" />
                            </span>
                            <input id="name" v-model="form.name" type="text" required 
                                class="block w-full rounded-2xl bg-slate-50 border-2 border-transparent px-14 py-4 text-sm font-bold text-slate-900 focus:bg-white focus:border-[#003366]/10 focus:ring-4 focus:ring-[#003366]/5 transition-all outline-none" 
                                :placeholder="$t('Your Name')">
                        </div>
                    </div>

                    <div class="group">
                        <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-[#003366] transition-colors">{{ $t('Email') }}</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:text-[#003366] transition-colors">
                                <Mail class="w-5 h-5" />
                            </span>
                            <input id="email" v-model="form.email" type="email" required 
                                class="block w-full rounded-2xl bg-slate-50 border-2 border-transparent px-14 py-4 text-sm font-bold text-slate-900 focus:bg-white focus:border-[#003366]/10 focus:ring-4 focus:ring-[#003366]/5 transition-all outline-none" 
                                placeholder="name@example.com">
                        </div>
                    </div>

                    <div class="group">
                        <label for="mobile" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-[#003366] transition-colors">{{ $t('Mobile Number') }}</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:text-[#003366] transition-colors">
                                <Phone class="w-5 h-5" />
                            </span>
                            <input id="mobile" v-model="form.mobile" type="text" required 
                                class="block w-full rounded-2xl bg-slate-50 border-2 border-transparent px-14 py-4 text-sm font-bold text-slate-900 focus:bg-white focus:border-[#003366]/10 focus:ring-4 focus:ring-[#003366]/5 transition-all outline-none" 
                                placeholder="017xxxxxxxx">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="group">
                            <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-[#FF6600] transition-colors">{{ $t('Password') }}</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:text-[#FF6600] transition-colors">
                                    <Lock class="w-4 h-4" />
                                </span>
                                <input id="password" v-model="form.password" type="password" required 
                                    class="block w-full rounded-2xl bg-slate-50 border-2 border-transparent px-10 py-4 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#FF6600]/10 focus:ring-4 focus:ring-[#FF6600]/5 transition-all outline-none" 
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <div class="group">
                            <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-[#FF6600] transition-colors">{{ $t('Confirm Password') }}</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:text-[#FF6600] transition-colors">
                                    <ShieldCheck class="w-4 h-4" />
                                </span>
                                <input id="password_confirmation" v-model="form.password_confirmation" type="password" required 
                                    class="block w-full rounded-2xl bg-slate-50 border-2 border-transparent px-10 py-4 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#FF6600]/10 focus:ring-4 focus:ring-[#FF6600]/5 transition-all outline-none" 
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" :disabled="form.processing" 
                    class="w-full flex justify-center py-5 px-4 bg-[#003366] text-white font-black rounded-2xl hover:bg-slate-800 transition-all active:scale-[0.98] disabled:opacity-50 shadow-xl shadow-[#003366]/20 uppercase tracking-[0.2em] text-xs italic mt-4">
                    <span v-if="!form.processing">{{ $t('Register') }}</span>
                    <Loader2 v-else class="w-5 h-5 animate-spin" />
                </button>

                <div class="text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        {{ $t('Already have an account?') }} 
                        <Link href="/login" class="text-[#FF6600] font-black border-b border-[#FF6600]/20 hover:border-[#FF6600] transition-all ml-1">{{ $t('Login') }}</Link>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { User, Mail, Lock, Phone, ShieldCheck, Loader2 } from 'lucide-vue-next';

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
