<template>
    <AdminLayout>
        <div class="mb-10 flex items-center justify-between bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-black text-[#003366] tracking-tight uppercase">{{ $t('Dashboard') }}</h1>
                <p class="text-sm font-bold text-slate-400 mt-1">{{ $t("Overview of your store's performance and recent activity") }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex flex-col text-right">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Store Status') }}</span>
                    <span class="text-xs font-black text-green-500 uppercase tracking-widest flex items-center justify-end mt-1">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                        {{ $t('Online') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
            <!-- Revenue Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex flex-col relative z-10">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6">
                        <TrendingUp class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $t('Total Revenue') }}</span>
                    <span class="text-2xl font-black text-slate-900">৳{{ parseFloat(stats.totalRevenue || 0).toLocaleString() }}</span>
                </div>
            </div>

            <!-- Profit Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex flex-col relative z-10">
                    <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 mb-6">
                        <CircleDollarSign class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $t('Net Profit') }}</span>
                    <span class="text-2xl font-black text-green-600">৳{{ parseFloat(stats.totalProfit || 0).toLocaleString() }}</span>
                </div>
            </div>

            <!-- Orders Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex flex-col relative z-10">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 mb-6">
                        <ShoppingCart class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $t('Total Orders') }}</span>
                    <span class="text-2xl font-black text-slate-900">{{ stats.totalOrders }}</span>
                </div>
            </div>

            <!-- Products Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex flex-col relative z-10">
                    <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6">
                        <Package class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $t('Products') }}</span>
                    <span class="text-2xl font-black text-slate-900">{{ stats.totalProducts }}</span>
                </div>
            </div>

            <!-- Customers Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex flex-col relative z-10">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 mb-6">
                        <Users class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $t('Customers') }}</span>
                    <span class="text-2xl font-black text-slate-900">{{ stats.totalCustomers }}</span>
                </div>
            </div>
        </div>

        <!-- Monthly Performance -->
        <div class="mb-12 bg-white rounded-[2rem] p-10 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-[#003366] rounded-full"></div>
                    <h3 class="font-black text-slate-900 uppercase tracking-wider text-sm">{{ $t('Monthly Performance') }} <span class="text-slate-400 text-xs ml-2 font-bold normal-case">{{ $t('(Last 6 Months)') }}</span></h3>
                </div>
            </div>

            <div class="flex items-end justify-between h-48 gap-4 px-4">
                <div v-for="month in monthlyStats" :key="month.month" class="flex-1 flex flex-col items-center group">
                    <div class="relative w-full flex flex-col items-center justify-end h-full">
                        <!-- Revenue Bar -->
                        <div 
                            :style="{ height: (month.revenue / maxRevenue * 100) + '%' }" 
                            class="w-10 bg-slate-100 rounded-t-lg transition-all duration-300 group-hover:bg-slate-200"
                        ></div>
                        <!-- Profit Bar -->
                        <div 
                            :style="{ height: (month.profit / maxRevenue * 100) + '%' }" 
                            class="w-10 bg-green-500 rounded-t-lg absolute bottom-0 transition-all duration-300 group-hover:bg-green-600 shadow-sm"
                        >
                            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] font-bold px-2 py-1 rounded whitespace-nowrap transition-all pointer-events-none">
                                Profit: ৳{{ month.profit.toLocaleString() }}
                            </div>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-4">{{ month.month }}</span>
                </div>
            </div>
            <div class="mt-8 flex items-center justify-center space-x-8">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-slate-100 rounded"></div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('Revenue') }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded"></div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('Net Profit') }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-[#003366] rounded-full"></div>
                        <h3 class="font-black text-slate-900 uppercase tracking-wider text-sm">{{ $t('Recent Orders') }}</h3>
                    </div>
                    <Link href="/admin/orders" class="text-xs font-bold text-[#003366] hover:underline">{{ $t('View All') }}</Link>
                </div>
                <div class="overflow-x-auto">
                    <div v-if="!recentOrders || recentOrders.length === 0" class="py-20 text-center">
                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                            <ShoppingCart class="w-5 h-5 text-slate-300" />
                        </div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $t('No orders yet') }}</p>
                    </div>
                    <table v-else class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-4 px-6">{{ $t('Order ID') }}</th>
                                <th class="py-4 px-6">{{ $t('Customer') }}</th>
                                <th class="py-4 px-6">{{ $t('Amount') }}</th>
                                <th class="py-4 px-6 text-right">{{ $t('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6 text-sm font-black text-[#003366]">#{{ String(order.id).padStart(5, '0') }}</td>
                                <td class="py-4 px-6">
                                    <div class="text-sm font-bold text-slate-900">{{ order.customer_name }}</div>
                                    <div class="text-[10px] text-slate-400 mt-1">{{ order.customer_email || order.customer_phone }}</div>
                                </td>
                                <td class="py-4 px-6 text-sm font-black text-slate-900">৳{{ parseFloat(order.total_amount).toLocaleString() }}</td>
                                <td class="py-4 px-6 text-right">
                                    <span :class="getStatusClass(order.status)" class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-lg border">
                                        {{ order.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Quick Stats Card -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mb-6 text-slate-400">
                        <ShoppingBag class="w-8 h-8" />
                    </div>
                    <h3 class="font-black text-slate-900 uppercase tracking-wider mb-2 text-lg">{{ $t('Store Activity') }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-loose mb-6">{{ $t('Current activity metrics') }}</p>
                    
                    <div class="w-full space-y-3">
                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('Active Sessions') }}</span>
                            <span class="text-sm font-black text-[#003366]">24</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('Products in Carts') }}</span>
                            <span class="text-sm font-black text-[#FF6600]">156</span>
                        </div>
                    </div>
                </div>

                <!-- System Messages -->
                <div class="bg-[#003366] rounded-[2rem] p-8 shadow-sm text-white relative overflow-hidden">
                    <div class="flex items-center space-x-3 mb-6 relative z-10">
                        <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                        <h3 class="text-sm font-black uppercase tracking-wider">{{ $t('System Logs') }}</h3>
                    </div>
                    <div class="space-y-4 relative z-10">
                        <div class="flex items-start space-x-3">
                            <div class="w-1.5 h-1.5 bg-blue-300 rounded-full mt-1.5"></div>
                            <p class="text-[10px] font-medium text-blue-100 leading-relaxed">{{ $t('Inventory synchronized successfully with database.') }}</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-1.5 h-1.5 bg-blue-300 rounded-full mt-1.5"></div>
                            <p class="text-[10px] font-medium text-blue-100 leading-relaxed">{{ $t('Email notifications are currently active.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { TrendingUp, ShoppingCart, Package, Users, ShoppingBag, CheckCircle, XCircle, CircleDollarSign } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
    monthlyStats: Array,
    recentOrders: Array
});

const maxRevenue = computed(() => {
    const max = Math.max(...props.monthlyStats.map(s => s.revenue), 1000);
    return max * 1.2; // Add 20% headroom
});

const getStatusClass = (status) => {
    switch (status) {
        case 'pending': return 'bg-yellow-50 text-yellow-600 border-yellow-100';
        case 'processing': return 'bg-blue-50 text-blue-600 border-blue-100';
        case 'completed': return 'bg-green-50 text-green-600 border-green-100';
        case 'cancelled': return 'bg-red-50 text-red-600 border-red-100';
        default: return 'bg-slate-50 text-slate-600 border-slate-100';
    }
};
</script>
