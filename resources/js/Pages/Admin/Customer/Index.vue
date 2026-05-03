<template>
    <AdminLayout>
        <div class="space-y-10">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight uppercase italic flex items-center">
                        <Users class="w-10 h-10 mr-4 text-primary-900" /> Customer <span class="text-primary-900 ml-3">Base</span>
                    </h1>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-[0.2em] text-[10px]">Registered user entities and growth metrics</p>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-primary-900 rounded-full animate-pulse"></div>
                        <h3 class="font-black text-slate-900 uppercase tracking-tight italic">Authenticated User Registry</h3>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="py-6 px-8">User Identity</th>
                                <th class="py-6 px-8">Email Protocol</th>
                                <th class="py-6 px-8">Join Date</th>
                                <th class="py-6 px-8 text-right">Activity Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="customer in customers.data" :key="customer.id" class="hover:bg-slate-50/50 transition-all group">
                                <td class="py-6 px-8">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-900 font-black text-xs group-hover:scale-110 transition-transform">
                                            {{ customer.name.charAt(0) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-black text-slate-900 uppercase tracking-tight italic">{{ customer.name }}</div>
                                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">ID: #USR-{{ String(customer.id).padStart(4, '0') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <span class="text-xs font-bold text-slate-600">{{ customer.email }}</span>
                                </td>
                                <td class="py-6 px-8 text-xs font-black text-slate-900 uppercase">
                                    {{ new Date(customer.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                                </td>
                                <td class="py-6 px-8 text-right">
                                    <span class="bg-green-50 text-green-600 text-[8px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full border border-green-100">
                                        Verified
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-8 border-t border-slate-50 bg-slate-50/30 flex justify-center">
                    <div class="flex space-x-2">
                        <Link v-for="link in customers.links" :key="link.label" :href="link.url || '#'" v-html="link.label" 
                            :class="[link.active ? 'bg-primary-900 text-white shadow-xl shadow-primary-900/20' : 'bg-white text-slate-400 hover:bg-slate-50 border border-slate-100', !link.url ? 'opacity-50 cursor-not-allowed' : '']"
                            class="px-4 py-2 rounded-xl text-[10px] font-black transition-all">
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Users } from 'lucide-vue-next';

defineProps({
    customers: Object
});
</script>
