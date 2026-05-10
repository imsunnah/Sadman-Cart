<template>
    <!-- Horizontal Layout (e.g. for Top Selling) -->
    <div v-if="layout === 'horizontal'" class="group relative bg-white rounded-[2.5rem] border border-slate-100 p-6 flex flex-row items-center gap-8 hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500 font-sans animate-in fade-in slide-in-from-left-4 duration-700">
        <!-- Image Area (Left) -->
        <div @click="$emit('view-image', product)" class="w-1/3 aspect-square flex-shrink-0 cursor-zoom-in relative overflow-hidden rounded-[2rem] bg-slate-50 border border-slate-100 group-hover:border-[#FF6600]/20 transition-all">
            <img 
                :src="product.image || 'https://placehold.co/400x400/f8fafc/cbd5e1?text=No+Image'" 
                :alt="product.name"
                class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700" 
            />
            <!-- Quick View Overlay -->
            <div class="absolute inset-0 bg-[#003366]/40 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center backdrop-blur-[2px]">
                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                    <Maximize2 class="w-5 h-5 text-[#FF6600]" />
                </div>
            </div>
        </div>

        <!-- Product Details (Right) -->
        <div class="flex-grow flex flex-col">
            <Link :href="`/products/${product.slug}`" class="text-xl font-black text-[#003366] hover:text-[#FF6600] transition-colors mb-2 line-clamp-1 italic uppercase tracking-tighter">
                {{ product.name }}
            </Link>
            
            <div class="flex items-center gap-3 mb-6">
                <span class="text-2xl font-black text-[#FF6600]">৳{{ parseFloat(product.price).toLocaleString() }}</span>
                <span class="text-xs text-slate-300 line-through font-bold italic">৳{{ (parseFloat(product.price) * 1.2).toFixed(0) }}</span>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button 
                    @click="addToCart(product)"
                    class="flex-1 h-12 bg-white border-2 border-slate-100 text-[#003366] rounded-xl font-black text-[9px] uppercase tracking-widest hover:border-[#FF6600] hover:text-[#FF6600] transition-all flex items-center justify-center gap-2"
                >
                    <ShoppingCart class="w-4 h-4" /> Add
                </button>
                <button 
                    @click="buyNow(product)"
                    class="flex-1 h-12 bg-[#003366] text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-black transition-all shadow-lg active:scale-95"
                >
                    Buy Now
                </button>
            </div>
        </div>
    </div>

    <!-- Vertical Layout (Default) -->
    <div v-else class="group bg-white rounded-[2rem] overflow-hidden border border-slate-100 transition-all duration-500 hover:shadow-2xl hover:shadow-slate-200/50 hover:-translate-y-2 animate-in fade-in zoom-in duration-700">
        <!-- Image Container -->
        <div class="relative aspect-square bg-slate-50 overflow-hidden flex items-center justify-center p-6 cursor-pointer" @click="$inertia.visit(`/products/${product.slug}`)">
            <img 
                :src="product.image || 'https://placehold.co/400x400/f8fafc/cbd5e1?text=No+Image'" 
                :alt="product.name"
                class="max-w-full max-h-full object-contain transition-transform duration-700 group-hover:scale-110"
            />
            
            <!-- Quick View Overlay -->
            <div class="absolute inset-0 bg-[#003366]/40 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center backdrop-blur-[2px]">
                <button 
                    @click.stop="$emit('view-image', product)"
                    class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-2xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 hover:bg-[#FF6600] hover:text-white"
                >
                    <Maximize2 class="w-5 h-5" />
                </button>
            </div>
        </div>

        <!-- Info -->
        <div class="p-6">
            <h3 class="text-xs font-black text-[#003366] uppercase tracking-widest line-clamp-1 mb-2 italic group-hover:text-[#FF6600] transition-colors">
                <Link :href="`/products/${product.slug}`">{{ product.name }}</Link>
            </h3>
            
            <div class="flex items-center justify-between mt-4">
                <div class="flex flex-col">
                    <span class="text-sm text-slate-300 line-through font-bold italic">৳{{ (parseFloat(product.price) * 1.2).toFixed(0) }}</span>
                    <span class="text-lg font-black text-slate-900 tracking-tighter">৳{{ parseFloat(product.price).toLocaleString() }}</span>
                </div>
                <button 
                    @click.stop="addToCart(product)"
                    class="w-10 h-10 bg-[#003366] text-white rounded-xl flex items-center justify-center hover:bg-[#FF6600] transition-all duration-300 shadow-lg active:scale-90"
                >
                    <ShoppingCart class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { useCart } from '@/Composables/useCart';
import { Package, ShoppingCart, Maximize2, Plus } from 'lucide-vue-next';

const props = defineProps({
    product: Object,
    layout: {
        type: String,
        default: 'vertical'
    }
});

const { addToCart } = useCart();

const buyNow = async (product) => {
    await addToCart(product);
    router.visit('/cart');
};
</script>