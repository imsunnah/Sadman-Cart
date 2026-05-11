<template>
    <div class="relative z-[100]">
        <!-- Overlay -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-400 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="isOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="$emit('close')"></div>
        </Transition>

        <!-- Drawer -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0 translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-400 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 translate-x-full"
        >
            <div v-if="isOpen" class="fixed right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-2xl flex flex-col overflow-hidden">
                <!-- Header -->
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-[#003366] text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                            <ShoppingCart class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-lg font-black uppercase tracking-tighter italic">Your Basket</h2>
                            <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{{ cartCount }} Items Reserved</p>
                        </div>
                    </div>
                    <button @click="$emit('close')" class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center hover:bg-white hover:text-[#003366] transition-all">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <!-- Items -->
                <div class="flex-grow overflow-y-auto p-6 space-y-6 custom-scrollbar">
                    <div v-if="cart.length === 0" class="h-full flex flex-col items-center justify-center opacity-30">
                        <ShoppingBag class="w-16 h-16 mb-4 text-slate-300" />
                        <p class="text-xs font-black uppercase tracking-[0.3em] text-slate-400">Empty Basket</p>
                    </div>

                    <div v-for="item in cart" :key="item.id" class="flex gap-4 group">
                        <div class="w-20 h-20 bg-slate-50 rounded-2xl overflow-hidden flex-shrink-0 p-2 border border-slate-100">
                            <img 
                                :src="(item.product ? item.product.image : item.combo.image) || 'https://placehold.co/200x200'" 
                                class="w-full h-full object-contain"
                            />
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-[10px] font-black text-[#003366] uppercase tracking-widest leading-tight">
                                    {{ item.product ? item.product.name : item.combo.name }}
                                </h3>
                                <button @click="removeFromCart(item.id)" class="text-slate-300 hover:text-red-500 transition-colors">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">
                                ৳{{ (item.product ? item.product.price : item.combo.price).toLocaleString() }} / unit
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center bg-slate-50 rounded-xl border border-slate-100">
                                    <button @click="updateQuantity(item.id, item.quantity - 1)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-[#003366]">-</button>
                                    <span class="w-8 text-center text-xs font-black text-[#003366]">{{ item.quantity }}</span>
                                    <button @click="updateQuantity(item.id, item.quantity + 1)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-[#003366]">+</button>
                                </div>
                                <div class="text-xs font-black text-slate-900">
                                    ৳{{ ((item.product ? item.product.price : item.combo.price) * item.quantity).toLocaleString() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-slate-50 bg-slate-50/50">
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <span>Subtotal</span>
                            <span>৳{{ cartTotal.toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-black text-[#003366] uppercase tracking-tighter italic">
                            <span>Total Payable</span>
                            <span>৳{{ cartTotal.toLocaleString() }}</span>
                        </div>
                    </div>
                    <Link 
                        href="/checkout" 
                        @click="$emit('close')"
                        class="w-full py-5 bg-[#FF6600] text-white rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] flex items-center justify-center gap-3 shadow-2xl shadow-orange-500/20 hover:bg-orange-600 transition-all active:scale-95"
                    >
                        Secure Checkout <ArrowRight class="w-4 h-4" />
                    </Link>
                    <button 
                        @click="$emit('close')"
                        class="w-full mt-4 text-[9px] font-black text-slate-300 uppercase tracking-[0.3em] hover:text-[#003366] transition-colors"
                    >
                        Continue Selection
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ShoppingCart, X, Trash2, ShoppingBag, ArrowRight } from 'lucide-vue-next';
import { useCart } from '@/Composables/useCart';

defineProps({
    isOpen: Boolean
});

defineEmits(['close']);

const { cart, cartTotal, cartCount, removeFromCart, updateQuantity } = useCart();
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
</style>
