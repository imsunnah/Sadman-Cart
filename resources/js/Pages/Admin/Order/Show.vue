<template>
    <AdminLayout>
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#003366]">Order Details</h1>
                <p class="text-sm text-slate-500 mt-1">Reference: <span class="font-mono font-bold text-[#FF6600]">#ORD-{{ String(order.id).padStart(5, '0') }}</span></p>
            </div>
            <Link href="/admin/orders" class="flex items-center text-sm font-bold text-slate-400 hover:text-[#003366] transition-colors">
                <ArrowLeft class="w-4 h-4 mr-2" /> Return to List
            </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Order Info & Items -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Items Manifest -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 flex items-center space-x-2">
                        <Package class="w-5 h-5 text-[#0099FF]" />
                        <h2 class="font-bold text-[#003366]">Inventory Manifest</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    <th class="py-4 px-8">Product Designation</th>
                                    <th class="py-4 px-8 text-center">Volume</th>
                                    <th class="py-4 px-8 text-right">Valuation</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-for="item in order.items" :key="item.id">
                                    <td class="py-5 px-8">
                                        <div class="text-sm font-bold text-slate-900">{{ item.product_name }}</div>
                                    </td>
                                    <td class="py-5 px-8 text-center text-sm text-slate-500 font-bold">x{{ item.quantity }}</td>
                                    <td class="py-5 px-8 text-right text-sm font-bold text-slate-900">৳{{ parseFloat(item.price * item.quantity).toLocaleString() }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-[#003366] text-white">
                                <tr>
                                    <td colspan="2" class="py-6 px-8 text-xs font-bold uppercase tracking-widest">Aggregate Total</td>
                                    <td class="py-6 px-8 text-right text-xl font-bold">৳{{ parseFloat(order.total_amount).toLocaleString() }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Logistics Log -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 space-y-6">
                    <div class="flex items-center space-x-2">
                        <Truck class="w-5 h-5 text-[#FF6600]" />
                        <h2 class="font-bold text-[#003366]">Logistics Destination</h2>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-sm text-slate-600 leading-relaxed font-medium">{{ order.shipping_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Status & Customer -->
            <div class="space-y-8">
                <!-- Operational Status -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 space-y-6">
                    <div class="flex items-center space-x-2">
                        <ShieldCheck class="w-5 h-5 text-[#0099FF]" />
                        <h2 class="font-bold text-[#003366]">Operational Control</h2>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Synchronization Status</label>
                        <select @change="updateStatus($event.target.value)" :value="order.status" class="w-full px-6 py-4 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-[#0099FF]/10 outline-none transition-all font-bold text-sm" :class="getStatusClass(order.status)">
                            <option value="pending">PENDING</option>
                            <option value="processing">PROCESSING</option>
                            <option value="completed">COMPLETED</option>
                            <option value="cancelled">CANCELLED</option>
                        </select>
                        <p class="text-[10px] text-slate-400 font-medium px-1">Updating status will dispatch an automated notification to the customer entity.</p>
                    </div>
                </div>

                <!-- Customer Entity -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 space-y-6">
                    <div class="flex items-center space-x-2">
                        <User class="w-5 h-5 text-[#FFCC00]" />
                        <h2 class="font-bold text-[#003366]">Customer Profile</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Entity Name</span>
                            <span class="text-sm font-bold text-slate-900">{{ order.customer_name }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Electronic Mail</span>
                            <span class="text-sm font-bold text-slate-900">{{ order.customer_email }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Telecom Line</span>
                            <span class="text-sm font-bold text-slate-900">{{ order.customer_phone }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ArrowLeft, Package, Truck, ShieldCheck, User } from 'lucide-vue-next';

const props = defineProps({
    order: Object
});

const updateStatus = (status) => {
    router.put(`/admin/orders/${props.order.id}`, {
        status: status
    });
};

const getStatusClass = (status) => {
    switch (status) {
        case 'pending': return 'text-yellow-600';
        case 'processing': return 'text-blue-600';
        case 'completed': return 'text-green-600';
        case 'cancelled': return 'text-red-600';
        default: return 'text-slate-600';
    }
};
</script>
