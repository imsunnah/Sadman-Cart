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
                <h2 class="mt-8 text-2xl font-black text-slate-900 uppercase tracking-tight italic">{{ otpSent ? 'ভেরিফিকেশন কোড দিন' : 'পাসওয়ার্ড পুনরুদ্ধার' }}</h2>
                <p class="mt-2 text-slate-400 text-xs font-bold uppercase tracking-widest tracking-[0.1em]">
                    {{ otpSent ? 'আপনার মোবাইল বা ইমেইলে পাঠানো কোডটি লিখুন' : 'আপনার একাউন্ট পুনরুদ্ধার করুন' }}
                </p>
            </div>

            <!-- Error Alerts -->
            <Transition name="fade">
                <div v-if="Object.keys(form.errors).length > 0 || Object.keys(resetForm.errors).length > 0" class="bg-red-50 p-4 rounded-2xl text-xs border border-red-100 animate-shake">
                    <p class="font-black text-red-800 uppercase tracking-widest mb-2 flex items-center gap-2">
                        <AlertCircle class="w-4 h-4" /> অনুগ্রহ করে নিচের ভুলগুলো সংশোধন করুন
                    </p>
                    <ul class="text-red-700 space-y-1 font-bold">
                        <li v-for="(error, key) in form.errors" :key="key" class="flex items-center gap-2">
                            <div class="w-1 h-1 bg-red-400 rounded-full"></div> {{ error }}
                        </li>
                        <li v-for="(error, key) in resetForm.errors" :key="key" class="flex items-center gap-2">
                            <div class="w-1 h-1 bg-red-400 rounded-full"></div> {{ error }}
                        </li>
                    </ul>
                </div>
            </Transition>

            <!-- Success Alert -->
            <Transition name="fade">
                <div v-if="flashSuccess" class="bg-emerald-50 p-5 rounded-2xl text-xs border border-emerald-100 text-emerald-800 font-extrabold flex items-center gap-3 shadow-sm italic uppercase tracking-wider">
                    <div class="w-8 h-8 bg-emerald-500 text-white rounded-xl flex items-center justify-center flex-shrink-0 animate-bounce">
                        <CheckCircle2 class="w-5 h-5" />
                    </div>
                    {{ flashSuccess }}
                </div>
            </Transition>

            <!-- Step 1: Input Email/Mobile -->
            <form v-if="!otpSent" class="mt-10 space-y-6" @submit.prevent="submitIdentifier">
                <div class="space-y-4">
                    <div class="group">
                        <label for="identifier" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-[#003366] transition-colors">ইমেইল অথবা মোবাইল নম্বর</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:text-[#003366] transition-colors">
                                <User class="w-5 h-5" />
                            </span>
                            <input 
                                id="identifier" 
                                v-model="form.identifier" 
                                type="text" 
                                required 
                                class="block w-full rounded-2xl bg-slate-50 border-2 border-transparent px-14 py-4.5 text-sm font-bold text-slate-900 focus:bg-white focus:border-[#003366]/10 focus:ring-4 focus:ring-[#003366]/5 transition-all outline-none placeholder:text-slate-300" 
                                placeholder="আপনার ইমেইল বা মোবাইল লিখুন"
                            >
                        </div>
                    </div>
                </div>

                <button 
                    type="submit" 
                    :disabled="form.processing" 
                    class="w-full flex justify-center py-5 px-4 bg-[#003366] text-white font-black rounded-2xl hover:bg-slate-800 transition-all active:scale-[0.98] disabled:opacity-50 shadow-xl shadow-[#003366]/20 uppercase tracking-[0.2em] text-xs italic"
                >
                    <span v-if="!form.processing" class="flex items-center gap-2">কোড পাঠান <ArrowRight class="w-4 h-4" /></span>
                    <Loader2 v-else class="w-5 h-5 animate-spin" />
                </button>

                <div class="text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        পাসওয়ার্ড মনে আছে? 
                        <Link href="/login" class="text-[#FF6600] font-black border-b border-[#FF6600]/20 hover:border-[#FF6600] transition-all ml-1">লগইন করুন</Link>
                    </p>
                </div>
            </form>

            <!-- Step 2: Input OTP & New Password -->
            <form v-else class="mt-10 space-y-6" @submit.prevent="submitReset">
                <div class="space-y-5">
                    <div class="group">
                        <label for="otp" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-[#003366] transition-colors">ভেরিফিকেশন কোড (OTP)</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:text-[#003366] transition-colors">
                                <KeyRound class="w-5 h-5" />
                            </span>
                            <input 
                                id="otp" 
                                v-model="resetForm.otp" 
                                type="text" 
                                required 
                                class="block w-full rounded-2xl bg-slate-50 border-2 border-transparent px-14 py-4 text-sm font-bold text-slate-900 focus:bg-white focus:border-[#003366]/10 focus:ring-4 focus:ring-[#003366]/5 transition-all outline-none" 
                                placeholder="১২৩৪৫৬"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5">
                        <div class="group">
                            <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-[#003366] transition-colors">নতুন পাসওয়ার্ড</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:text-[#003366] transition-colors">
                                    <Lock class="w-5 h-5" />
                                </span>
                                <input 
                                    id="password" 
                                    v-model="resetForm.password" 
                                    type="password" 
                                    required 
                                    class="block w-full rounded-2xl bg-slate-50 border-2 border-transparent px-14 py-4 text-sm font-bold text-slate-900 focus:bg-white focus:border-[#003366]/10 focus:ring-4 focus:ring-[#003366]/5 transition-all outline-none" 
                                    placeholder="••••••••"
                                >
                            </div>
                        </div>

                        <div class="group">
                            <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-[#003366] transition-colors">পাসওয়ার্ড নিশ্চিত করুন</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:text-[#003366] transition-colors">
                                    <ShieldCheck class="w-5 h-5" />
                                </span>
                                <input 
                                    id="password_confirmation" 
                                    v-model="resetForm.password_confirmation" 
                                    type="password" 
                                    required 
                                    class="block w-full rounded-2xl bg-slate-50 border-2 border-transparent px-14 py-4 text-sm font-bold text-slate-900 focus:bg-white focus:border-[#003366]/10 focus:ring-4 focus:ring-[#003366]/5 transition-all outline-none" 
                                    placeholder="••••••••"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <button 
                    type="submit" 
                    :disabled="resetForm.processing" 
                    class="w-full flex justify-center py-5 px-4 bg-[#FF6600] text-white font-black rounded-2xl hover:bg-[#e65c00] transition-all active:scale-[0.98] disabled:opacity-50 shadow-xl shadow-orange-900/20 uppercase tracking-[0.2em] text-xs italic"
                >
                    <span v-if="!resetForm.processing">পাসওয়ার্ড রিসেট করুন</span>
                    <Loader2 v-else class="w-5 h-5 animate-spin" />
                </button>
                
                <button type="button" @click="otpSent = false" class="w-full text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-[#003366] transition-colors">
                    আবার কোড পাঠান
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { User, Lock, KeyRound, AlertCircle, CheckCircle2, ArrowRight, Loader2, ShieldCheck } from 'lucide-vue-next';

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

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
.animate-shake {
    animation: shake 0.4s ease-in-out;
}
</style>


