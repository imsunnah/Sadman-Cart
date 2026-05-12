<template>
    <StoreLayout>
        <div class="bg-gray-50 min-h-screen py-10 sm:py-16 px-4 font-sans">
            <div class="max-w-3xl mx-auto space-y-6">
                <!-- Success Message -->
                <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-sm border border-gray-100 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-[#003366]"></div>
                    <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-slate-50 mb-6 border-4 border-slate-100">
                        <CheckCircle class="h-12 w-12 text-[#FF6600]" />
                    </div>
                    
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-4 italic uppercase">Order Protocol Confirmed</h1>
                    <p class="text-lg text-slate-600 mb-2 font-bold uppercase tracking-tight">
                        Thank you for your trust, <span class="text-slate-900">{{ order.customer_name }}</span>.
                    </p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                        Your Order ID <span class="text-[#003366]">#{{ String(order.id).padStart(6, '0') }}</span> has been successfully initialized.
                    </p>
                </div>

                <!-- Registration Prompt -->
                <div v-if="!$page.props.auth.user" class="bg-orange-50 border border-orange-100 p-6 sm:p-8 rounded-2xl shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div>
                        <h2 class="text-xl font-black text-slate-900 mb-2 flex items-center italic uppercase tracking-tight"><UserPlus class="w-6 h-6 mr-2 text-[#FF6600]"/> Initialize Account</h2>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Track order status, manage logistics, and earn reward protocols!</p>
                    </div>
                    <div class="flex-shrink-0 w-full sm:w-auto">
                        <Link href="/register" class="block w-full text-center bg-[#FF6600] text-white font-black py-3 px-6 rounded-xl shadow-xl shadow-orange-500/20 hover:bg-orange-600 transition-all uppercase tracking-widest text-[10px]">
                            Register Now
                        </Link>
                        <p class="text-[10px] text-center mt-3 text-slate-500 font-black uppercase tracking-widest">
                            Already have access? <Link href="/login" class="text-[#003366] hover:underline">Log In</Link>
                        </p>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-black text-sm text-slate-900 flex items-center italic uppercase tracking-widest">
                            <ShoppingBag class="w-5 h-5 mr-2 text-slate-500" /> Order Summary
                        </h3>
                    </div>
                    
                    <div class="p-6 sm:p-8">
                        <div class="space-y-4 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                            <div class="flex items-center justify-between text-xs py-3 border-b border-gray-50 last:border-0" v-for="item in order.items" :key="item.id">
                                <div class="flex flex-1 items-start">
                                    <span class="font-black text-white bg-[#003366] px-2 py-0.5 rounded text-[8px] mr-3 mt-0.5">{{ item.quantity }}x</span>
                                    <span class="text-slate-700 font-bold uppercase tracking-tight italic">{{ item.product_name }}</span>
                                </div>
                                <span class="font-black text-slate-900 ml-4">৳ {{ parseFloat(item.price * item.quantity).toLocaleString() }}</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                            <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <p>Subtotal</p>
                                <p class="text-slate-900">৳ {{ (order.total_amount - order.delivery_charge).toLocaleString() }}</p>
                            </div>
                            <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <p>Delivery Charge ({{ order.delivery_location }})</p>
                                <p class="text-[#FF6600]">৳ {{ parseFloat(order.delivery_charge).toLocaleString() }}</p>
                            </div>
                            <div class="flex justify-between border-t border-dashed border-gray-200 pt-6 mt-4">
                                <p class="text-lg font-black text-slate-900 italic uppercase tracking-tighter">Total Payable</p>
                                <p class="text-2xl font-black text-[#003366]">৳ {{ parseFloat(order.total_amount).toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Instructions -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 text-center text-[11px] text-slate-500 font-bold uppercase tracking-widest">
                    <Truck class="w-8 h-8 mx-auto text-[#FF6600] mb-3" />
                    <p class="leading-relaxed">
                        Payment Method: <strong class="text-slate-900">Cash on Delivery</strong>. Our logistics protocol will contact you at <strong class="text-[#003366]">{{ order.customer_phone }}</strong> to confirm physical delivery arrival.
                    </p>
                    <div class="mt-8">
                        <Link href="/" class="inline-flex items-center justify-center rounded-xl border-2 border-slate-100 bg-white py-4 px-8 text-[10px] font-black text-[#003366] shadow-sm hover:bg-slate-50 transition-all uppercase tracking-[0.2em]">
                            <ArrowLeft class="w-4 h-4 mr-2" /> Return to Home
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { CheckCircle, ShoppingBag, Truck, ArrowLeft, UserPlus } from 'lucide-vue-next';

defineProps({
    order: {
        type: Object,
        required: true
    }
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent; 
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #e5e7eb; 
  border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #d1d5db; 
}
</style>
