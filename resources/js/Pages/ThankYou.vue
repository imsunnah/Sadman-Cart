<template>
    <StoreLayout>
        <div class="bg-gray-50 min-h-screen py-10 sm:py-16 px-4 font-sans antialiased">
            <div class="max-w-3xl mx-auto space-y-6">
                
                <!-- NEW: Critical Confirmation Notice Banner -->
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 sm:p-5 flex items-start gap-4 shadow-sm">
                    <div class="flex-shrink-0 bg-amber-500 text-white rounded-xl p-2 mt-0.5 shadow-sm shadow-amber-500/20">
                        <Truck class="w-5 h-5" />
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-amber-900 uppercase tracking-wider mb-0.5">{{ $t('Verification Required') }}</h4>
                        <p class="text-xs text-amber-800 font-medium leading-relaxed">
                            {{ $t('To prevent spam and ensure accurate logistics, our customer care team will call you at') }} <span class="font-bold text-amber-950 underline decoration-amber-500/50 decoration-2">{{ order.customer_phone }}</span> {{ $t('to confirm your details.') }} <span class="font-bold">{{ $t('Your items will only be dispatched after this verbal verification.') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-sm border border-gray-100 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-[#003366]"></div>
                    <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-slate-50 mb-6 border-4 border-slate-100 shadow-inner">
                        <CheckCircle class="h-12 w-12 text-[#FF6600]" />
                    </div>
                    
                    <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3 uppercase">{{ $t('Order Received') }}</h1>
                    <p class="text-base sm:text-lg text-slate-600 mb-4 font-medium max-w-md mx-auto">
                        {{ $t('Thank you for your order,') }} <span class="text-slate-900 font-bold">{{ order.customer_name }}</span>. {{ $t("We've queued your request into our processing queue.") }}
                    </p>
                    <div class="inline-flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-full border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $t('Order ID:') }}</span>
                        <span class="text-xs font-black text-[#003366] tracking-widest">#{{ String(order.id).padStart(6, '0') }}</span>
                    </div>
                </div>

                <!-- Registration Prompt (Only shown to Guests) -->
                <div v-if="!$page.props.auth.user" class="bg-orange-50/70 border border-orange-100/80 p-6 sm:p-8 rounded-2xl shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 transition-all hover:bg-orange-50">
                    <div class="text-center sm:text-left">
                        <h2 class="text-lg font-black text-slate-900 mb-1 flex items-center justify-center sm:justify-start uppercase tracking-tight">
                            <UserPlus class="w-5 h-5 mr-2 text-[#FF6600]"/> {{ $t('Save Your Progress') }}
                        </h2>
                        <p class="text-xs font-semibold text-slate-500 leading-relaxed max-w-sm">{{ $t('Track order status pipelines in real-time, manage multiple addresses, and unlock exclusive rewards.') }}</p>
                    </div>
                    <div class="flex-shrink-0 w-full sm:w-auto text-center sm:text-right">
                        <Link href="/register" class="block w-full sm:inline-block bg-[#FF6600] text-white font-black py-3 px-6 rounded-xl shadow-lg shadow-orange-500/20 hover:bg-orange-600 hover:shadow-orange-600/20 active:scale-[0.98] transition-all uppercase tracking-widest text-xs">
                            {{ $t('Register Account') }}
                        </Link>
                        <p class="text-xs mt-3 text-slate-500 font-medium">
                            {{ $t('Already have access?') }} <Link href="/login" class="text-[#003366] font-bold hover:underline">{{ $t('Log In') }}</Link>
                        </p>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 sm:p-6 border-b border-gray-100 bg-slate-50/70 flex items-center justify-between">
                        <h3 class="font-black text-xs text-slate-700 flex items-center uppercase tracking-widest">
                            <ShoppingBag class="w-4 h-4 mr-2 text-slate-400" /> {{ $t('Manifest Summary') }}
                        </h3>
                        <span class="text-xs font-bold text-slate-400">{{ order.items.length }} {{ $t('Item(s)') }}</span>
                    </div>
                    
                    <div class="p-6 sm:p-8">
                        <div class="space-y-2.5 max-h-96 overflow-y-auto pr-1 custom-scrollbar">
                            <div class="flex items-center justify-between text-xs p-3 rounded-xl bg-slate-50/50 hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors" v-for="item in order.items" :key="item.id">
                                <div class="flex flex-1 items-center min-w-0 mr-4">
                                    <span class="font-black text-white bg-[#003366] px-2 py-1 rounded-lg text-[10px] min-w-[28px] text-center shadow-sm shadow-[#003366]/10 mr-3">{{ item.quantity }}x</span>
                                    <span class="text-slate-700 font-bold uppercase tracking-tight truncate">{{ item.product_name }}</span>
                                </div>
                                <span class="font-black text-slate-900 whitespace-nowrap bg-white px-2.5 py-1 rounded-lg border border-gray-100 shadow-sm">৳ {{ parseFloat(item.price * item.quantity).toLocaleString() }}</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                            <div class="flex justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <p>{{ $t('Subtotal') }}</p>
                                <p class="text-slate-900 font-black">৳ {{ (order.total_amount - order.delivery_charge).toLocaleString() }}</p>
                            </div>
                            <div class="flex justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <p>{{ $t('Delivery') }} ({{ order.delivery_location }})</p>
                                <p class="text-[#FF6600] font-black">৳ {{ parseFloat(order.delivery_charge).toLocaleString() }}</p>
                            </div>
                            <div class="flex justify-between items-center border-t border-dashed border-gray-200 pt-5 mt-4">
                                <p class="text-base font-black text-slate-900 uppercase tracking-tight">{{ $t('Total Amount') }}</p>
                                <p class="text-2xl font-black text-[#003366]">৳ {{ parseFloat(order.total_amount).toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Operations & Navigation -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 text-center text-xs text-slate-500 font-semibold uppercase tracking-wider">
                    <p class="leading-relaxed max-w-xl mx-auto">
                        {{ $t('Settlement Mode:') }} <strong class="text-slate-900">{{ $t('Cash on Delivery') }}</strong>. {{ $t('Our delivery courier will arrive at your destination after the initial phone verification is secured.') }}
                    </p>
                    <div class="mt-8">
                        <Link href="/" class="inline-flex items-center justify-center rounded-xl border-2 border-slate-200 bg-white py-3.5 px-8 text-xs font-black text-[#003366] shadow-sm hover:bg-slate-50 hover:border-slate-300 active:scale-[0.99] transition-all uppercase tracking-widest">
                            <ArrowLeft class="w-4 h-4 mr-2" /> {{ $t('Continue Shopping') }}
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