<template>
    <div class="group relative bg-white rounded-[2rem] overflow-hidden border border-slate-100 flex flex-col transition-all duration-700 hover:-translate-y-2 hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] hover:border-[#FF6600]/20">

        <!-- Discount badge -->
        <div v-if="product.discount_type" class="absolute top-4 left-4 z-10 bg-[#FF6600] text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg uppercase tracking-widest animate-pulse">
            {{ product.discount_type === 'percentage' ? product.discount_value + '% OFF' : '৳' + product.discount_value + ' OFF' }}
        </div>

        <!-- Stock badge -->
        <div v-if="product.stock <= 5 && product.stock > 0" class="absolute top-3 right-3 z-10 bg-red-500 text-white text-[8px] font-black px-2 py-0.5 rounded-full">
            Only {{ product.stock }} left
        </div>

        <!-- Image — click → detail page -->
        <Link :href="`/products/${product.slug}`" class="block relative w-full bg-gradient-to-br from-slate-50 to-slate-100 overflow-hidden" style="aspect-ratio:1/1;">
            <img
                v-if="product.image"
                :src="product.image"
                :alt="product.name"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                loading="lazy"
            />
            <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-200">
                <Package class="w-16 h-16" />
                <span class="text-[9px] font-black uppercase tracking-widest mt-2">No Image</span>
            </div>
            <!-- Hover overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#003366]/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-700 flex items-center justify-center">
                <div class="bg-white/90 backdrop-blur-md text-[#003366] text-[10px] font-black px-6 py-3 rounded-2xl uppercase tracking-[0.2em] shadow-2xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 flex items-center gap-2 border border-white">
                    <Eye class="w-4 h-4" /> Quick View
                </div>
            </div>
        </Link>

        <!-- Card Body -->
        <div class="flex flex-col flex-1 p-4">
            <!-- Category -->
            <span v-if="product.category" class="text-[9px] font-black text-[#FF6600]/70 uppercase tracking-[0.15em] mb-1">
                {{ product.category.name }}
            </span>

            <!-- Name — click → detail page -->
            <Link :href="`/products/${product.slug}`" class="flex-grow mb-4">
                <h3 class="text-sm font-black text-[#003366] uppercase tracking-tight leading-snug line-clamp-2 group-hover:text-[#FF6600] transition-colors duration-300">
                    {{ product.name }}
                </h3>
            </Link>

            <!-- Price -->
            <div class="flex items-center gap-3 mb-6">
                <span class="text-xl font-black text-[#FF6600] tracking-tighter leading-none drop-shadow-sm">
                    ৳{{ parseFloat(discountedPrice).toLocaleString() }}
                </span>
                <span v-if="product.discount_type" class="text-[10px] text-slate-300 line-through font-bold">
                    ৳{{ parseFloat(product.price).toLocaleString() }}
                </span>
            </div>

            <!-- 2 Action Buttons -->
            <!-- 2 Action Buttons -->
            <div class="grid grid-cols-2 gap-3">
                <button
                    :disabled="product.stock <= 0"
                    @click.prevent="handleAddToCart"
                    class="h-12 rounded-2xl bg-[#FF6600] text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-[#003366] transition-all duration-300 shadow-lg shadow-orange-900/10 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed"
                >
                    Add To Cart
                </button>
                <button
                    :disabled="product.stock <= 0"
                    @click.prevent="handleBuyNow"
                    class="h-12 rounded-2xl bg-[#003366] text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-black transition-all duration-300 shadow-lg shadow-blue-900/10 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed"
                >
                    Buy Now
                </button>
            </div>

            <div v-if="product.stock <= 0" class="text-center mt-2">
                <span class="text-[9px] font-black text-red-400 uppercase tracking-widest">Out of Stock</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { ShoppingCart, Package, Eye, Zap } from 'lucide-vue-next';
import { useCart } from '@/Composables/useCart';

const props = defineProps({
    product: Object,
});

defineEmits(['view-image']);

const { addToCart } = useCart();

const discountedPrice = computed(() => {
    if (!props.product.discount_type) return props.product.price;
    if (props.product.discount_type === 'percentage') {
        return props.product.price * (1 - props.product.discount_value / 100);
    }
    return Math.max(0, props.product.price - props.product.discount_value);
});

const handleAddToCart = async () => {
    await addToCart(props.product, 1);
};

const handleBuyNow = async () => {
    await addToCart(props.product, 1);
    router.visit('/checkout');
};
</script>