<template>
    <!-- Horizontal Layout (e.g. for Top Selling) -->
    <div v-if="layout === 'horizontal'" class="relative bg-white rounded-lg border border-gray-100 p-6 flex flex-row items-center gap-6 group hover:shadow-xl transition-all duration-300 font-sans">
        <!-- Badge -->
        <div class="absolute top-3 right-0 bg-red-500 text-white text-[10px] font-black px-3 py-1 rounded-l-full flex items-center gap-1 z-10 uppercase tracking-widest">
            <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
            Top Selling
        </div>

        <!-- Image Area (Left) -->
        <Link :href="`/products/${product.slug}`" class="w-1/3 aspect-square flex-shrink-0">
            <img 
                v-if="product.image" 
                :src="product.image" 
                :alt="product.name" 
                class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform" 
            />
            <div v-else class="w-full h-full flex items-center justify-center bg-gray-50 rounded-lg">
                <Package class="w-10 h-10 text-gray-200" />
            </div>
        </Link>

        <!-- Product Details (Right) -->
        <div class="flex-grow flex flex-col">
            <Link :href="`/products/${product.slug}`" class="text-lg font-black text-slate-800 hover:text-[#FF6600] transition-colors mb-2 line-clamp-1 italic uppercase tracking-tight">
                {{ product.name }}
            </Link>
            
            <!-- Pricing & Savings -->
            <div class="flex flex-col gap-1 mb-6">
                <div class="flex items-center gap-3">
                    <span class="text-[#FF6600] font-black text-2xl">৳{{ parseFloat(product.price).toLocaleString() }}</span>
                    <span class="text-gray-300 text-lg line-through font-bold">৳{{ (parseFloat(product.price) * 1.2).toFixed(0) }}</span>
                </div>
                <!-- Savings Badge -->
                <div class="inline-block self-start bg-green-500 text-white text-[11px] font-black px-2 py-0.5 rounded shadow-sm">
                    Save ৳{{ (parseFloat(product.price) * 0.2).toFixed(0) }}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button 
                    @click="addToCart(product)"
                    class="flex-1 flex items-center justify-center gap-2 py-2.5 px-2 border border-[#FF6600] text-[#FF6600] rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-orange-50 transition-colors"
                >
                    <ShoppingCart class="w-3 h-3" />
                    Add To Cart
                </button>
                <button 
                    @click="buyNow(product)"
                    class="flex-1 py-2.5 px-2 bg-[#FF6600] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-orange-600 transition-all shadow-xl shadow-orange-500/20"
                >
                    Buy Now
                </button>
            </div>
        </div>
    </div>

    <!-- Vertical Layout (Default) -->
    <div v-else class="relative bg-white rounded-lg border border-slate-100 p-4 flex flex-col group hover:shadow-xl transition-all duration-300 font-sans h-full">
        <!-- Top Right Badge (Save %) -->
        <div class="absolute top-3 right-3 bg-green-500 text-white text-[10px] font-black px-2 py-1 rounded shadow-sm z-10">
            SAVE 20%
        </div>

        <!-- Image Area -->
        <Link :href="`/products/${product.slug}`" class="relative block aspect-square bg-white mb-4 overflow-hidden">
            <img 
                v-if="product.image" 
                :src="product.image" 
                :alt="product.name" 
                class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-700" 
            />
            <div v-else class="w-full h-full flex items-center justify-center text-slate-200">
                <Package class="w-12 h-12" />
            </div>
        </Link>

        <!-- Product Details -->
        <div class="flex flex-col flex-grow">
            <Link :href="`/products/${product.slug}`" class="text-sm font-black text-slate-800 hover:text-[#FF6600] line-clamp-1 mb-2 transition-colors italic uppercase tracking-tight">
                {{ product.name }}
            </Link>
            
            <div class="flex items-center gap-3 mb-4">
                <span class="text-[#FF6600] font-black text-lg">৳{{ parseFloat(product.price).toLocaleString() }}</span>
                <span class="text-slate-300 text-sm line-through font-bold">৳{{ (parseFloat(product.price) * 1.2).toFixed(0) }}</span>
            </div>
            
            <div class="mt-auto">
                <button 
                    @click="addToCart(product)" 
                    :disabled="product.stock <= 0" 
                    class="w-full flex items-center justify-center py-3 bg-white text-[#FF6600] border-2 border-[#FF6600] rounded-xl hover:bg-[#FF6600] hover:text-white transition-all text-[11px] font-black uppercase tracking-widest"
                >
                    <ShoppingCart class="w-4 h-4 mr-2" />
                    Add To Cart
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { useCart } from '@/Composables/useCart';
import { Package, ShoppingCart } from 'lucide-vue-next';

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