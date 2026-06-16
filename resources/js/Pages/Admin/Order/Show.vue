<template>
    <AdminLayout>
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase">{{ $t('Order Details') }}</h1>
                <p class="text-sm font-bold text-slate-500 mt-1">{{ $t('Order ID') }}: <span class="font-mono text-[#003366]">#{{ String(order.id).padStart(5, '0') }}</span></p>
            </div>
            <Link href="/admin/orders" class="flex items-center text-sm font-bold text-slate-500 hover:text-[#003366] transition-colors bg-white px-4 py-2 rounded-lg border shadow-sm">
                <ArrowLeft class="w-4 h-4 mr-2" /> {{ $t('Back to Orders') }}
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
                        <h2 class="font-black text-[#003366] uppercase tracking-wider text-sm">{{ $t('Ordered Items') }}</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    <th class="py-4 px-6">{{ $t('Product & Specification') }}</th>
                                    <th class="py-4 px-6 text-center">{{ $t('Quantity') }}</th>
                                    <th class="py-4 px-6 text-right">{{ $t('Unit Price') }}</th>
                                    <th class="py-4 px-6 text-right">{{ $t('Line Total') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-for="item in order.items" :key="item.id" class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="py-5 px-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden flex-shrink-0">
                                                <img v-if="item.product?.image || item.combo?.image" :src="item.product?.image || item.combo?.image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                                <div v-else class="w-full h-full flex items-center justify-center text-slate-200"><Package class="w-6 h-6" /></div>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1" v-if="item.combo_id">
                                                    <span class="text-[7px] font-black bg-[#003366] text-white px-1.5 py-0.5 rounded-full uppercase tracking-widest">Combo</span>
                                                </div>
                                                <div class="text-sm font-black text-slate-900 group-hover:text-[#003366] transition-colors italic uppercase tracking-tight">{{ item.product_name }}</div>
                                                <div v-if="item.combo_id && item.combo?.products" class="mt-1 flex flex-wrap gap-2">
                                                    <span v-for="p in item.combo.products" :key="p.id" class="text-[8px] font-black text-slate-500 bg-slate-100 px-2 py-0.5 rounded uppercase tracking-widest">
                                                        {{ p.name }}
                                                    </span>
                                                </div>
                                                <div class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">SKU: {{ item.product?.sku || 'BUNDLE-' + item.combo_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-black text-[#003366]">x{{ item.quantity }}</span>
                                    </td>
                                    <td class="py-5 px-6 text-right text-sm font-bold text-slate-600 italic">৳{{ parseFloat(item.price).toLocaleString() }}</td>
                                    <td class="py-5 px-6 text-right text-sm font-black text-[#003366]">৳{{ parseFloat(item.price * item.quantity).toLocaleString() }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-slate-50/50 text-slate-900 border-t border-slate-200">
                                <tr class="border-b border-slate-100">
                                    <td colspan="3" class="py-4 px-6 text-[10px] font-black uppercase tracking-widest text-right text-slate-400">{{ $t('Subtotal:') }}</td>
                                    <td class="py-4 px-6 text-right text-sm font-bold">৳{{ (order.total_amount - order.delivery_charge).toLocaleString() }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td colspan="3" class="py-4 px-6 text-[10px] font-black uppercase tracking-widest text-right text-slate-400">{{ $t('Delivery Charge') }} ({{ $t(order.delivery_location) }}):</td>
                                    <td class="py-4 px-6 text-right text-sm font-bold text-[#FF6600]">৳{{ parseFloat(order.delivery_charge).toLocaleString() }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="py-8 px-6 text-sm font-black uppercase tracking-widest text-right italic">{{ $t('Grand Total:') }}</td>
                                    <td class="py-8 px-6 text-right text-2xl font-black text-[#003366]">৳{{ parseFloat(order.total_amount).toLocaleString() }}</td>
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
                            <h2 class="font-black text-[#003366] uppercase tracking-wider text-sm">{{ $t('Shipping Address') }}</h2>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase">{{ $t('Destination:') }}</span>
                            <select 
                                @change="initiateLocationUpdate($event.target.value, $event)" 
                                :value="order.delivery_location" 
                                :disabled="order.status === 'completed' || order.status === 'cancelled'"
                                class="text-[10px] font-black uppercase px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 outline-none cursor-pointer hover:border-[#FF6600] transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <option value="Inside Dhaka">{{ $t('Inside Dhaka') }}</option>
                                <option value="Outside Dhaka">{{ $t('Outside Dhaka') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $t('District') }}</span>
                            <span class="text-sm font-bold text-slate-800">{{ order.district || 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $t('Upazila') }}</span>
                            <span class="text-sm font-bold text-slate-800">{{ order.upazila || 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $t('Village / Area') }}</span>
                            <span class="text-sm font-bold text-slate-800">{{ order.village || 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $t('Full Detailed Address') }}</span>
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
                        <h2 class="font-black text-[#003366] uppercase tracking-wider text-sm">{{ $t('Order Status') }}</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">{{ $t('Payment Method') }}</label>
                            <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-2">
                                <Banknote class="w-4 h-4 text-slate-500" />
                                <span class="text-sm font-bold text-slate-800 uppercase">{{ $t(order.payment_method) }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-4">{{ $t('Current Status') }}</label>
                            <select 
                                @change="initiateStatusUpdate($event.target.value, $event)" 
                                :value="order.status" 
                                :disabled="order.status === 'completed' || order.status === 'cancelled'"
                                class="w-full px-4 py-3 rounded-xl border outline-none font-bold text-sm uppercase transition-all cursor-pointer shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" 
                                :class="getStatusClass(order.status)"
                            >
                                <option value="pending" :disabled="!!order.courier_tracking_code">{{ $t('Pending') }}</option>
                                <option value="processing" :disabled="!!order.courier_tracking_code">{{ $t('Processing') }}</option>
                                <option value="completed">{{ $t('Completed') }}</option>
                                <option value="cancelled" :disabled="!!order.courier_tracking_code">{{ $t('Cancelled') }}</option>
                            </select>
                            <div class="mt-4" v-if="order.status !== 'completed' && order.status !== 'cancelled'">
                                <!-- Courier Selection/Action -->
                                <div v-if="!order.courier_tracking_code">
                                    <button 
                                        @click="order.status !== 'cancelled' ? showCourierModal = true : null"
                                        :disabled="order.status === 'cancelled'"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white hover:bg-orange-600 text-orange-600 hover:text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all border border-orange-200 hover:border-orange-600 shadow-sm mb-3 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-amber-500 disabled:hover:border-amber-200"
                                    >
                                        <Send class="w-4 h-4" /> {{ $t('Send to Steadfast') }}
                                    </button>
                                    
                                    <!-- Debug info if a previous attempt failed -->
                                    <div v-if="order.courier_response" class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Last Attempt Info') }}</span>
                                            <button @click="showResponseModal = true" class="text-[9px] font-black text-indigo-600 hover:underline uppercase tracking-widest flex items-center gap-1">
                                                <Code2 class="w-2.5 h-2.5" /> {{ $t('View Response') }}
                                            </button>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-600">{{ $t('The last attempt failed. Click "View Response" to see the reason.') }}</p>
                                    </div>
                                </div>

                                <!-- Success State -->
                                <div v-else class="bg-orange-50 p-6 rounded-2xl border-2 border-orange-100 shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-orange-600 text-white rounded-lg flex items-center justify-center">
                                                <Truck class="w-4 h-4" />
                                            </div>
                                            <span class="text-[11px] font-black text-orange-900 uppercase tracking-widest">{{ $t('Steadfast Courier Integration') }}</span>
                                        </div>
                                        <span class="text-[10px] font-black text-orange-600 uppercase tracking-widest bg-white px-3 py-1 rounded-full border border-orange-200 shadow-sm">{{ $t(order.courier_status) }}</span>
                                    </div>
                                    <div class="bg-white p-4 rounded-xl border border-orange-50 mb-4">
                                        <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $t('Consignment tracking code') }}</span>
                                        <div class="flex items-center justify-between">
                                            <span class="text-lg font-black text-[#003366] font-mono select-all tracking-tighter italic">{{ order.courier_tracking_code }}</span>
                                            <button @click="showResponseModal = true" class="flex items-center gap-1.5 text-[10px] font-black text-[#FF6600] hover:text-orange-700 transition-colors bg-orange-50 px-2 py-1 rounded-lg">
                                                <Code2 class="w-3 h-3" /> {{ $t('View Data') }}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="bg-white/50 p-3 rounded-xl border border-orange-50">
                                            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">{{ $t('Response status') }}</span>
                                            <span class="text-[11px] font-black text-slate-700 uppercase italic">{{ order.courier_status === 'success' ? $t('Placed') : $t(order.courier_status) }}</span>
                                        </div>
                                        <div class="bg-white/50 p-3 rounded-xl border border-orange-50">
                                            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">{{ $t('Consignment ID') }}</span>
                                            <span class="text-[11px] font-black text-slate-700 font-mono">{{ order.courier_consignment_id || 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p v-if="order.status !== 'completed' && order.status !== 'cancelled'" class="text-[10px] text-slate-400 font-medium mt-2 italic leading-tight">{{ $t('Changing the status will notify the customer via email and may affect inventory census.') }}</p>
                            <p v-else class="text-[10px] text-green-600 font-black mt-2 uppercase tracking-widest">{{ $t('This order is in a final state and cannot be modified.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200 space-y-6">
                    <div class="flex items-center space-x-3 border-b border-slate-100 pb-4">
                        <User class="w-5 h-5 text-[#FFCC00]" />
                        <h2 class="font-black text-[#003366] uppercase tracking-wider text-sm">{{ $t('Customer Info') }}</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-slate-400 shadow-sm"><User class="w-5 h-5" /></div>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $t('Name') }}</span>
                                <span class="text-sm font-black text-slate-900">{{ order.customer_name }}</span>
                            </div>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-slate-400 shadow-sm"><Phone class="w-5 h-5" /></div>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $t('Phone') }}</span>
                                <span class="text-sm font-black text-slate-900">{{ order.customer_phone }}</span>
                            </div>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-slate-400 shadow-sm"><Mail class="w-5 h-5" /></div>
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $t('Email') }}</span>
                                <span class="text-sm font-black text-slate-900">{{ order.customer_email || $t('Not Provided') }}</span>
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
                    <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase tracking-tighter italic">{{ $t('Confirm Status?') }}</h3>
                    <p class="text-sm text-slate-500 mb-10 font-medium">
                        {{ $t('Are you sure you want to change order status to') }} <span class="font-black text-[#003366] uppercase">{{ $t(newStatus) }}</span>?
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showStatusModal = false" class="px-6 py-4 rounded-2xl border-2 border-slate-100 text-slate-400 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50 transition-all">{{ $t('Cancel') }}</button>
                        <button @click="confirmStatusUpdate" class="px-6 py-4 rounded-2xl bg-[#003366] text-white font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all shadow-xl shadow-blue-900/20">{{ $t('Confirm') }}</button>
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
                    <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase tracking-tighter italic">{{ $t('Update Destination?') }}</h3>
                    <p class="text-sm text-slate-500 mb-10 font-medium leading-relaxed">
                        {{ $t('Moving to') }} <span class="font-black text-[#FF6600] uppercase">{{ $t(newLocation) }}</span> {{ $t('will adjust delivery charges and update the grand total.') }}
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showLocationModal = false" class="px-6 py-4 rounded-2xl border-2 border-slate-100 text-slate-400 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50 transition-all">{{ $t('Cancel') }}</button>
                        <button @click="confirmLocationUpdate" class="px-6 py-4 rounded-2xl bg-[#FF6600] text-white font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all shadow-xl shadow-orange-500/20">{{ $t('Update') }}</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Send to Courier Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showCourierModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2rem] p-10 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-20 h-20 bg-orange-50 text-[#FF6600] rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <Send class="w-10 h-10" />
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase tracking-tighter italic">{{ $t('Send to Steadfast?') }}</h3>
                    <p class="text-sm text-slate-500 mb-10 font-medium leading-relaxed">
                        {{ $t('Are you sure you want to send this order to Steadfast Courier?') }}
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showCourierModal = false" class="px-6 py-4 rounded-2xl border-2 border-slate-100 text-slate-400 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50 transition-all">{{ $t('Cancel') }}</button>
                        <button @click="confirmCourierSend" class="px-6 py-4 rounded-2xl bg-orange-600 text-white font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all shadow-xl shadow-orange-500/20">{{ $t('Send Now') }}</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Response Viewer Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showResponseModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl flex flex-col max-h-[80vh]">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center font-bold">
                                <Code2 class="w-5 h-5" />
                            </div>
                            <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight italic">{{ $t('Courier') }} <span class="text-orange-600">{{ $t('Response') }}</span></h3>
                        </div>
                        <button @click="showResponseModal = false" class="p-2 hover:bg-slate-100 rounded-xl transition-colors"><XCircle class="w-5 h-5 text-slate-400" /></button>
                    </div>
                    
                    <div class="flex-1 overflow-auto bg-slate-900 rounded-2xl p-6 shadow-inner">
                        <pre class="text-[11px] font-mono text-green-400 leading-relaxed">{{ formatJson(order.courier_response) }}</pre>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <button @click="showResponseModal = false" class="px-8 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black uppercase text-[10px] tracking-widest rounded-xl transition-all">{{ $t('Close Viewer') }}</button>
                    </div>
                </div>
            </div>
        </transition>
        <!-- Success Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showSuccessModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl text-center">
                    <div class="w-20 h-20 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <ShieldCheck class="w-10 h-10" />
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-2">{{ $t('Steadfast Update') }}</h3>
                    <p class="text-sm font-bold text-slate-500 mb-8">{{ $t(successModalMessage) }}</p>
                    <button @click="showSuccessModal = false" class="w-full py-4 bg-[#003366] hover:bg-[#FF6600] text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-blue-900/20">{{ $t('Great!') }}</button>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
    ArrowLeft, 
    Package, 
    Truck, 
    ShieldCheck, 
    User, 
    CheckCircle, 
    XCircle, 
    Banknote, 
    Phone, 
    Mail, 
    AlertTriangle,
    MessageSquare,
    Send,
    Code2,
    X
} from 'lucide-vue-next';

const props = defineProps({
    order: Object
});

const showStatusModal = ref(false);
const showLocationModal = ref(false);
const showCourierModal = ref(false);
const showResponseModal = ref(false);
const showSuccessModal = ref(false);
const successModalMessage = ref('');
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

const confirmCourierSend = () => {
    router.post(`/admin/orders/${props.order.id}/send-to-courier`, {}, {
        preserveScroll: true,
        onSuccess: (page) => {
            showCourierModal.value = false;
            successModalMessage.value = page.props.flash.success || 'Order sent to Steadfast successfully.';
            showSuccessModal.value = true;
        }
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
