<template>
    <AdminLayout>
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase">Order Details</h1>
                <p class="text-sm font-bold text-slate-500 mt-1">Order ID: <span class="font-mono text-[#003366]">#{{ String(order.id).padStart(5, '0') }}</span></p>
            </div>
            <Link href="/admin/orders" class="flex items-center text-sm font-bold text-slate-500 hover:text-[#003366] transition-colors bg-white px-4 py-2 rounded-lg border shadow-sm">
                <ArrowLeft class="w-4 h-4 mr-2" /> Back to Orders
            </Link>
        </div>

        <!-- Notification -->
        <div v-if="$page.props.flash?.success" class="mb-8 bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <CheckCircle class="w-5 h-5" />
                <span class="text-sm font-bold">{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="opacity-50 hover:opacity-100 transition-opacity"><XCircle class="w-5 h-5" /></button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Order Info & Items -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Ordered Items -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center space-x-3 bg-slate-50">
                        <Package class="w-5 h-5 text-[#003366]" />
                        <h2 class="font-black text-[#003366] uppercase tracking-wider text-sm">Ordered Items</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    <th class="py-4 px-6">Product Name</th>
                                    <th class="py-4 px-6 text-center">Quantity</th>
                                    <th class="py-4 px-6 text-right">Total Price</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-for="item in order.items" :key="item.id" class="hover:bg-slate-50 transition-colors">
                                    <td class="py-5 px-6">
                                        <div class="text-sm font-bold text-slate-800">{{ item.product_name }}</div>
                                        <div class="text-xs text-slate-400 mt-1">Unit Price: ৳{{ parseFloat(item.price).toLocaleString() }}</div>
                                    </td>
                                    <td class="py-5 px-6 text-center text-sm text-slate-600 font-bold">x{{ item.quantity }}</td>
                                    <td class="py-5 px-6 text-right text-sm font-black text-slate-900">৳{{ parseFloat(item.price * item.quantity).toLocaleString() }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-slate-50 text-slate-900 border-t border-slate-200">
                                <tr class="border-b border-slate-100">
                                    <td colspan="2" class="py-4 px-6 text-[10px] font-black uppercase tracking-widest text-right text-slate-400">Subtotal:</td>
                                    <td class="py-4 px-6 text-right text-sm font-bold">৳{{ (order.total_amount - order.delivery_charge).toLocaleString() }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td colspan="2" class="py-4 px-6 text-[10px] font-black uppercase tracking-widest text-right text-slate-400">Delivery ({{ order.delivery_location }}):</td>
                                    <td class="py-4 px-6 text-right text-sm font-bold text-[#FF6600]">৳{{ parseFloat(order.delivery_charge).toLocaleString() }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="py-6 px-6 text-sm font-black uppercase tracking-wider text-right">Grand Total:</td>
                                    <td class="py-6 px-6 text-right text-xl font-black text-[#003366]">৳{{ parseFloat(order.total_amount).toLocaleString() }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center space-x-3">
                            <Truck class="w-5 h-5 text-[#FF6600]" />
                            <h2 class="font-black text-[#003366] uppercase tracking-wider text-sm">Shipping Address</h2>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase">Destination:</span>
                            <select 
                                @change="initiateLocationUpdate($event.target.value, $event)" 
                                :value="order.delivery_location" 
                                :disabled="order.status === 'completed' || order.status === 'cancelled'"
                                class="text-[10px] font-black uppercase px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 outline-none cursor-pointer hover:border-[#FF6600] transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <option value="Inside Dhaka">Inside Dhaka</option>
                                <option value="Outside Dhaka">Outside Dhaka</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">District</span>
                            <span class="text-sm font-bold text-slate-800">{{ order.district || 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Upazila</span>
                            <span class="text-sm font-bold text-slate-800">{{ order.upazila || 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Village / Area</span>
                            <span class="text-sm font-bold text-slate-800">{{ order.village || 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Full Detailed Address</span>
                            <span class="text-sm font-medium text-slate-700 leading-relaxed">{{ order.shipping_address }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Status & Customer -->
            <div class="space-y-8">
                <!-- Status & Payment -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200 space-y-6">
                    <div class="flex items-center space-x-3 border-b border-slate-100 pb-4">
                        <ShieldCheck class="w-5 h-5 text-[#0099FF]" />
                        <h2 class="font-black text-[#003366] uppercase tracking-wider text-sm">Order Status</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Payment Method</label>
                            <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-2">
                                <Banknote class="w-4 h-4 text-slate-500" />
                                <span class="text-sm font-bold text-slate-800 uppercase">{{ order.payment_method }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-4">Current Status</label>
                            <select 
                                @change="initiateStatusUpdate($event.target.value, $event)" 
                                :value="order.status" 
                                :disabled="order.status === 'completed' || order.status === 'cancelled'"
                                class="w-full px-4 py-3 rounded-xl border outline-none font-bold text-sm uppercase transition-all cursor-pointer shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" 
                                :class="getStatusClass(order.status)"
                            >
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <p v-if="order.status !== 'completed' && order.status !== 'cancelled'" class="text-[10px] text-slate-400 font-medium mt-2 italic leading-tight">Changing the status will notify the customer via email and may affect inventory census.</p>
                            <p v-else class="text-[10px] text-green-600 font-black mt-2 uppercase tracking-widest">This order is in a final state and cannot be modified.</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200 space-y-6">
                    <div class="flex items-center space-x-3 border-b border-slate-100 pb-4">
                        <User class="w-5 h-5 text-[#FFCC00]" />
                        <h2 class="font-black text-[#003366] uppercase tracking-wider text-sm">Customer Info</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-slate-400 shadow-sm"><User class="w-5 h-5" /></div>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Name</span>
                                <span class="text-sm font-black text-slate-900">{{ order.customer_name }}</span>
                            </div>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-slate-400 shadow-sm"><Phone class="w-5 h-5" /></div>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Phone</span>
                                <span class="text-sm font-black text-slate-900">{{ order.customer_phone }}</span>
                            </div>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-slate-400 shadow-sm"><Mail class="w-5 h-5" /></div>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</span>
                                <span class="text-sm font-black text-slate-900">{{ order.customer_email || 'Not Provided' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Status Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showStatusModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2rem] p-10 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-20 h-20 bg-blue-50 text-[#003366] rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <AlertTriangle class="w-10 h-10" />
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase tracking-tighter italic">Confirm Status?</h3>
                    <p class="text-sm text-slate-500 mb-10 font-medium">
                        Are you sure you want to change order status to <span class="font-black text-[#003366] uppercase">{{ newStatus }}</span>?
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showStatusModal = false" class="px-6 py-4 rounded-2xl border-2 border-slate-100 text-slate-400 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50 transition-all">Cancel</button>
                        <button @click="confirmStatusUpdate" class="px-6 py-4 rounded-2xl bg-[#003366] text-white font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all shadow-xl shadow-blue-900/20">Confirm</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Confirm Location Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showLocationModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2rem] p-10 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-20 h-20 bg-orange-50 text-[#FF6600] rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <Truck class="w-10 h-10" />
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase tracking-tighter italic">Update Destination?</h3>
                    <p class="text-sm text-slate-500 mb-10 font-medium leading-relaxed">
                        Moving to <span class="font-black text-[#FF6600] uppercase">{{ newLocation }}</span> will adjust delivery charges and update the grand total.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showLocationModal = false" class="px-6 py-4 rounded-2xl border-2 border-slate-100 text-slate-400 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50 transition-all">Cancel</button>
                        <button @click="confirmLocationUpdate" class="px-6 py-4 rounded-2xl bg-[#FF6600] text-white font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all shadow-xl shadow-orange-500/20">Update</button>
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
import { ArrowLeft, Package, Truck, ShieldCheck, User, CheckCircle, XCircle, Banknote, Phone, Mail, AlertTriangle } from 'lucide-vue-next';

const props = defineProps({
    order: Object
});

const showStatusModal = ref(false);
const showLocationModal = ref(false);
const newStatus = ref('');
const newLocation = ref('');

const initiateStatusUpdate = (status, event) => {
    event.target.value = props.order.status; // Revert visually
    newStatus.value = status;
    showStatusModal.value = true;
};

const confirmStatusUpdate = () => {
    router.put(`/admin/orders/${props.order.id}`, {
        status: newStatus.value
    }, {
        preserveScroll: true,
        onSuccess: () => showStatusModal.value = false
    });
};

const initiateLocationUpdate = (location, event) => {
    event.target.value = props.order.delivery_location; // Revert visually
    newLocation.value = location;
    showLocationModal.value = true;
};

const confirmLocationUpdate = () => {
    router.put(`/admin/orders/${props.order.id}`, {
        delivery_location: newLocation.value
    }, {
        preserveScroll: true,
        onSuccess: () => showLocationModal.value = false
    });
};

const getStatusClass = (status) => {
    switch (status) {
        case 'pending': return 'text-yellow-700 bg-yellow-50 border-yellow-200';
        case 'processing': return 'text-blue-700 bg-blue-50 border-blue-200';
        case 'completed': return 'text-green-700 bg-green-50 border-green-200';
        case 'cancelled': return 'text-red-700 bg-red-50 border-red-200';
        default: return 'text-slate-700 bg-slate-50 border-slate-200';
    }
};
</script>
