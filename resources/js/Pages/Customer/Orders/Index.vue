<template>
    <StoreLayout>
        <div class="bg-[#F8F9FA] min-h-screen py-16 font-sans">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Page Title -->
                <div class="text-center mb-16">
                    <span class="text-[9px] font-black text-[#FF6600] uppercase tracking-[0.4em] mb-2 block">Customer Panel</span>
                    <h1 class="text-4xl font-black text-[#003366] uppercase tracking-tighter italic">My Order History</h1>
                    <div class="w-12 h-1 bg-[#FF6600] mx-auto mt-4 rounded-full"></div>
                </div>

                <!-- Orders List -->
                <div v-if="orders.data && orders.data.length > 0" class="bg-white rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden p-6 sm:p-10">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    <th class="py-4 px-6">Order ID</th>
                                    <th class="py-4 px-6">Date</th>
                                    <th class="py-4 px-6">Total Amount</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-for="order in orders.data" :key="order.id" class="text-xs font-bold text-slate-600 hover:bg-slate-50/50 transition-colors">
                                    <td class="py-5 px-6">
                                        <span class="text-[#003366] font-black">#{{ String(order.id).padStart(6, '0') }}</span>
                                    </td>
                                    <td class="py-5 px-6 text-slate-500">
                                        {{ new Date(order.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) }}
                                    </td>
                                    <td class="py-5 px-6 text-slate-900 font-black">
                                        ৳ {{ parseFloat(order.total_amount).toLocaleString() }}
                                    </td>
                                    <td class="py-5 px-6">
                                        <span 
                                            class="inline-block px-3 py-1 text-[9px] font-black uppercase tracking-wider rounded-full"
                                            :class="{
                                                'bg-amber-50 text-amber-700 border border-amber-100': order.status === 'pending',
                                                'bg-blue-50 text-blue-700 border border-blue-100': order.status === 'processing',
                                                'bg-emerald-50 text-emerald-700 border border-emerald-100': order.status === 'completed',
                                                'bg-rose-50 text-rose-700 border border-rose-100': order.status === 'cancelled'
                                            }"
                                        >
                                            {{ order.status }}
                                        </span>
                                    </td>
                                    <td class="py-5 px-6 text-right">
                                        <Link 
                                            :href="`/customer/orders/${order.id}`" 
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#003366] text-white rounded-xl font-black uppercase tracking-widest text-[9px] hover:bg-[#FF6600] transition-all shadow-md shadow-blue-900/5 hover:shadow-orange-500/10 hover:-translate-y-0.5"
                                        >
                                            <Eye class="w-3.5 h-3.5" />
                                            Trace & Details
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="orders.links && orders.links.length > 3" class="flex justify-center mt-12">
                        <nav class="flex items-center gap-1.5">
                            <template v-for="(link, i) in orders.links" :key="i">
                                <div 
                                    v-if="link.url === null" 
                                    class="px-3.5 py-2 text-[10px] font-black text-slate-300 uppercase tracking-widest rounded-lg border border-slate-100 cursor-not-allowed select-none"
                                    v-html="link.label"
                                 />
                                <Link 
                                    v-else 
                                    :href="link.url" 
                                    class="px-3.5 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg border transition-all"
                                    :class="link.active 
                                        ? 'bg-[#003366] text-white border-[#003366] shadow-md shadow-blue-900/10' 
                                        : 'bg-white text-slate-600 border-slate-100 hover:bg-slate-50'"
                                    v-html="link.label"
                                />
                            </template>
                        </nav>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-20 bg-white rounded-[2rem] border border-slate-100 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] p-12">
                    <ShoppingBag class="w-16 h-16 text-slate-300 mx-auto mb-6" />
                    <h2 class="text-xl font-black text-[#003366] uppercase tracking-tight">No Orders Initiated</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-sm mx-auto mt-2 leading-relaxed">
                        You have not placed any orders yet. Once you place an order, it will show up here for live tracing and communication.
                    </p>
                    <div class="mt-8">
                        <Link href="/shop" class="inline-flex items-center gap-2 bg-[#FF6600] text-white px-8 py-3.5 rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-[#e55c00] transition-all shadow-md">
                            Go Shopping
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { Eye, ShoppingBag } from 'lucide-vue-next';

defineProps({
    orders: Object
});
</script>
