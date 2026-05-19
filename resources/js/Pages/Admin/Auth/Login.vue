<template>
    <div class="min-h-screen flex items-center justify-center bg-slate-900 p-6 font-sans">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-3xl shadow-2xl">
            
            <div class="text-center">
                <div class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center mx-auto shadow-xl">
                    <span class="text-white font-bold text-2xl">S</span>
                </div>
                <h2 class="mt-6 text-2xl font-bold text-slate-900 uppercase tracking-tighter italic">Admin Portal</h2>
                <p class="mt-1 text-slate-400 font-bold text-[10px] uppercase tracking-widest">Management Authorization</p>
            </div>

            <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 p-4 rounded-xl border border-red-100">
                <ul class="text-red-700 text-xs font-bold list-inside list-disc">
                    <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                </ul>
            </div>

            <!-- Success Alert -->
            <div v-if="$page.props.flash?.success" class="bg-emerald-50 p-4 rounded-xl text-xs font-bold border border-emerald-100 text-emerald-800 mb-4">
                {{ $page.props.flash.success }}
            </div>

            <form class="mt-8 space-y-6" @submit.prevent="submit">
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Identity Protocol</label>
                        <input id="email" v-model="form.email" type="email" required 
                            class="block w-full rounded-xl bg-slate-50 border-none px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-slate-900/10 outline-none transition-all" 
                            placeholder="Admin Email">
                    </div>

                    <div>
                        <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1 font-bold flex justify-between items-center">
                            <span>Access Key</span>
                            <Link href="/forgot-password" class="text-indigo-600 hover:underline normal-case">Forgot password?</Link>
                        </label>
                        <input id="password" v-model="form.password" type="password" required 
                            class="block w-full rounded-xl bg-slate-50 border-none px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-slate-900/10 outline-none transition-all" 
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" :disabled="form.processing" 
                    class="w-full flex justify-center py-4 px-4 bg-slate-900 text-white font-bold rounded-xl hover:bg-black transition-all active:scale-[0.98] disabled:opacity-50 uppercase tracking-widest text-xs">
                    Authorize
                </button>

                <div class="text-center pt-6 border-t border-slate-50 flex items-center justify-center space-x-4">
                    <Link href="/login" class="text-[9px] font-black text-slate-300 hover:text-slate-600 uppercase tracking-widest transition-colors">Store Login</Link>
                    <span class="w-1 h-1 bg-slate-100 rounded-full"></span>
                    <Link href="/forgot-password" class="text-[9px] font-black text-slate-300 hover:text-slate-600 uppercase tracking-widest transition-colors">Forgot Key</Link>
                    <span class="w-1 h-1 bg-slate-100 rounded-full"></span>
                    <Link href="/" class="text-[9px] font-black text-slate-300 hover:text-slate-600 uppercase tracking-widest transition-colors">Home</Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/admin/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>
