<template>
    <div class="group relative bg-white rounded-2xl overflow-hidden border border-slate-100 flex flex-col transition-all duration-500 hover:-translate-y-1.5 hover:shadow-[0_20px_45px_-12px_rgba(0,0,0,0.06)] hover:border-[#003366]/20">
        
        <!-- Badges -->
        <div class="absolute top-3 inset-x-3 z-10 flex justify-between items-start pointer-events-none">
            <!-- Left Badge -->
            <span v-if="product.stock > 0" class="bg-[#FF6600] text-white text-[8px] font-black px-2.5 py-1 rounded-md uppercase tracking-widest shadow-sm">
                {{ product.discount_type ? $t('Sale') : $t('New') }}
            </span>
            <span v-else class="bg-red-500 text-white text-[8px] font-black px-2.5 py-1 rounded-md uppercase tracking-widest shadow-sm">
                {{ $t('Sold Out') }}
            </span>
            
            <!-- Right Badge -->
            <span v-if="product.discount_type" class="bg-green-600 text-white text-[8px] font-black px-2.5 py-1 rounded-md uppercase tracking-widest shadow-sm">
                {{ product.discount_type === 'percentage' ? product.discount_value + '% ' + $t('OFF') : '৳' + product.discount_value + ' ' + $t('OFF') }}
            </span>
        </div>

        <!-- Image — click → detail page -->
        <Link :href="`/products/${product.slug}`" class="block relative w-full bg-slate-50/50 overflow-hidden p-0 border-b border-slate-100/50" style="aspect-ratio:1/1;">
            <img
                v-if="product.image"
                :src="product.image"
                :alt="product.name"
                class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                loading="lazy"
            />
            <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-200">
                <Package class="w-12 h-12" />
                <span class="text-[8px] font-black uppercase tracking-widest mt-2">{{ $t('No Image') }}</span>
            </div>
        </Link>

        <!-- Card Body -->
        <div class="flex flex-col flex-grow p-5">
            <!-- Category & Brand -->
            <div class="flex items-center justify-between mb-2">
                <span v-if="product.category" class="text-[8px] font-black text-slate-400 uppercase tracking-widest">
                    {{ product.category.name }}
                </span>
                <span v-if="product.brand" class="text-[8px] font-black text-[#FF6600] uppercase tracking-widest bg-orange-50/60 px-2 py-0.5 rounded-md border border-orange-100/40">
                    {{ product.brand.name }}
                </span>
            </div>

            <!-- Name — click → detail page -->
            <Link :href="`/products/${product.slug}`" class="flex-grow mb-3 block">
                <h3 class="text-xs font-black text-slate-800 leading-snug line-clamp-2 group-hover:text-[#FF6600] transition-colors duration-300 uppercase tracking-tight font-sans">
                    {{ product.name }}
                </h3>
            </Link>

            <!-- Price -->
            <div class="flex items-baseline gap-2 mb-4">
                <span class="text-sm font-black text-[#FF6600] tracking-tight">
                    ৳{{ parseFloat(discountedPrice).toLocaleString() }}
                </span>
                <span v-if="product.discount_type" class="text-[10px] text-slate-300 line-through font-bold">
                    ৳{{ parseFloat(product.price).toLocaleString() }}
                </span>
            </div>

            <!-- Add to Cart & Buy Now Buttons -->
            <!-- Desktop View -->
            <div class="hidden md:grid grid-cols-2 gap-2 mt-auto">
                <button
                    :disabled="product.stock <= 0"
                    @click.prevent="handleAddToCart"
                    class="py-2.5 px-1 bg-[#003366] text-white hover:bg-[#FF6600] transition-all duration-300 text-[9px] font-black uppercase tracking-wider rounded-lg flex items-center justify-center gap-1 disabled:opacity-40 disabled:cursor-not-allowed font-sans cursor-pointer active:scale-95 shadow-sm"
                >
                    <ShoppingCart class="w-3 h-3" /> {{ $t('Add To Cart') }}
                </button>
                <button
                    :disabled="product.stock <= 0"
                    @click.prevent="handleBuyNow"
                    class="py-2.5 px-1 bg-[#FF6600] text-white hover:bg-[#003366] transition-all duration-300 text-[9px] font-black uppercase tracking-wider rounded-lg flex items-center justify-center gap-1 disabled:opacity-40 disabled:cursor-not-allowed shadow-sm font-sans cursor-pointer active:scale-95"
                >
                    <Zap class="w-3 h-3" /> {{ $t('Buy Now') }}
                </button>
            </div>

            <div class="grid grid-cols-1 md:hidden mt-auto">
                <button
                    :disabled="product.stock <= 0"
                    @click.prevent="handleBuyNow"
                    class="py-3 px-1 bg-[#FF6600] text-white hover:bg-[#003366] transition-all duration-300 text-[10px] font-black uppercase tracking-wider rounded-xl flex items-center justify-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed shadow-md font-sans cursor-pointer active:scale-95"
                >
                    <Zap class="w-4 h-4" /> অর্ডার করুন
                </button>
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