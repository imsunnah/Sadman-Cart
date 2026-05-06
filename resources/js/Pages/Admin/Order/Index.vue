<template>
    <AdminLayout>
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase">Order <span class="text-[#003366]">Management</span></h1>
                <p class="text-sm font-bold text-slate-400 mt-1">Manage all your customer orders from here</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="px-6 py-4 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-end">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Orders</span>
                    <span class="text-xl font-black text-[#003366]">{{ orders.total }}</span>
                </div>
            </div>
        </div>

        <!-- Notification -->
        <div v-if="$page.props.flash?.success" class="mb-8 bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <CheckCircle class="w-5 h-5" />
                <span class="text-sm font-bold">{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="opacity-50 hover:opacity-100 transition-opacity"><XCircle class="w-5 h-5" /></button>
        </div>

        <!-- Status Tabs -->
        <div class="mb-8 flex flex-wrap gap-3">
            <Link 
                v-for="tab in tabs" 
                :key="tab.value"
                :href="route('admin.orders.index', tab.value === 'all' ? {} : { status: tab.value })"
                class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all border-2"
                :class="[
                    currentStatus === tab.value 
                    ? 'bg-[#003366] text-white border-[#003366] shadow-md' 
                    : 'bg-white text-slate-500 border-slate-200 hover:border-[#003366] hover:text-[#003366]'
                ]"
            >
                {{ tab.label }}
            </Link>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50 text-xs font-black text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <th class="py-4 px-6">Order ID</th>
                            <th class="py-4 px-6">Customer Info</th>
                            <th class="py-4 px-6 w-64">Items (Name & Qty)</th>
                            <th class="py-4 px-6">Total Amount</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="order in orders.data" :key="order.id" class="hover:bg-slate-50 transition-colors">
                            <td class="py-5 px-6">
                                <div class="text-sm font-black text-[#003366]">#{{ String(order.id).padStart(5, '0') }}</div>
                                <div class="text-[10px] font-bold text-slate-400 mt-1">
                                    {{ new Date(order.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                                </div>
                            </td>
                            <td class="py-5 px-6">
                                <div class="text-sm font-bold text-slate-900">{{ order.customer_name }}</div>
                                <div class="text-xs text-slate-500 mt-1 flex items-center">
                                    <Phone class="w-3 h-3 mr-1 text-slate-400" /> {{ order.customer_phone }}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1 line-clamp-1">
                                    {{ order.district }}, {{ order.upazila }}
                                </div>
                            </td>
                            <td class="py-5 px-6">
                                <ul class="space-y-1">
                                    <li v-for="item in order.items" :key="item.id" class="text-xs flex justify-between gap-2 border-b border-slate-50 pb-1 last:border-0">
                                        <span class="font-medium text-slate-700 truncate" :title="item.product_name">{{ item.product_name }}</span>
                                        <span class="font-bold text-slate-500 whitespace-nowrap">x{{ item.quantity }}</span>
                                    </li>
                                </ul>
                            </td>
                            <td class="py-5 px-6">
                                <div class="text-sm font-black text-slate-900">৳{{ parseFloat(order.total_amount).toLocaleString() }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase mt-1">{{ order.payment_method }}</div>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <select @change="initiateStatusUpdate(order, $event.target.value, $event)" :value="order.status" class="text-xs font-bold uppercase px-3 py-2 rounded-lg border outline-none cursor-pointer w-full text-center transition-all appearance-none" :class="getStatusClass(order.status)">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </td>
                            <td class="py-5 px-6 text-right">
                                <Link :href="`/admin/orders/${order.id}`" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-[#003366] text-slate-600 hover:text-white rounded-lg text-xs font-bold transition-colors">
                                    <Eye class="w-4 h-4" /> View Details
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="orders.data.length === 0">
                            <td colspan="6" class="py-20 text-center">
                                <div class="flex flex-col items-center opacity-40">
                                    <ShoppingBag class="w-12 h-12 mb-3 text-slate-400" />
                                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">No orders found</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div v-if="orders.last_page > 1" class="p-6 bg-slate-50 border-t border-slate-100 flex justify-center space-x-2">
                <Link v-for="link in orders.links" :key="link.label" 
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="px-4 py-2 rounded-lg text-xs font-bold transition-all border"
                    :class="[
                        link.active ? 'bg-[#003366] text-white border-[#003366]' : 'bg-white text-slate-500 border-slate-200 hover:border-[#003366]',
                        !link.url ? 'opacity-50 cursor-not-allowed bg-slate-100' : ''
                    ]"
                ></Link>
            </div>
        </div>

        <!-- Confirm Status Update Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showStatusModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl">
                    <h3 class="text-xl font-bold text-slate-900 text-center mb-4">Are you sure?</h3>
                    <p class="text-sm text-slate-500 text-center mb-8">
                        Do you really want to change the status of Order <span class="font-bold text-[#003366]">#{{ String(orderToUpdate?.id).padStart(5, '0') }}</span> to <span class="font-bold text-[#003366] uppercase">{{ newStatus }}</span>?
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showStatusModal = false" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">Cancel</button>
                        <button @click="confirmStatusUpdate" class="px-4 py-3 rounded-xl bg-[#003366] text-white font-bold hover:bg-slate-800 transition-all shadow-lg">Confirm</button>
                    </div>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
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

const showStatusModal = ref(false);
const orderToUpdate = ref(null);
const newStatus = ref('');

const initiateStatusUpdate = (order, status, event) => {
    event.target.value = order.status; // Revert visually until confirmed
    orderToUpdate.value = order;
    newStatus.value = status;
    showStatusModal.value = true;
};

const confirmStatusUpdate = () => {
    router.put(`/admin/orders/${orderToUpdate.value.id}`, {
        status: newStatus.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showStatusModal.value = false;
        }
    });
};

const getStatusClass = (status) => {
    switch (status) {
        case 'pending': return 'text-yellow-700 border-yellow-200 bg-yellow-50';
        case 'processing': return 'text-blue-700 border-blue-200 bg-blue-50';
        case 'completed': return 'text-green-700 border-green-200 bg-green-50';
        case 'cancelled': return 'text-red-700 border-red-200 bg-red-50';
        default: return 'text-slate-500 border-slate-200 bg-slate-50';
    }
};
</script>
