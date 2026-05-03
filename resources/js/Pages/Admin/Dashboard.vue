<template>
    <AdminLayout>
        <div class="mb-10 flex items-center justify-between bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-black text-primary-900 tracking-tighter uppercase italic">Control <span class="text-slate-400">Terminal</span></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">Operational oversight of <span class="text-primary-900">Sadman Cart</span> ecosystems</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex flex-col text-right">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">System Status</span>
                    <span class="text-xs font-black text-green-500 uppercase tracking-widest flex items-center justify-end">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                        Synchronized
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
            <!-- Revenue Card -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary-900/5 rounded-full blur-2xl group-hover:bg-primary-900/10 transition-all"></div>
                <div class="flex flex-col relative z-10">
                    <div class="w-12 h-12 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-900 mb-6 shadow-inner">
                        <TrendingUp class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Total Revenue</span>
                    <span class="text-2xl font-black text-slate-900 italic">৳{{ parseFloat(stats.totalRevenue || 0).toLocaleString() }}</span>
                </div>
            </div>

            <!-- Profit Card (NEW) -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-500/5 rounded-full blur-2xl group-hover:bg-green-500/10 transition-all"></div>
                <div class="flex flex-col relative z-10">
                    <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 mb-6 shadow-inner">
                        <CircleDollarSign class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Total Profit</span>
                    <span class="text-2xl font-black text-green-600 italic">৳{{ parseFloat(stats.totalProfit || 0).toLocaleString() }}</span>
                </div>
            </div>

            <!-- Orders Card -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent-500/5 rounded-full blur-2xl group-hover:bg-accent-500/10 transition-all"></div>
                <div class="flex flex-col relative z-10">
                    <div class="w-12 h-12 bg-accent-50 rounded-2xl flex items-center justify-center text-accent-500 mb-6 shadow-inner">
                        <ShoppingCart class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Total Orders</span>
                    <span class="text-2xl font-black text-slate-900 italic">{{ stats.totalOrders }}</span>
                </div>
            </div>

            <!-- Products Card -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary-600/5 rounded-full blur-2xl group-hover:bg-primary-600/10 transition-all"></div>
                <div class="flex flex-col relative z-10">
                    <div class="w-12 h-12 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 mb-6 shadow-inner">
                        <Package class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Active Products</span>
                    <span class="text-2xl font-black text-slate-900 italic">{{ stats.totalProducts }}</span>
                </div>
            </div>

            <!-- Customers Card -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-highlight/5 rounded-full blur-2xl group-hover:bg-highlight/10 transition-all"></div>
                <div class="flex flex-col relative z-10">
                    <div class="w-12 h-12 bg-highlight/10 rounded-2xl flex items-center justify-center text-primary-900 mb-6 shadow-inner">
                        <Users class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Total Customers</span>
                    <span class="text-2xl font-black text-slate-900 italic">{{ stats.totalCustomers }}</span>
                </div>
            </div>
        </div>

        <!-- Monthly Profit Analytics (NEW) -->
        <div class="mb-12 bg-white rounded-[2.5rem] p-10 shadow-2xl shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-[#FF6600] rounded-full"></div>
                    <h3 class="font-black text-slate-900 uppercase tracking-tight italic">Profitability Stream <span class="text-slate-400 text-xs ml-2 font-bold">(Last 6 Months)</span></h3>
                </div>
            </div>

            <div class="flex items-end justify-between h-48 gap-4 px-4">
                <div v-for="month in monthlyStats" :key="month.month" class="flex-1 flex flex-col items-center group">
                    <div class="relative w-full flex flex-col items-center justify-end h-full">
                        <!-- Revenue Bar -->
                        <div 
                            :style="{ height: (month.revenue / maxRevenue * 100) + '%' }" 
                            class="w-8 bg-slate-100 rounded-t-lg transition-all duration-700 group-hover:bg-slate-200"
                        ></div>
                        <!-- Profit Bar -->
                        <div 
                            :style="{ height: (month.profit / maxRevenue * 100) + '%' }" 
                            class="w-8 bg-green-500 rounded-t-lg absolute bottom-0 transition-all duration-700 group-hover:bg-green-600 shadow-lg shadow-green-500/20"
                        >
                            <div class="opacity-0 group-hover:opacity-100 absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[9px] font-black px-2 py-1 rounded whitespace-nowrap transition-all">
                                ৳{{ month.profit.toLocaleString() }}
                            </div>
                        </div>
                    </div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-4">{{ month.month }}</span>
                </div>
            </div>
            <div class="mt-8 flex items-center justify-center space-x-8">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-slate-100 rounded"></div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Revenue</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded"></div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Net Profit</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-primary-900 rounded-full"></div>
                        <h3 class="font-black text-slate-900 uppercase tracking-tight italic">Recent Order Protocol</h3>
                    </div>
                    <Link href="/admin/orders" class="text-[10px] font-black text-primary-900 uppercase tracking-[0.2em] hover:underline">View All Records</Link>
                </div>
                <div class="overflow-x-auto">
                    <div v-if="!recentOrders || recentOrders.length === 0" class="py-24 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <ShoppingCart class="w-8 h-8 text-slate-200" />
                        </div>
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">No order entities recorded</p>
                    </div>
                    <table v-else class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="py-4 px-8">Identifier</th>
                                <th class="py-4 px-8">Customer Entity</th>
                                <th class="py-4 px-8">Value</th>
                                <th class="py-4 px-8">Operational Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-5 px-8 text-[10px] font-black text-primary-900 font-mono tracking-tighter">#ORD-{{ String(order.id).padStart(4, '0') }}</td>
                                <td class="py-5 px-8">
                                    <div class="text-xs font-black text-slate-900 uppercase tracking-tight italic">{{ order.customer_name }}</div>
                                    <div class="text-[9px] text-slate-400 font-medium">{{ order.customer_email }}</div>
                                </td>
                                <td class="py-5 px-8 text-xs font-black text-slate-900">৳{{ parseFloat(order.total_amount).toLocaleString() }}</td>
                                <td class="py-5 px-8">
                                    <span :class="getStatusClass(order.status)" class="text-[8px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full border">
                                        {{ order.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="space-y-8">
                <!-- Cart Intelligence Card -->
                <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl shadow-slate-200/50 border border-slate-100 flex flex-col items-center text-center group">
                    <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-6 text-slate-200 group-hover:bg-primary-900 group-hover:text-white transition-all duration-500 shadow-inner group-hover:shadow-xl group-hover:shadow-primary-900/20">
                        <ShoppingBag class="w-10 h-10" />
                    </div>
                    <h3 class="font-black text-slate-900 uppercase tracking-tight mb-2 italic text-xl">Cart Intelligence</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] leading-loose mb-8">Active shopping sessions and abandoned cart metrics</p>
                    
                    <div class="w-full space-y-4">
                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Active Sessions</span>
                            <span class="text-sm font-black text-primary-900 italic">24</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Units in Carts</span>
                            <span class="text-sm font-black text-accent-500 italic">156</span>
                        </div>
                    </div>
                </div>

                <!-- System Logs -->
                <div class="bg-primary-900 rounded-[2.5rem] p-10 shadow-2xl shadow-primary-900/20 text-white relative overflow-hidden group">
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="flex items-center space-x-3 mb-6 relative z-10">
                        <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                        <h3 class="text-[10px] font-black uppercase tracking-[0.3em] italic">System Telemetry</h3>
                    </div>
                    <div class="space-y-4 relative z-10">
                        <div class="flex items-start space-x-3">
                            <div class="w-1 h-1 bg-white/30 rounded-full mt-1.5"></div>
                            <p class="text-[9px] font-bold text-primary-200 uppercase tracking-widest leading-relaxed">Stock sync established with central repository</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-1 h-1 bg-white/30 rounded-full mt-1.5"></div>
                            <p class="text-[9px] font-bold text-primary-200 uppercase tracking-widest leading-relaxed">Mail protocols initialized for order updates</p>
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
