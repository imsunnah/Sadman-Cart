<template>
    <AdminLayout>
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight italic uppercase">Order <span class="text-[#003366]">Management</span></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Operational logistics and fulfillment tracking</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="px-6 py-4 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-end">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Total Entities</span>
                    <span class="text-xl font-black text-[#003366] italic">{{ orders.total }}</span>
                </div>
            </div>
        </div>

        <!-- Notification -->
        <div v-if="$page.props.flash?.success" class="mb-8 bg-[#003366] text-white p-6 rounded-2xl shadow-xl shadow-[#003366]/20 flex items-center justify-between animate-in slide-in-from-top duration-500">
            <div class="flex items-center space-x-4">
                <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                    <CheckCircle class="w-5 h-5 text-[#FF6600]" />
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="opacity-50 hover:opacity-100 transition-opacity"><XCircle class="w-5 h-5" /></button>
        </div>

        <!-- Status Tabs -->
        <div class="mb-8 flex flex-wrap gap-3">
            <Link 
                v-for="tab in tabs" 
                :key="tab.value"
                :href="route('admin.orders.index', tab.value === 'all' ? {} : { status: tab.value })"
                class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border-2"
                :class="[
                    currentStatus === tab.value 
                    ? 'bg-[#003366] text-white border-[#003366] shadow-lg shadow-[#003366]/20' 
                    : 'bg-white text-slate-400 border-slate-100 hover:border-[#003366] hover:text-[#003366]'
                ]"
            >
                {{ tab.label }}
            </Link>
        </div>

        <!-- Orders Registry -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                            <th class="py-8 px-8 w-40">Identifier</th>
                            <th class="py-8 px-8">Customer Node</th>
                            <th class="py-8 px-8">Net Value</th>
                            <th class="py-8 px-8">Status Logic</th>
                            <th class="py-8 px-8">Timestamp</th>
                            <th class="py-8 px-8 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="order in orders.data" :key="order.id" class="hover:bg-slate-50/50 transition-all group">
                            <td class="py-8 px-8">
                                <div class="text-[11px] font-black text-[#003366] font-mono tracking-tighter italic">#ORD-{{ String(order.id).padStart(5, '0') }}</div>
                                <div class="mt-1 flex gap-1">
                                    <div v-if="order.status === 'pending'" class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></div>
                                    <div v-if="order.status === 'processing'" class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></div>
                                    <div v-if="order.status === 'completed'" class="w-2 h-2 rounded-full bg-green-400"></div>
                                    <div v-if="order.status === 'cancelled'" class="w-2 h-2 rounded-full bg-red-400"></div>
                                </div>
                            </td>
                            <td class="py-8 px-8">
                                <div class="text-sm font-black text-slate-900 uppercase tracking-tight italic">{{ order.customer_name }}</div>
                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1 flex items-center">
                                    <Phone class="w-3 h-3 mr-1" /> {{ order.customer_phone }}
                                </div>
                            </td>
                            <td class="py-8 px-8">
                                <div class="text-sm font-black text-slate-900">৳{{ parseFloat(order.total_amount).toLocaleString() }}</div>
                                <div class="text-[8px] font-black text-green-500 uppercase tracking-widest mt-1">COD Protocol</div>
                            </td>
                            <td class="py-8 px-8">
                                <select @change="updateStatus(order, $event.target.value)" :value="order.status" class="text-[9px] font-black uppercase tracking-widest px-4 py-3 rounded-xl border-2 border-slate-100 outline-none focus:ring-4 focus:ring-[#003366]/5 appearance-none bg-slate-50 cursor-pointer w-full max-w-[160px] text-center transition-all" :class="getStatusClass(order.status)">
                                    <option value="pending">PENDING</option>
                                    <option value="processing">PROCESSING</option>
                                    <option value="completed">COMPLETED</option>
                                    <option value="cancelled">CANCELLED</option>
                                </select>
                            </td>
                            <td class="py-8 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                                {{ new Date(order.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                            </td>
                            <td class="py-8 px-8 text-right">
                                <Link :href="`/admin/orders/${order.id}`" class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 hover:bg-[#003366] hover:text-white transition-all inline-flex items-center justify-center shadow-sm group-hover:scale-105">
                                    <Eye class="w-5 h-5" />
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="orders.data.length === 0">
                            <td colspan="6" class="py-32 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <ShoppingBag class="w-16 h-16 mb-4" />
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">No active order entities found in this sector</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Registry -->
            <div v-if="orders.last_page > 1" class="p-10 bg-slate-50/50 border-t border-slate-100 flex justify-center space-x-3">
                <Link v-for="link in orders.links" :key="link.label" 
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border-2"
                    :class="[
                        link.active ? 'bg-[#003366] text-white border-[#003366] shadow-xl shadow-[#003366]/20' : 'bg-white text-slate-400 border-slate-100 hover:border-[#003366]',
                        !link.url ? 'opacity-30 cursor-not-allowed' : ''
                    ]"
                ></Link>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Eye, CheckCircle, XCircle, ShoppingBag, Phone } from 'lucide-vue-next';

defineProps({
    orders: Object,
    currentStatus: String
});

const tabs = [
    { label: 'All Orders', value: 'all' },
    { label: 'Pending', value: 'pending' },
    { label: 'Processing', value: 'processing' },
    { label: 'Delivered', value: 'completed' },
    { label: 'Cancelled', value: 'cancelled' },
];

const updateStatus = (order, status) => {
    router.put(`/admin/orders/${order.id}`, {
        status: status
    }, {
        preserveScroll: true
    });
};

const getStatusClass = (status) => {
    switch (status) {
        case 'pending': return 'text-yellow-600 border-yellow-100 bg-yellow-50/50';
        case 'processing': return 'text-blue-600 border-blue-100 bg-blue-50/50';
        case 'completed': return 'text-green-600 border-green-100 bg-green-50/50';
        case 'cancelled': return 'text-red-600 border-red-100 bg-red-50/50';
        default: return 'text-slate-400';
    }
};
</script>
