<template>
    <StoreLayout>
        <div class="bg-slate-50 min-h-screen py-16 font-sans relative">
            <!-- Processing Overlay -->
            <Transition name="fade">
                <div v-if="form.processing" class="fixed inset-0 z-[100] bg-white/90 backdrop-blur-xl flex flex-col items-center justify-center text-slate-900">
                    <div class="relative w-24 h-24 mb-6">
                        <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-[#003366] rounded-full animate-spin border-t-transparent shadow-lg"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <Lock class="w-8 h-8 text-[#003366]" />
                        </div>
                    </div>
                    <h2 class="text-2xl font-black uppercase tracking-tight mb-2">Processing Order</h2>
                    <p class="text-slate-500 font-bold uppercase tracking-[0.2em] text-[10px]">Please wait while we secure your purchase</p>
                </div>
            </Transition>

            <!-- Confirmation Modal -->
            <Transition name="modal">
                <div v-if="showConfirmModal" class="fixed inset-0 z-[90] flex items-center justify-center px-4">
                    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showConfirmModal = false"></div>
                    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg relative overflow-hidden border border-slate-100">
                        <div class="p-10">
                            <div class="w-16 h-16 bg-[#003366]/5 rounded-2xl flex items-center justify-center mb-6">
                                <ShoppingCart class="w-8 h-8 text-[#003366]" />
                            </div>
                            
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase mb-3">Place Your Order?</h2>
                            <p class="text-slate-500 font-medium leading-relaxed mb-8">
                                You are placing an order for <span class="text-[#003366] font-bold">৳{{ subtotal.toLocaleString() }}</span>. 
                                By clicking confirm, your order will be sent for processing.
                            </p>

                            <div class="bg-slate-50 rounded-2xl p-5 mb-8 space-y-3 border border-slate-100">
                                <div class="flex items-center text-sm font-bold text-slate-700">
                                    <MapPin class="w-4 h-4 text-[#003366] mr-3" />
                                    {{ form.district }}, {{ form.upazila }}
                                </div>
                                <div class="flex items-center text-sm font-bold text-slate-700">
                                    <Phone class="w-4 h-4 text-[#003366] mr-3" />
                                    {{ form.customer_phone }}
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <button @click="showConfirmModal = false" class="flex-1 py-4 rounded-xl text-[11px] font-black text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-all border border-slate-200">
                                    Cancel
                                </button>
                                <button @click="confirmAndSubmit" class="flex-[2] py-4 bg-[#003366] rounded-xl text-[11px] font-black text-white uppercase tracking-[0.2em] shadow-lg shadow-[#003366]/20 hover:bg-slate-800 transition-all transform active:scale-95 flex items-center justify-center">
                                    Confirm Order <ArrowRight class="w-4 h-4 ml-2" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>

            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-10">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center uppercase">
                        <ShoppingBag class="w-8 h-8 mr-3 text-[#003366]" />
                        Checkout
                    </h1>
                    <div class="h-1 w-20 bg-[#003366] mt-4 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <!-- Checkout Form -->
                    <div class="lg:col-span-7">
                        <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100 p-8 sm:p-10">
                            <form @submit.prevent="showConfirmModal = true">
                                <!-- Section 1: Contact -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-black text-[#003366] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded-lg bg-[#003366]/10 flex items-center justify-center mr-3 text-xs">1</span>
                                        Contact Information
                                    </h2>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                                            <input v-model="form.customer_name" type="text" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="e.g. John Doe">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Phone Number</label>
                                            <input v-model="form.customer_phone" type="text" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="01XXXXXXXXX">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Email (Optional)</label>
                                            <input v-model="form.customer_email" type="email" class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="john@example.com">
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 2: Shipping -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-black text-[#003366] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded-lg bg-[#003366]/10 flex items-center justify-center mr-3 text-xs">2</span>
                                        Shipping Address
                                    </h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">District</label>
                                            <input v-model="form.district" type="text" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="Enter District">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Upazila</label>
                                            <input v-model="form.upazila" type="text" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="Enter Upazila">
                                        </div>
                                    </div>
                                    <div class="mb-6">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Village / Area / House</label>
                                        <input v-model="form.village" type="text" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="Detailed address details">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Full Address (Summary)</label>
                                        <textarea v-model="form.shipping_address" rows="2" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="Enter your full searchable address"></textarea>
                                    </div>
                                </div>

                                <!-- Section 3: Payment -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-black text-[#003366] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded-lg bg-[#003366]/10 flex items-center justify-center mr-3 text-xs">3</span>
                                        Payment Method
                                    </h2>
                                    <div class="bg-slate-50 border-2 border-[#003366] rounded-2xl p-6 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-5 h-5 rounded-full border-4 border-[#003366] mr-4 flex-shrink-0"></div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900 uppercase">Cash on Delivery</p>
                                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pay when you receive your order</p>
                                            </div>
                                        </div>
                                        <Banknote class="w-8 h-8 text-[#003366]/20" />
                                    </div>
                                </div>

                                <button type="submit" :disabled="form.processing" class="w-full bg-[#003366] py-5 rounded-2xl text-white font-black uppercase tracking-[0.2em] shadow-xl shadow-[#003366]/20 hover:bg-slate-800 transition-all transform active:scale-[0.98] disabled:opacity-50 flex items-center justify-center">
                                    Continue to Confirmation <ArrowRight class="w-5 h-5 ml-3" />
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="lg:col-span-5">
                        <div class="lg:sticky lg:top-24 space-y-6">
                            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100 overflow-hidden">
                                <div class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                                    <h2 class="text-[11px] font-black text-[#003366] uppercase tracking-[0.2em]">Order Summary</h2>
                                    <span class="px-3 py-1 bg-white rounded-full text-[10px] font-black text-slate-500 border border-slate-100">{{ cart.items.length }} Items</span>
                                </div>
                                <div class="p-6">
                                    <ul role="list" class="divide-y divide-slate-100 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                        <li v-for="item in cart.items" :key="item.id" class="flex py-4">
                                            <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl border border-slate-50 bg-slate-100">
                                                <img v-if="item.product?.image" :src="item.product.image" :alt="item.product.name" class="h-full w-full object-cover object-center" />
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-tight line-clamp-1 italic">{{ item.product?.name }}</h3>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-[10px] font-bold text-slate-400">Qty {{ item.quantity }}</p>
                                                    <p class="text-xs font-black text-slate-900">৳{{ (item.product?.price * item.quantity).toLocaleString() }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>

                                    <div class="border-t border-slate-100 pt-6 mt-4 space-y-3">
                                        <div class="flex justify-between text-[11px] font-bold text-slate-500">
                                            <p>Subtotal</p>
                                            <p>৳{{ subtotal.toLocaleString() }}</p>
                                        </div>
                                        <div class="flex justify-between text-[11px] font-bold text-slate-500">
                                            <p>Shipping</p>
                                            <p class="text-green-600">FREE</p>
                                        </div>
                                        <div class="flex justify-between border-t border-slate-100 pt-5 mt-3">
                                            <p class="text-sm font-black text-slate-900 uppercase italic">Total to pay</p>
                                            <p class="text-2xl font-black text-[#003366]">৳{{ subtotal.toLocaleString() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-[#003366] text-white rounded-3xl p-6 flex items-center space-x-4 shadow-xl shadow-[#003366]/10">
                                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                                    <Truck class="w-5 h-5 text-white" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-[11px] font-black uppercase tracking-widest">Free Express Delivery</p>
                                    <p class="text-[9px] font-bold text-white/50 uppercase tracking-widest mt-0.5">Estimated 2-3 business days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { useCart } from '@/Composables/useCart';
import { Lock, Package, ArrowRight, Banknote, Truck, X, ShoppingCart, MapPin, Phone, ShoppingBag } from 'lucide-vue-next';

const props = defineProps({
    cart: Object
});

const { clearCart } = useCart();
const showConfirmModal = ref(false);

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

const confirmAndSubmit = () => {
    showConfirmModal.value = false;
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

/* Modal Animations */
.modal-enter-active, .modal-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.modal-enter-from, .modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
}

/* Fade Animations */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
