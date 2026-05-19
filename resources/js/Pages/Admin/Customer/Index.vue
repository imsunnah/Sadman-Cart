<template>
    <AdminLayout>
        <div class="space-y-8">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase italic flex items-center">
                        <Users class="w-8 h-8 mr-3 text-indigo-900" /> Customer <span class="text-indigo-900 ml-2">Base</span>
                    </h1>
                    <p class="text-slate-400 font-bold mt-1 uppercase tracking-wider text-[9px]">Registered user entities & customer purchase frequency</p>
                </div>
            </div>

            <!-- Tabs selector -->
            <div class="flex border-b border-slate-200">
                <button 
                    @click="activeTab = 'all'" 
                    class="py-3 px-6 text-xs font-black uppercase tracking-wider border-b-2 outline-none transition-colors"
                    :class="activeTab === 'all' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-600'"
                >
                    All Customers ({{ customers.total }})
                </button>
                <button 
                    @click="activeTab = 'repeated'" 
                    class="py-3 px-6 text-xs font-black uppercase tracking-wider border-b-2 outline-none transition-colors"
                    :class="activeTab === 'repeated' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-600'"
                >
                    Repeated Customers ({{ repeatedCustomers.length }})
                </button>
            </div>

            <!-- Tab 1: All Customers Table -->
            <div v-if="activeTab === 'all'" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Authenticated User Registry</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="py-4 px-6">User Identity</th>
                                <th class="py-4 px-6">Email Protocol</th>
                                <th class="py-4 px-6">Join Date</th>
                                <th class="py-4 px-6 text-center">Orders Count</th>
                                <th class="py-4 px-6 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="customer in customers.data" :key="customer.id" class="hover:bg-slate-50/50 transition-all text-xs font-bold text-slate-600">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-700 font-black text-xs">
                                            {{ customer.name.charAt(0) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-xs font-black text-slate-900 uppercase tracking-tight">{{ customer.name }}</div>
                                            <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">ID: #USR-{{ String(customer.id).padStart(4, '0') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-slate-500 lowercase">
                                    {{ customer.email }}
                                </td>
                                <td class="py-4 px-6 text-[10px] font-black text-slate-900 uppercase">
                                    {{ new Date(customer.created_at).toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: 'numeric' }) }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span 
                                        class="inline-block px-2.5 py-1 text-[9px] font-black rounded-full"
                                        :class="customer.orders_count > 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-400'"
                                    >
                                        {{ customer.orders_count }} orders
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <span class="bg-green-50 text-green-600 text-[8px] font-black uppercase tracking-[0.2em] px-2 py-0.5 rounded-full border border-green-100">
                                        Active
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="customers.data.length === 0">
                                <td colspan="5" class="py-12 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    No registered customers found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="customers.links && customers.links.length > 3" class="p-6 border-t border-slate-50 bg-slate-50/50 flex justify-center">
                    <div class="flex space-x-1.5">
                        <template v-for="(link, i) in customers.links" :key="i">
                            <div 
                                v-if="link.url === null" 
                                class="px-3 py-1.5 text-[9px] font-black text-slate-300 uppercase tracking-widest rounded border border-slate-100 cursor-not-allowed select-none"
                                v-html="link.label"
                             />
                            <Link 
                                v-else 
                                :href="link.url" 
                                class="px-3 py-1.5 text-[9px] font-black uppercase tracking-widest rounded border transition-all"
                                :class="link.active 
                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-900/10' 
                                    : 'bg-white text-slate-500 border-slate-100 hover:bg-slate-50'"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Repeated Customers Table -->
            <div v-if="activeTab === 'repeated'" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">High Loyalty Customer List (>= 2 purchases)</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="py-4 px-6">User Identity</th>
                                <th class="py-4 px-6">Email Protocol</th>
                                <th class="py-4 px-6">Join Date</th>
                                <th class="py-4 px-6 text-center">Orders Count</th>
                                <th class="py-4 px-6 text-right">Loyalty Class</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="customer in repeatedCustomers" :key="customer.id" class="hover:bg-slate-50/50 transition-all text-xs font-bold text-slate-600">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-700 font-black text-xs">
                                            {{ customer.name.charAt(0) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-xs font-black text-slate-900 uppercase tracking-tight">{{ customer.name }}</div>
                                            <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">ID: #USR-{{ String(customer.id).padStart(4, '0') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-slate-500 lowercase">
                                    {{ customer.email }}
                                </td>
                                <td class="py-4 px-6 text-[10px] font-black text-slate-900 uppercase">
                                    {{ new Date(customer.created_at).toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: 'numeric' }) }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block px-3 py-1 bg-orange-50 text-orange-700 text-[10px] font-black rounded-full border border-orange-100 animate-pulse">
                                        🔥 {{ customer.orders_count }} orders
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <span class="bg-[#003366] text-white text-[8px] font-black uppercase tracking-[0.2em] px-2 py-0.5 rounded-full border border-[#003366]">
                                        REPEATED
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="repeatedCustomers.length === 0">
                                <td colspan="5" class="py-12 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    No repeated customers yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Users } from 'lucide-vue-next';

defineProps({
    customers: Object,
    repeatedCustomers: Array
});

const activeTab = ref('all');
</script>
