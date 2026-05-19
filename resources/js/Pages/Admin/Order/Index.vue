<template>
    <AdminLayout>
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase">Order <span class="text-[#003366]">Management</span></h1>
                <p class="text-sm font-bold text-slate-400 mt-1">Manage all your customer orders from here</p>
            </div>
            <div class="flex items-center space-x-4">
                <Link :href="route('admin.orders.create')" class="px-6 py-4 bg-[#003366] text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#FF6600] transition-all flex items-center gap-2 shadow-xl shadow-blue-900/10">
                    <Plus class="w-4 h-4" /> Create Manual Order
                </Link>
                <div class="px-6 py-4 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-end">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Orders</span>
                    <span class="text-xl font-black text-[#003366]">{{ orders.total }}</span>
                </div>
            </div>
        </div>

        <!-- Overdue Pending Orders Alert -->
        <div v-if="overduePendingCount > 0" class="mb-8 bg-red-50 border border-red-200 text-red-800 p-5 rounded-2xl flex items-center justify-between shadow-sm animate-pulse">
            <div class="flex items-center space-x-3">
                <AlertTriangle class="w-5 h-5 text-red-600 shrink-0" />
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-red-900">Urgent Pending Action Required</h4>
                    <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest mt-0.5">{{ overduePendingCount }} items is pending more than 2 days</p>
                </div>
            </div>
            <Link :href="route('admin.orders.index', { status: 'pending' })" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                Review Pending
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

        <!-- Filters Section -->
        <div class="mb-8 space-y-6">
            <!-- Status Tabs -->
            <div class="flex flex-wrap gap-3">
                <Link 
                    v-for="tab in tabs" 
                    :key="tab.value"
                    :href="route('admin.orders.index', getFilterParams({ status: tab.value }))"
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

            <!-- Resource Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 bg-slate-50 p-6 rounded-2xl border border-slate-200">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Filter by Category</label>
                    <select 
                        v-model="filterForm.category_id" 
                        @change="applyFilters"
                        class="w-full px-4 py-2.5 rounded-xl bg-white border-slate-200 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#003366]/10 transition-all"
                    >
                        <option value="all">All Categories</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Filter by Product</label>
                    <select 
                        v-model="filterForm.product_id" 
                        @change="applyFilters"
                        class="w-full px-4 py-2.5 rounded-xl bg-white border-slate-200 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#003366]/10 transition-all"
                    >
                        <option value="all">All Products</option>
                        <option v-for="prod in products" :key="prod.id" :value="prod.id">{{ prod.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Filter by Combo</label>
                    <select 
                        v-model="filterForm.combo_id" 
                        @change="applyFilters"
                        class="w-full px-4 py-2.5 rounded-xl bg-white border-slate-200 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#003366]/10 transition-all"
                    >
                        <option value="all">All Combos</option>
                        <option v-for="combo in combos" :key="combo.id" :value="combo.id">{{ combo.name }}</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button @click="resetFilters" class="px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Reset Filters
                    </button>
                </div>
            </div>
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
                        <tr v-for="order in orders.data" :key="order.id" 
                            class="transition-colors"
                            :class="[
                                isOrderOverdue(order) 
                                ? 'bg-red-50/30 hover:bg-red-50/60' 
                                : 'hover:bg-slate-50'
                            ]"
                        >
                            <td class="py-5 px-6 relative">
                                <!-- Dynamic Status Accent Bar -->
                                <div 
                                    class="absolute left-0 top-0 bottom-0 w-1.5"
                                    :class="{
                                        'bg-red-600 animate-pulse': isOrderOverdue(order),
                                        'bg-[#FF6600]': order.status === 'pending' && !isOrderOverdue(order),
                                        'bg-blue-500': order.status === 'processing',
                                        'bg-green-500': order.status === 'completed',
                                        'bg-red-500': order.status === 'cancelled'
                                    }"
                                ></div>
                                <div class="pl-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-black text-[#003366] tracking-tight">#{{ String(order.id).padStart(5, '0') }}</span>
                                        <span v-if="isOrderOverdue(order)" class="inline-flex items-center gap-0.5 text-[8px] font-black uppercase bg-red-600 text-white px-1.5 py-0.5 rounded tracking-widest animate-pulse">
                                            <AlertTriangle class="w-2.5 h-2.5" /> Overdue
                                        </span>
                                    </div>
                                    <div class="text-[10px] font-bold text-slate-400 mt-1">
                                        {{ new Date(order.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                                    </div>
                                    <div class="mt-2">
                                        <button @click="toggleActive(order.id)" :class="order.is_active ? 'bg-green-50 text-green-600 border-green-100' : 'bg-slate-50 text-slate-400 border-slate-100'" class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full border">
                                            {{ order.is_active ? 'Active' : 'Archived' }}
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-6">
                                <div class="text-sm font-bold text-slate-900">{{ order.customer_name }}</div>
                                <div class="text-xs text-slate-500 mt-1 flex items-center font-semibold">
                                    <Phone class="w-3.5 h-3.5 mr-1 text-[#FF6600]" /> {{ order.customer_phone }}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1.5 space-y-0.5">
                                    <div class="flex items-start">
                                        <MapPin class="w-3.5 h-3.5 mr-1 mt-0.5 flex-shrink-0 text-slate-400" />
                                        <span class="line-clamp-2">{{ order.shipping_address }}</span>
                                    </div>
                                    <div class="pl-4 text-[#003366] font-bold">
                                        {{ order.district }}, {{ order.upazila }}
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-6">
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <Truck class="w-4 h-4 text-[#003366]" />
                                        <select 
                                            @change="initiateLocationUpdate(order, $event.target.value, $event)" 
                                            :value="order.delivery_location" 
                                            :disabled="order.status === 'completed' || order.status === 'cancelled'"
                                            class="text-[10px] font-black uppercase px-2 py-1 rounded bg-slate-50 border border-slate-200 outline-none cursor-pointer hover:border-[#003366] transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <option value="Inside Dhaka">Inside Dhaka</option>
                                            <option value="Outside Dhaka">Outside Dhaka</option>
                                        </select>
                                    </div>
                                    <ul class="space-y-2 mt-3">
                                        <li v-for="item in order.items" :key="item.id" class="flex items-center gap-2 border-b border-slate-50 pb-2 last:border-0">
                                            <div class="w-8 h-8 rounded bg-white flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-100 p-0.5">
                                                <img 
                                                    v-if="item.product && item.product.image" 
                                                    :src="item.product.image.startsWith('http') ? item.product.image : `/storage/${item.product.image}`" 
                                                    class="w-full h-full object-contain" 
                                                />
                                                <img 
                                                    v-else-if="item.combo && item.combo.image" 
                                                    :src="item.combo.image.startsWith('http') ? item.combo.image : `/storage/${item.combo.image}`" 
                                                    class="w-full h-full object-contain" 
                                                />
                                                <Package v-else class="w-4 h-4 text-slate-300" />
                                            </div>
                                            <div class="flex-grow min-w-0">
                                                <p class="text-[10px] font-black text-slate-700 truncate italic" :title="item.product_name">{{ item.product_name }}</p>
                                                <p class="text-[9px] font-bold text-slate-400">৳{{ parseFloat(item.price).toLocaleString() }}</p>
                                            </div>
                                            <span class="text-[10px] font-black text-[#FF6600] shrink-0 bg-orange-50 px-1.5 py-0.5 rounded">x{{ item.quantity }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td class="py-5 px-6">
                                <div class="space-y-1.5 bg-slate-50 p-3 rounded-xl border border-slate-100 max-w-[180px]">
                                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                        <span>Items Sub:</span>
                                        <span>৳{{ (order.total_amount - order.delivery_charge).toLocaleString() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                        <span>Delivery:</span>
                                        <div class="flex items-center">
                                            <span class="mr-1">৳</span>
                                            <input 
                                                type="number" 
                                                :value="order.delivery_charge" 
                                                :disabled="order.status === 'completed' || order.status === 'cancelled'"
                                                @blur="updateDeliveryCharge(order, $event.target.value)"
                                                class="w-12 bg-white border border-slate-200 rounded px-1 py-0.5 text-right font-black text-slate-700 text-[10px] focus:ring-1 focus:ring-[#003366] outline-none transition-all disabled:opacity-50"
                                            >
                                        </div>
                                    </div>
                                    <div class="pt-1.5 border-t border-slate-200 flex items-center justify-between">
                                        <span class="text-xs font-black text-slate-900">Payable:</span>
                                        <span class="text-xs font-black text-[#003366]">৳{{ parseFloat(order.total_amount).toLocaleString() }}</span>
                                    </div>
                                    <div class="text-[8px] font-black text-white bg-[#003366] rounded px-1.5 py-0.5 inline-block uppercase text-center w-full tracking-widest mt-1">
                                        {{ order.payment_method }}
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <div class="relative inline-block w-full">
                                    <select 
                                        @change="initiateStatusUpdate(order, $event.target.value, $event)" 
                                        :value="order.status" 
                                        :disabled="order.status === 'completed' || order.status === 'cancelled'"
                                        class="text-xs font-black uppercase pl-3 pr-8 py-2.5 rounded-xl border outline-none cursor-pointer w-full text-center transition-all appearance-none disabled:opacity-50 disabled:cursor-not-allowed" 
                                        :class="getStatusClass(order.status)"
                                    >
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                    <ChevronDown class="absolute right-2.5 top-3.5 w-3.5 h-3.5 pointer-events-none opacity-60" />
                                </div>
                            </td>
                            <td class="py-5 px-6 text-right">
                                <Link :href="`/admin/orders/${order.id}`" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-[#003366] text-slate-600 hover:text-white rounded-xl text-xs font-black uppercase tracking-wider transition-colors border border-slate-200 hover:border-[#003366]">
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
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-16 h-16 bg-blue-50 text-[#003366] rounded-full flex items-center justify-center mx-auto mb-4">
                        <AlertTriangle class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Are you sure?</h3>
                    <p class="text-sm text-slate-500 mb-8">
                        Do you really want to change the status of Order <span class="font-bold text-[#003366]">#{{ String(orderToUpdate?.id).padStart(5, '0') }}</span> to <span class="font-bold text-[#003366] uppercase">{{ newStatus }}</span>?
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showStatusModal = false" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">Cancel</button>
                        <button @click="confirmStatusUpdate" class="px-4 py-3 rounded-xl bg-[#003366] text-white font-bold hover:bg-slate-800 transition-all shadow-lg">Confirm</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Confirm Location Update Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showLocationModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-16 h-16 bg-orange-50 text-[#FF6600] rounded-full flex items-center justify-center mx-auto mb-4">
                        <Truck class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Update Destination?</h3>
                    <p class="text-sm text-slate-500 mb-8">
                        Changing destination to <span class="font-bold text-[#FF6600] uppercase">{{ newLocation }}</span> will automatically update the delivery charge and total amount.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showLocationModal = false" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">Cancel</button>
                        <button @click="confirmLocationUpdate" class="px-4 py-3 rounded-xl bg-[#FF6600] text-white font-bold hover:bg-orange-600 transition-all shadow-lg">Update</button>
                    </div>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Eye, CheckCircle, XCircle, ShoppingBag, Phone, MapPin, Truck, AlertTriangle, Package, Plus, ChevronDown } from 'lucide-vue-next';

const props = defineProps({
    orders: Object,
    categories: Array,
    products: Array,
    combos: Array,
    currentStatus: String,
    currentCategoryId: [String, Number],
    currentProductId: [String, Number],
    currentComboId: [String, Number],
    overduePendingCount: Number
});

const isOrderOverdue = (order) => {
    if (order.status !== 'pending') return false;
    const createdAt = new Date(order.created_at);
    const diffTime = Math.abs(new Date() - createdAt);
    const diffDays = diffTime / (1000 * 60 * 60 * 24);
    return diffDays > 2;
};

const filterForm = reactive({
    category_id: props.currentCategoryId || 'all',
    product_id: props.currentProductId || 'all',
    combo_id: props.currentComboId || 'all'
});

const tabs = [
    { label: 'All Orders', value: 'all' },
    { label: 'Pending', value: 'pending' },
    { label: 'Processing', value: 'processing' },
    { label: 'Delivered', value: 'completed' },
    { label: 'Cancelled', value: 'cancelled' },
];

const getFilterParams = (overrides = {}) => {
    const params = {
        status: props.currentStatus === 'all' ? null : props.currentStatus,
        category_id: filterForm.category_id === 'all' ? null : filterForm.category_id,
        product_id: filterForm.product_id === 'all' ? null : filterForm.product_id,
        combo_id: filterForm.combo_id === 'all' ? null : filterForm.combo_id,
        ...overrides
    };
    // Remove 'all' or null values
    Object.keys(params).forEach(key => {
        if (params[key] === 'all' || params[key] === null) delete params[key];
    });
    return params;
};

const applyFilters = () => {
    router.get(route('admin.orders.index'), getFilterParams(), {
        preserveScroll: true,
        preserveState: true
    });
};

const resetFilters = () => {
    filterForm.category_id = 'all';
    filterForm.product_id = 'all';
    filterForm.combo_id = 'all';
    router.get(route('admin.orders.index'));
};

const toggleActive = (id) => {
    router.put(`/admin/orders/${id}/toggle-active`, {}, {
        preserveScroll: true
    });
};

const showStatusModal = ref(false);
const showLocationModal = ref(false);
const orderToUpdate = ref(null);
const newStatus = ref('');
const newLocation = ref('');

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

const initiateLocationUpdate = (order, location, event) => {
    event.target.value = order.delivery_location; // Revert visually until confirmed
    orderToUpdate.value = order;
    newLocation.value = location;
    showLocationModal.value = true;
};

const confirmLocationUpdate = () => {
    router.put(`/admin/orders/${orderToUpdate.value.id}`, {
        delivery_location: newLocation.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showLocationModal.value = false;
        }
    });
};

const updateDeliveryCharge = (order, charge) => {
    if (parseFloat(charge) === parseFloat(order.delivery_charge)) return;
    
    router.put(`/admin/orders/${order.id}`, {
        delivery_charge: charge
    }, {
        preserveScroll: true
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
