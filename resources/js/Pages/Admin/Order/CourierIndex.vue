<template>
    <AdminLayout>
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase">{{ $t('Steadfast') }} <span class="text-[#FF6600]">{{ $t('Status') }}</span></h1>
                <p class="text-sm font-bold text-slate-400 mt-1">{{ $t('Track all orders sent to Steadfast Courier') }}</p>
            </div>
            <div class="px-6 py-4 bg-orange-50 rounded-2xl border border-orange-100 flex flex-col items-end">
                <span class="text-[10px] font-black text-orange-400 uppercase tracking-widest">{{ $t('Total Steadfast Orders') }}</span>
                <span class="text-xl font-black text-orange-600">{{ orders.total }}</span>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50 text-xs font-black text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <th class="py-4 px-6 text-[#FF6600]">{{ $t('Consignment ID') }}</th>
                            <th class="py-4 px-6">{{ $t('Tracking Code') }}</th>
                            <th class="py-4 px-6">{{ $t('Order ID') }}</th>
                            <th class="py-4 px-6">{{ $t('Customer') }}</th>
                            <th class="py-4 px-6 text-center">{{ $t('Status') }}</th>
                            <th class="py-4 px-6 text-right">{{ $t('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="order in orders.data" :key="order.id" class="hover:bg-slate-50 transition-colors">
                            <td class="py-5 px-6 font-mono text-xs font-bold text-slate-600">
                                {{ order.courier_consignment_id }}
                            </td>
                            <td class="py-5 px-6">
                                <span class="text-xs font-black text-[#FF6600] bg-orange-50 px-2.5 py-1 rounded-lg border border-orange-100 select-all tracking-wider">
                                    {{ order.courier_tracking_code }}
                                </span>
                            </td>
                            <td class="py-5 px-6">
                                <span class="text-xs font-black text-[#003366]">#{{ String(order.id).padStart(5, '0') }}</span>
                            </td>
                            <td class="py-5 px-6">
                                <div class="text-sm font-bold text-slate-900">{{ order.customer_name }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ order.customer_phone }}</div>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-full border" 
                                      :class="order.courier_status === 'success' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-blue-50 text-blue-600 border-blue-100'">
                                    {{ $t(order.courier_status) }}
                                </span>
                            </td>
                            <td class="py-5 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="syncStatus(order.id)" 
                                        class="p-2 text-slate-400 hover:text-[#FF6600] hover:bg-orange-50 rounded-lg transition-all"
                                        :title="$t('Sync Courier Status')"
                                    >
                                        <RefreshCw class="w-4 h-4" />
                                    </button>
                                    <button @click="viewResponse(order)" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="View API Response">
                                        <Code2 class="w-4 h-4" />
                                    </button>
                                    <Link :href="`/admin/orders/${order.id}`" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-[#003366] text-slate-600 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition-colors border border-slate-200">
                                        <Eye class="w-3.5 h-3.5" /> {{ $t('Details') }}
                                    </Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="orders.data.length === 0">
                            <td colspan="6" class="py-20 text-center">
                                <div class="flex flex-col items-center opacity-40">
                                    <Truck class="w-12 h-12 mb-3 text-slate-400" />
                                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">{{ $t('No courier orders yet') }}</p>
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
                        link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-500 border-slate-200 hover:border-indigo-600',
                        !link.url ? 'opacity-50 cursor-not-allowed bg-slate-100' : ''
                    ]"
                ></Link>
            </div>
        </div>

        <!-- API Response Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-8 max-w-2xl w-full shadow-2xl flex flex-col max-h-[80vh]">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">
                                <Code2 class="w-5 h-5" />
                            </div>
                            <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight italic">{{ $t('JSON') }} <span class="text-indigo-600">{{ $t('Response') }}</span></h3>
                        </div>
                        <button @click="showModal = false" class="p-2 hover:bg-slate-100 rounded-xl transition-colors"><XCircle class="w-5 h-5 text-slate-400" /></button>
                    </div>
                    
                    <div class="flex-1 overflow-auto bg-slate-900 rounded-2xl p-6 shadow-inner">
                        <pre class="text-[11px] font-mono text-green-400 leading-relaxed">{{ formatJson(selectedOrder?.courier_response) }}</pre>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button @click="showModal = false" class="px-8 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black uppercase text-[10px] tracking-widest rounded-xl transition-all">{{ $t('Close Viewer') }}</button>
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
import { Truck, Eye, Code2, X, RefreshCw, XCircle } from 'lucide-vue-next';

const props = defineProps({
    orders: Object
});

const showModal = ref(false);
const selectedOrder = ref(null);

const viewResponse = (order) => {
    selectedOrder.value = order;
    showModal.value = true;
};

const syncStatus = (id) => {
    router.post(route('admin.orders.sync-courier-status', id), {}, {
        preserveScroll: true
    });
};

const formatJson = (jsonStr) => {
    if (!jsonStr) return 'No response available';
    try {
        const obj = typeof jsonStr === 'string' ? JSON.parse(jsonStr) : jsonStr;
        return JSON.stringify(obj, null, 4);
    } catch (e) {
        return jsonStr;
    }
};
</script>
