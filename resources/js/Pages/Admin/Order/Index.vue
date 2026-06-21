<template>
    <AdminLayout>
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase">অর্ডার <span class="text-[#003366]">ম্যানেজমেন্ট</span></h1>
                <p class="text-sm font-bold text-slate-400 mt-1">আপনার সকল কাস্টমার অর্ডার এখান থেকে ম্যানেজ করুন</p>
            </div>
            <div class="flex items-center space-x-4">
                <Link :href="route('admin.orders.create')" class="px-6 py-4 bg-[#003366] text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#FF6600] transition-all flex items-center gap-2 shadow-xl shadow-blue-900/10">
                    <Plus class="w-4 h-4" /> ম্যানুয়াল অর্ডার তৈরি করুন
                </Link>
                <div class="px-6 py-4 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-end">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">সিলেক্টেড</span>
                    <span class="text-xl font-black text-[#FF6600]">{{ selectedOrders.length }}</span>
                </div>
                <button 
                    v-if="selectedOrders.length > 0"
                    @click="initiateBulkCourierSend"
                    class="px-6 py-4 bg-orange-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all flex items-center gap-2 shadow-xl shadow-orange-500/20"
                >
                    <Send class="w-4 h-4" /> এক সাথে স্টিডফাস্টে পাঠান
                </button>
                <div class="px-6 py-4 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-end">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">মোট অর্ডার</span>
                    <span class="text-xl font-black text-[#003366]">{{ orders.total }}</span>
                </div>
            </div>
        </div>

        <!-- Overdue Pending Orders Alert -->
        <div v-if="overduePendingCount > 0" class="mb-8 bg-red-50 border border-red-200 text-red-800 p-5 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-3">
                <AlertTriangle class="w-5 h-5 text-red-600 shrink-0" />
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-red-900">{{ $t('Urgent Pending Action Required') }}</h4>
                    <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest mt-0.5">{{ overduePendingCount }} {{ $t('items pending more than 2 days') }}</p>
                </div>
            </div>
            <Link :href="route('admin.orders.index', { status: 'pending' })" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                {{ $t('Review Pending') }}
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 bg-slate-50 p-6 rounded-2xl border border-slate-200">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ক্যাটাগরি দিয়ে ফিল্টার করুন</label>
                    <select v-model="filterForm.category_id" @change="applyFilters" class="w-full px-4 py-2.5 rounded-xl bg-white border-slate-200 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#003366]/10 transition-all">
                        <option value="all">সব ক্যাটাগরি</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">প্রোডাক্ট দিয়ে ফিল্টার করুন</label>
                    <select v-model="filterForm.product_id" @change="applyFilters" class="w-full px-4 py-2.5 rounded-xl bg-white border-slate-200 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#003366]/10 transition-all">
                        <option value="all">সব প্রোডাক্ট</option>
                        <option v-for="prod in products" :key="prod.id" :value="prod.id">{{ prod.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">কম্বো দিয়ে ফিল্টার করুন</label>
                    <select v-model="filterForm.combo_id" @change="applyFilters" class="w-full px-4 py-2.5 rounded-xl bg-white border-slate-200 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#003366]/10 transition-all">
                        <option value="all">সব কম্বো</option>
                        <option v-for="combo in combos" :key="combo.id" :value="combo.id">{{ combo.name }}</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button @click="resetFilters" class="px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        ফিল্টার রিসেট করুন
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
                            <th class="py-4 px-6 w-10 text-center">
                                <input type="checkbox" :checked="isAllSelected" @change="toggleSelectAll" class="w-4 h-4 rounded border-slate-300 text-[#003366] focus:ring-[#003366]">
                            </th>
                            <th class="py-4 px-6">অর্ডার আইডি</th>
                            <th class="py-4 px-6">কাস্টমার তথ্য</th>
                            <th class="py-4 px-6 w-64">আইটেম (নাম ও পরিমাণ)</th>
                            <th class="py-4 px-6">মোট টাকা</th>
                            <th class="py-4 px-6 text-center">স্ট্যাটাস</th>
                            <th class="py-4 px-6 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="order in orders.data" :key="order.id" 
                            class="transition-colors"
                            :class="[
                                isOrderOverdue(order) ? 'bg-red-50/30 hover:bg-red-50/60' : 'hover:bg-slate-50',
                                selectedOrders.includes(order.id) ? 'bg-[#003366]/5' : ''
                            ]"
                        >
                            <td class="py-5 px-6 text-center">
                                <input type="checkbox" :checked="selectedOrders.includes(order.id)" @change="toggleOrderSelection(order.id)" class="w-4 h-4 rounded border-slate-300 text-[#003366] focus:ring-[#003366]">
                            </td>

                            <!-- Order ID -->
                            <td class="py-5 px-6 relative">
                                <div class="absolute left-0 top-0 bottom-0 w-1.5"
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
                                        <span v-if="isOrderOverdue(order)" class="inline-flex items-center gap-0.5 text-[8px] font-black uppercase bg-red-600 text-white px-1.5 py-0.5 rounded tracking-widest">
                                            <AlertTriangle class="w-2.5 h-2.5" /> {{ $t('Overdue') }}
                                        </span>
                                    </div>
                                    <div class="text-[10px] font-bold text-slate-400 mt-1">
                                        {{ new Date(order.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                                    </div>
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        <button @click="toggleActive(order.id)" :class="order.is_active ? 'bg-green-50 text-green-600 border-green-100' : 'bg-slate-50 text-slate-400 border-slate-100'" class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full border">
                                            {{ order.is_active ? 'সক্রিয়' : 'আর্কাইভ' }}
                                        </button>
                                        <span v-if="order.courier_tracking_code" class="bg-orange-50 text-orange-600 border border-orange-100 text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full flex items-center gap-1">
                                            <Send class="w-2 h-2" /> স্টিডফাস্ট
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Customer Info -->
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
                                    <div class="pl-4 text-[#003366] font-bold">{{ order.district }}, {{ order.upazila }}</div>
                                </div>
                            </td>

                            <!-- Items -->
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
                                            <option value="Inside Dhaka">ঢাকার ভিতরে</option>
                                            <option value="Outside Dhaka">ঢাকার বাইরে</option>
                                        </select>
                                    </div>
                                    <ul class="space-y-2 mt-3">
                                        <li v-for="item in order.items" :key="item.id" class="flex items-center gap-2 border-b border-slate-50 pb-2 last:border-0">
                                            <div class="w-8 h-8 rounded bg-white flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-100 p-0.5">
                                                <img v-if="item.product && item.product.image" :src="item.product.image.startsWith('http') ? item.product.image : `/storage/${item.product.image}`" class="w-full h-full object-contain" />
                                                <img v-else-if="item.combo && item.combo.image" :src="item.combo.image.startsWith('http') ? item.combo.image : `/storage/${item.combo.image}`" class="w-full h-full object-contain" />
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

                            <!-- Amount -->
                            <td class="py-5 px-6">
                                <div class="space-y-1.5 bg-slate-50 p-3 rounded-xl border border-slate-100 max-w-[180px]">
                                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                        <span>আইটেম মূল্য:</span>
                                        <span>৳{{ (order.total_amount - order.delivery_charge).toLocaleString() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                        <span>ডেলিভারি:</span>
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
                                        <span class="text-xs font-black text-slate-900">মোট পেয়েবল:</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-black text-[#003366]">৳{{ parseFloat(order.total_amount).toLocaleString() }}</span>
                                            <button @click="openEditModal(order)" class="p-1 hover:bg-[#003366] hover:text-white rounded transition-colors" title="এডিট করুন">
                                                <Edit2 class="w-3 h-3 text-slate-400 group-hover:text-white" />
                                            </button>
                                        </div>
                                    </div>
                                    <div class="text-[8px] font-black text-white bg-[#003366] rounded px-1.5 py-0.5 inline-block uppercase text-center w-full tracking-widest mt-1">
                                        {{ order.payment_method }}
                                    </div>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-5 px-6 text-center">
                                <!-- If courier tracking exists and status is not completed/cancelled → show auto-complete pill -->
                                <div v-if="order.courier_tracking_code && order.status !== 'completed' && order.status !== 'cancelled'" class="flex flex-col items-center gap-2">
                                    <span class="text-[9px] font-black text-orange-600 bg-orange-50 border border-orange-100 px-2 py-1 rounded-lg uppercase tracking-widest">Steadfast Sent</span>
                                    <button @click="directComplete(order)" class="text-[9px] font-black text-white bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded-lg uppercase tracking-widest transition-all">
                                        ✓ Mark Complete
                                    </button>
                                </div>
                                <!-- Normal status select — always enabled (overdue orders can still be updated) -->
                                <div v-else-if="order.status !== 'completed' && order.status !== 'cancelled'" class="relative inline-block w-full">
                                    <select 
                                        @change="initiateStatusUpdate(order, $event.target.value, $event)" 
                                        :value="order.status"
                                        class="text-xs font-black uppercase pl-3 pr-8 py-2.5 rounded-xl border outline-none cursor-pointer w-full text-center transition-all appearance-none" 
                                        :class="getStatusClass(order.status)"
                                    >
                                        <option value="pending">পেন্ডিং</option>
                                        <option value="processing">প্রসেসিং</option>
                                        <option value="completed">ডেলিভারি</option>
                                        <option value="cancelled">বাতিল</option>
                                    </select>
                                    <ChevronDown class="absolute right-2.5 top-3.5 w-3.5 h-3.5 pointer-events-none opacity-60" />
                                </div>
                                <!-- Finalized state badge -->
                                <span v-else class="flex flex-col items-center gap-2">
                                    <span class="inline-block text-xs font-black uppercase px-3 py-2 rounded-xl border" :class="getStatusClass(order.status)">
                                        {{ order.status === 'completed' ? 'ডেলিভারি' : (order.status === 'cancelled' ? 'বাতিল' : order.status) }}
                                    </span>
                                    <button 
                                        v-if="order.status === 'cancelled'"
                                        @click="initiateStatusUpdate(order, 'pending', $event)"
                                        class="text-[9px] font-black text-[#003366] uppercase tracking-widest hover:underline"
                                    >
                                        পেন্ডিং এ পাঠান
                                    </button>
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-5 px-6 text-right">
                                <div class="flex flex-col gap-2">
                                    <button 
                                        v-if="!order.courier_tracking_code && order.status !== 'cancelled' && order.status !== 'completed'"
                                        @click="initiateCourierSend(order)"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white hover:bg-orange-600 text-orange-600 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition-all border border-orange-200 hover:border-orange-600 shadow-sm"
                                    >
                                        <Send class="w-3.5 h-3.5" /> কুরিয়ার
                                    </button>
                                    <div v-if="order.courier_tracking_code" class="flex flex-col items-end">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">স্টিডফাস্ট</span>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] font-bold text-[#FF6600] bg-orange-50 px-2 py-1 rounded-lg border border-orange-100">{{ order.courier_tracking_code }}</span>
                                            <button @click="syncStatus(order.id)" class="p-1.5 bg-slate-100 hover:bg-[#003366] text-slate-400 hover:text-white rounded-lg transition-all" title="কুরিয়ার স্ট্যাটাস সিঙ্ক করুন">
                                                <RefreshCw class="w-3.5 h-3.5" />
                                            </button>
                                            <button v-if="currentStatus === 'cancelled' || order.status === 'cancelled'" @click="confirmDeleteOrder(order.id)" class="p-1.5 bg-slate-100 hover:bg-red-600 text-slate-400 hover:text-white rounded-lg transition-all" title="অর্ডার ডিলিট করুন">
                                                <Trash2 class="w-3.5 h-3.5" />
                                            </button>
                                        </div>
                                    </div>
                                    <div v-if="!order.courier_tracking_code && currentStatus === 'cancelled'">
                                        <button @click="confirmDeleteOrder(order.id)" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition-all border border-red-100 hover:border-red-600 shadow-sm" title="অর্ডার ডিলিট করুন">
                                            <Trash2 class="w-3.5 h-3.5" /> ডিলিট
                                        </button>
                                    </div>
                                    <Link :href="`/admin/orders/${order.id}`" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-100 hover:bg-[#003366] text-slate-600 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition-colors border border-slate-200 hover:border-[#003366]">
                                        <Eye class="w-3.5 h-3.5" /> দেখুন
                                    </Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="orders.data.length === 0">
                            <td colspan="7" class="py-20 text-center">
                                <div class="flex flex-col items-center opacity-40">
                                    <ShoppingBag class="w-12 h-12 mb-3 text-slate-400" />
                                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">কোনো অর্ডার পাওয়া যায়নি</p>
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

        <!-- ====== MODALS ====== -->

        <!-- Cancel Confirmation Modal -->
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showCancelModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-14 h-14 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <XCircle class="w-7 h-7" />
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2 uppercase tracking-tight">অর্ডার বাতিল করবেন?</h3>
                    <p class="text-sm text-slate-500 mb-6">অর্ডার <span class="font-black text-slate-700">#{{ String(orderToUpdate?.id ?? 0).padStart(5, '0') }}</span> বাতিল করা হবে। এটি আর ফিরিয়ে আনা সম্ভব নয়।</p>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="showCancelModal = false" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">না, রাখুন</button>
                        <button @click="confirmStatusUpdate" class="px-4 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">হ্যাঁ, বাতিল করুন</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Complete Order Modal (no courier) -->
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showCompleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-14 h-14 bg-orange-50 text-[#FF6600] rounded-full flex items-center justify-center mx-auto mb-4">
                        <Truck class="w-7 h-7" />
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2 uppercase tracking-tight">অর্ডার সম্পন্ন করুন</h3>
                    <p class="text-sm text-slate-500 mb-6">এই অর্ডারটি কি <span class="font-black text-[#FF6600]">স্টিডফাস্টে</span> পাঠাতে চান?</p>
                    <div class="flex flex-col gap-2">
                        <button @click="completeWithCourier(true)" class="w-full px-6 py-3 rounded-xl bg-[#003366] text-white font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all">
                            সম্পন্ন করুন + স্টিডফাস্টে পাঠান
                        </button>
                        <button @click="completeWithCourier(false)" class="w-full px-6 py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50 transition-all">
                            শুধু সম্পন্ন করুন
                        </button>
                        <button @click="showCompleteModal = false" class="w-full px-4 py-2 text-slate-400 font-bold text-xs hover:text-slate-600 transition-all">বাতিল</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Delete Order Modal -->
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showDeleteOrderModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-14 h-14 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <Trash2 class="w-7 h-7" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">অর্ডার ডিলিট করবেন?</h3>
                    <p class="text-sm text-slate-500 mb-6">এই কাজটি আর ফিরিয়ে আনা সম্ভব নয়।</p>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="showDeleteOrderModal = false" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">বাতিল</button>
                        <button @click="deleteOrder" class="px-4 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">ডিলিট করুন</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Confirm Location Update Modal -->
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showLocationModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-14 h-14 bg-orange-50 text-[#FF6600] rounded-full flex items-center justify-center mx-auto mb-4">
                        <Truck class="w-7 h-7" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">গন্তব্য পরিবর্তন করবেন?</h3>
                    <p class="text-sm text-slate-500 mb-6">
                        ডেলিভারি এরিয়া পরিবর্তন করলে অটোমেটিকভাবে ডেলিভারি চার্জ আপডেট হয়ে যাবে।
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="showLocationModal = false" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">বাতিল</button>
                        <button @click="confirmLocationUpdate" class="px-4 py-3 rounded-xl bg-[#FF6600] text-white font-bold hover:bg-orange-600 transition-all shadow-lg">আপডেট করুন</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Send to Courier Modal -->
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showCourierModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-14 h-14 bg-orange-50 text-[#FF6600] rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <Send class="w-7 h-7" />
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2 uppercase tracking-tight">স্টিডফাস্টে পাঠাবেন?</h3>
                    <p class="text-sm text-slate-500 mb-6">
                        অর্ডার <span class="font-black text-[#FF6600]">#{{ String(orderToCourier?.id ?? 0).padStart(5, '0') }}</span> স্টিডফাস্ট কুরিয়ারের মাধ্যমে পাঠানো হবে।
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="showCourierModal = false" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">বাতিল</button>
                        <button @click="confirmCourierSend" class="px-4 py-3 rounded-xl bg-orange-600 text-white font-bold hover:bg-black transition-all shadow-xl shadow-orange-500/20">এখনই পাঠান</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Bulk Send Modal -->
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showBulkCourierModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-14 h-14 bg-orange-600 text-white rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <Send class="w-7 h-7" />
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2 uppercase tracking-tight">একসাথে পাঠাবেন?</h3>
                    <p class="text-sm text-slate-500 mb-6">
                        <span class="font-black text-orange-600">{{ selectedOrders.length }} টি অর্ডার</span> স্টিডফাস্টে পাঠানো হবে। এটি আর ফিরিয়ে আনা সম্ভব নয়।
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="showBulkCourierModal = false" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">বাতিল</button>
                        <button @click="confirmBulkCourierSend" class="px-4 py-3 rounded-xl bg-black text-white font-bold hover:bg-orange-600 transition-all shadow-xl shadow-orange-500/20">কনফার্ম করুন</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Success Modal -->
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showSuccessModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-14 h-14 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <ShieldCheck class="w-7 h-7" />
                    </div>
                    <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight mb-2">সম্পন্ন হয়েছে!</h3>
                    <p class="text-sm font-bold text-slate-500 mb-6">{{ successModalMessage }}</p>
                    <button @click="showSuccessModal = false" class="w-full py-3 bg-[#003366] hover:bg-[#FF6600] text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all">ঠিক আছে</button>
                </div>
            </div>
        </transition>

        <!-- Edit Order Modal -->
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-lg w-full shadow-2xl relative overflow-hidden">
                    <button @click="showEditModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                        <XCircle class="w-6 h-6" />
                    </button>
                    
                    <div class="flex items-center gap-3 mb-8">
                        <div class="p-2.5 bg-blue-50 rounded-xl text-[#003366]">
                            <Edit2 class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-900 leading-none uppercase">অর্ডার এডিট করুন</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">অর্ডার #{{ String(orderToEdit?.id ?? 0).padStart(5, '0') }}</p>
                        </div>
                    </div>

                    <form @submit.prevent="submitOrderEdit" class="space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">কাস্টমার নাম</label>
                                <input v-model="editForm.customer_name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">মোবাইল নাম্বার</label>
                                <input v-model="editForm.customer_phone" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">শিপিং ঠিকানা</label>
                            <textarea v-model="editForm.shipping_address" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all"></textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">মোট অ্যামাউন্ট</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400">৳</span>
                                <input v-model.number="editForm.total_amount" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-4 py-3 text-xs font-black focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all text-[#003366]">
                            </div>
                            <p class="text-[9px] font-bold text-orange-500 mt-1 uppercase tracking-widest leading-tight">* এটি ডেলিভারি চার্জ সহ সর্বমোট অ্যামাউন্ট।</p>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showEditModal = false" class="flex-1 py-4 rounded-xl border border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">বাতিল</button>
                            <button type="submit" :disabled="editForm.processing" class="flex-[2] py-4 bg-[#003366] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black shadow-xl shadow-blue-900/10 transition-all disabled:opacity-50">
                                {{ editForm.processing ? 'আপডেট হচ্ছে...' : 'অর্ডার আপডেট করুন' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Eye, CheckCircle, XCircle, ShoppingBag, Phone, MapPin, Truck, AlertTriangle, Package, Plus, ChevronDown, Send, ShieldCheck, RefreshCw, Trash2, Edit2 } from 'lucide-vue-next';

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
    const diffMs = Math.abs(new Date() - new Date(order.created_at));
    return diffMs / (1000 * 60 * 60 * 24) > 2;
};

const filterForm = reactive({
    category_id: props.currentCategoryId || 'all',
    product_id: props.currentProductId || 'all',
    combo_id: props.currentComboId || 'all'
});

const tabs = [
    { label: 'পেন্ডিং', value: 'pending' },
    { label: 'প্রসেসিং', value: 'processing' },
    { label: 'ডেলিভারি', value: 'completed' },
    { label: 'বাতিল', value: 'cancelled' },
    { label: 'সব অর্ডার', value: 'all' },
];

const getFilterParams = (overrides = {}) => {
    const params = {
        status: props.currentStatus === 'all' ? null : props.currentStatus,
        category_id: filterForm.category_id === 'all' ? null : filterForm.category_id,
        product_id: filterForm.product_id === 'all' ? null : filterForm.product_id,
        combo_id: filterForm.combo_id === 'all' ? null : filterForm.combo_id,
        ...overrides
    };
    Object.keys(params).forEach(key => { if (params[key] === 'all' || params[key] === null) delete params[key]; });
    return params;
};

const applyFilters = () => router.get(route('admin.orders.index'), getFilterParams(), { preserveScroll: true, preserveState: true });
const resetFilters = () => {
    filterForm.category_id = 'all';
    filterForm.product_id = 'all';
    filterForm.combo_id = 'all';
    router.get(route('admin.orders.index'));
};

const toggleActive = (id) => router.put(`/admin/orders/${id}/toggle-active`, {}, { preserveScroll: true });

// Modal state
const showCancelModal = ref(false);
const showCompleteModal = ref(false);
const showLocationModal = ref(false);
const showCourierModal = ref(false);
const showBulkCourierModal = ref(false);
const showDeleteOrderModal = ref(false);
const showSuccessModal = ref(false);
const successModalMessage = ref('');

const orderToUpdate = ref(null);
const orderToCourier = ref(null);
const orderToEdit = ref(null);
const orderToDelete = ref(null);
const newStatus = ref('');
const newLocation = ref('');

const showEditModal = ref(false);
const editForm = useForm({
    customer_name: '',
    customer_phone: '',
    shipping_address: '',
    total_amount: 0
});

const openEditModal = (order) => {
    orderToEdit.value = order;
    editForm.customer_name = order.customer_name;
    editForm.customer_phone = order.customer_phone;
    editForm.shipping_address = order.shipping_address;
    editForm.total_amount = order.total_amount;
    showEditModal.value = true;
};

const submitOrderEdit = () => {
    editForm.put(`/admin/orders/${orderToEdit.value.id}?status=${props.currentStatus}`, {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
        }
    });
};

// Selection
const selectedOrders = ref([]);
const isAllSelected = computed(() => props.orders.data.length > 0 && selectedOrders.value.length === props.orders.data.length);
const toggleSelectAll = () => {
    selectedOrders.value = isAllSelected.value ? [] : props.orders.data.map(o => o.id);
};
const toggleOrderSelection = (id) => {
    const idx = selectedOrders.value.indexOf(id);
    if (idx > -1) selectedOrders.value.splice(idx, 1);
    else selectedOrders.value.push(id);
};

// Status update logic:
// - processing → direct update, no modal
// - cancelled → cancel confirmation modal
// - completed (no courier) → complete modal with Steadfast option
// - completed (has courier) → directComplete used instead (button in table)
const initiateStatusUpdate = (order, status, event) => {
    event.target.value = order.status; // revert visually until confirmed
    orderToUpdate.value = order;
    newStatus.value = status;

    if (status === 'cancelled') {
        showCancelModal.value = true;
    } else if (status === 'completed') {
        // No courier yet → show complete modal
        showCompleteModal.value = true;
    } else {
        // processing or pending → update directly, no modal
        submitStatusUpdate(false);
    }
};

// Called when order has courier tracking code and user clicks "Mark Complete"
const directComplete = (order) => {
    orderToUpdate.value = order;
    newStatus.value = 'completed';
    submitStatusUpdate(false);
};

const confirmStatusUpdate = () => {
    showCancelModal.value = false;
    submitStatusUpdate(false);
};

const completeWithCourier = (sendToCourier) => {
    showCompleteModal.value = false;
    submitStatusUpdate(sendToCourier);
};

const submitStatusUpdate = (sendToCourier) => {
    router.put(`/admin/orders/${orderToUpdate.value.id}?status=${props.currentStatus}`, {
        status: newStatus.value,
        send_to_courier: sendToCourier
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showCancelModal.value = false;
            showCompleteModal.value = false;
        }
    });
};

// Courier
const initiateCourierSend = (order) => { orderToCourier.value = order; showCourierModal.value = true; };
const confirmCourierSend = () => {
    router.post(`/admin/orders/${orderToCourier.value.id}/send-to-courier`, {}, {
        preserveScroll: true,
        onSuccess: (page) => {
            showCourierModal.value = false;
            successModalMessage.value = page.props.flash.success || 'Order sent to Steadfast successfully.';
            showSuccessModal.value = true;
        }
    });
};

const initiateBulkCourierSend = () => { if (selectedOrders.value.length > 0) showBulkCourierModal.value = true; };
const confirmBulkCourierSend = () => {
    router.post(route('admin.orders.bulk-send'), { order_ids: selectedOrders.value }, {
        preserveScroll: true,
        onSuccess: (page) => {
            showBulkCourierModal.value = false;
            selectedOrders.value = [];
            successModalMessage.value = page.props.flash.success || 'Orders sent to Steadfast successfully.';
            showSuccessModal.value = true;
        }
    });
};

// Delete
const confirmDeleteOrder = (id) => { orderToDelete.value = id; showDeleteOrderModal.value = true; };
const deleteOrder = () => {
    router.delete(`/admin/orders/${orderToDelete.value}?status=${props.currentStatus}`, {
        onSuccess: () => { showDeleteOrderModal.value = false; }
    });
};

// Location
const initiateLocationUpdate = (order, location, event) => {
    event.target.value = order.delivery_location;
    orderToUpdate.value = order;
    newLocation.value = location;
    showLocationModal.value = true;
};
const confirmLocationUpdate = () => {
    router.put(`/admin/orders/${orderToUpdate.value.id}?status=${props.currentStatus}`, { delivery_location: newLocation.value }, {
        preserveScroll: true,
        onSuccess: () => { showLocationModal.value = false; }
    });
};

const updateDeliveryCharge = (order, charge) => {
    if (parseFloat(charge) === parseFloat(order.delivery_charge)) return;
    router.put(`/admin/orders/${order.id}?status=${props.currentStatus}`, { delivery_charge: charge }, { preserveScroll: true });
};

const syncStatus = (id) => {
    router.post(route('admin.orders.sync-courier-status', id), {}, {
        preserveScroll: true,
        onSuccess: (page) => {
            if (page.props.flash.success) { successModalMessage.value = page.props.flash.success; showSuccessModal.value = true; }
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