<template>
    <StoreLayout>
        <div class="bg-slate-50 min-h-screen py-20 font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-12 text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight flex items-center justify-center md:justify-start italic uppercase">
                        <Lock class="w-8 h-8 mr-4 text-[#FF6600]" /> Secure <span class="text-[#FF6600] ml-3">Checkout</span>
                    </h1>
                    <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px]">Complete your order with delivery intelligence</p>
                </div>

                <div class="flex flex-col lg:flex-row gap-12">
                    <!-- Checkout Form -->
                    <div class="flex-grow lg:w-2/3">
                        <div class="bg-white shadow-2xl shadow-slate-200/50 rounded-3xl border border-slate-100 overflow-hidden">
                            <div class="p-8 sm:p-12">
                                <form @submit.prevent="submitOrder">
                                    <div class="mb-12">
                                        <div class="flex items-center space-x-4 mb-8">
                                            <div class="w-10 h-10 bg-[#003366] text-white rounded-xl flex items-center justify-center text-sm font-black italic">01</div>
                                            <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase">Contact Protocol</h2>
                                        </div>
                                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                                            <div class="sm:col-span-2">
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Full Identity *</label>
                                                <input v-model="form.customer_name" type="text" required class="block w-full rounded-2xl bg-slate-50 border-none px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all" placeholder="Enter your full name">
                                                <div v-if="form.errors.customer_name" class="text-red-500 text-[10px] font-black mt-2 uppercase">{{ form.errors.customer_name }}</div>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Phone Number *</label>
                                                <input v-model="form.customer_phone" type="text" required class="block w-full rounded-2xl bg-slate-50 border-none px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all" placeholder="01XXXXXXXXX">
                                                <div v-if="form.errors.customer_phone" class="text-red-500 text-[10px] font-black mt-2 uppercase">{{ form.errors.customer_phone }}</div>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Email Address (Optional)</label>
                                                <input v-model="form.customer_email" type="email" class="block w-full rounded-2xl bg-slate-50 border-none px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all" placeholder="example@mail.com">
                                                <div v-if="form.errors.customer_email" class="text-red-500 text-[10px] font-black mt-2 uppercase">{{ form.errors.customer_email }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-12">
                                        <div class="flex items-center space-x-4 mb-8">
                                            <div class="w-10 h-10 bg-[#003366] text-white rounded-xl flex items-center justify-center text-sm font-black italic">02</div>
                                            <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase">Delivery Logistics</h2>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">District *</label>
                                                <input v-model="form.district" type="text" required class="block w-full rounded-2xl bg-slate-50 border-none px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all" placeholder="Enter District">
                                                <div v-if="form.errors.district" class="text-red-500 text-[10px] font-black mt-2 uppercase">{{ form.errors.district }}</div>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Upazila *</label>
                                                <input v-model="form.upazila" type="text" required class="block w-full rounded-2xl bg-slate-50 border-none px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all" placeholder="Enter Upazila">
                                                <div v-if="form.errors.upazila" class="text-red-500 text-[10px] font-black mt-2 uppercase">{{ form.errors.upazila }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-8">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Village / House Road *</label>
                                            <input v-model="form.village" type="text" required class="block w-full rounded-2xl bg-slate-50 border-none px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all" placeholder="Detailed Street/Area Info">
                                            <div v-if="form.errors.village" class="text-red-500 text-[10px] font-black mt-2 uppercase">{{ form.errors.village }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Full Shipping Address *</label>
                                            <textarea v-model="form.shipping_address" rows="3" required class="block w-full rounded-3xl bg-slate-50 border-none px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all" placeholder="e.g. House 10, Road 5, Mirpur-12, Dhaka"></textarea>
                                            <div v-if="form.errors.shipping_address" class="text-red-500 text-[10px] font-black mt-2 uppercase">{{ form.errors.shipping_address }}</div>
                                        </div>
                                    </div>

                                    <div class="mb-12">
                                        <div class="flex items-center space-x-4 mb-8">
                                            <div class="w-10 h-10 bg-[#003366] text-white rounded-xl flex items-center justify-center text-sm font-black italic">03</div>
                                            <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase">Payment Logic</h2>
                                        </div>
                                        <div class="bg-orange-50 border-4 border-orange-100 rounded-3xl p-8 flex items-start cursor-pointer hover:bg-orange-100/50 transition-all group">
                                            <div class="flex items-center h-6 mt-1">
                                                <div class="w-6 h-6 rounded-full border-2 border-[#FF6600] flex items-center justify-center bg-white">
                                                    <div class="w-3 h-3 bg-[#FF6600] rounded-full"></div>
                                                </div>
                                            </div>
                                            <div class="ml-6 text-sm">
                                                <label class="font-black text-slate-900 uppercase tracking-tight cursor-pointer italic">Cash on Delivery (COD)</label>
                                                <p class="text-slate-600 mt-2 font-bold uppercase tracking-widest text-[10px]">Pay securely upon physical item receipt</p>
                                            </div>
                                            <Banknote class="w-10 h-10 ml-auto text-[#FF6600] opacity-20 group-hover:opacity-40 transition-opacity" />
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <button type="submit" :disabled="form.processing" class="w-full flex justify-center items-center rounded-2xl bg-[#003366] py-6 px-10 text-lg font-black text-white shadow-2xl shadow-[#003366]/30 hover:bg-slate-800 transition-all transform active:scale-95 disabled:opacity-50 uppercase tracking-[0.2em]">
                                            <span v-if="form.processing">Processing Order...</span>
                                            <span v-else class="flex items-center font-black italic">Confirm Purchase <ArrowRight class="w-6 h-6 ml-4" /></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="lg:w-1/3 w-full lg:sticky lg:top-32 h-fit">
                        <div class="bg-white shadow-2xl shadow-slate-200/50 rounded-3xl border border-slate-100 overflow-hidden">
                            <div class="p-8 bg-slate-50 border-b border-slate-100">
                                <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Order Architecture</h2>
                            </div>
                            <div class="p-8">
                                <ul role="list" class="divide-y divide-slate-50 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                                    <li v-for="item in cart.items" :key="item.id" class="flex py-6 group">
                                        <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-2xl border border-slate-100 bg-slate-50">
                                            <img v-if="item.product?.image" :src="item.product.image" :alt="item.product.name" class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform" />
                                            <div v-else class="h-full w-full flex items-center justify-center text-slate-300">
                                                <Package class="w-6 h-6" />
                                            </div>
                                        </div>
                                        <div class="ml-6 flex flex-1 flex-col justify-center">
                                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-tight line-clamp-1 italic">{{ item.product?.name }}</h3>
                                            <div class="flex justify-between items-center mt-2">
                                                <p class="text-[10px] font-black text-slate-400 uppercase">Qty {{ item.quantity }}</p>
                                                <p class="text-sm font-black text-[#003366]">৳{{ (item.product?.price * item.quantity).toLocaleString() }}</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>

                                <div class="border-t border-slate-100 pt-8 mt-4 space-y-4">
                                    <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        <p>Subtotal</p>
                                        <p class="text-slate-900 font-black">৳{{ subtotal.toLocaleString() }}</p>
                                    </div>
                                    <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        <p>Delivery</p>
                                        <p class="text-green-600 font-black">FREE</p>
                                    </div>
                                    <div class="flex justify-between border-t border-slate-100 pt-6 mt-4">
                                        <p class="text-lg font-black text-slate-900 uppercase tracking-tighter italic">Total Amount</p>
                                        <p class="text-2xl font-black text-[#003366]">৳{{ subtotal.toLocaleString() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 bg-[#003366] text-white rounded-3xl p-8 flex items-center space-x-6 shadow-2xl shadow-[#003366]/20">
                            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                                <Truck class="w-6 h-6 text-[#FF6600]" />
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-[#FF6600]">Free Shipping</p>
                                <p class="text-[8px] font-black text-slate-300 mt-1 uppercase tracking-[0.2em]">National Delivery Protocol</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { useCart } from '@/Composables/useCart';
import { Lock, Package, ArrowRight, Banknote, Truck } from 'lucide-vue-next';

const props = defineProps({
    cart: Object
});

const { clearCart } = useCart();

const form = useForm({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    shipping_address: '',
    district: '',
    upazila: '',
    village: '',
});

const subtotal = computed(() => {
    return props.cart.items.reduce((total, item) => total + (item.product.price * item.quantity), 0);
});

const submitOrder = () => {
    form.post('/checkout', {
        onSuccess: () => {
            clearCart();
        }
    });
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 51, 102, 0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 51, 102, 0.1);
}
</style>
