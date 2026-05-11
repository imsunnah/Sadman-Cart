<template>
    <div class="group relative bg-white rounded-3xl overflow-hidden border border-slate-100 flex flex-col transition-all duration-500 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-slate-200/50 hover:border-slate-200">

        <!-- Discount badge -->
        <div v-if="product.discount_type" class="absolute top-3 left-3 z-10 bg-[#FF6600] text-white text-[9px] font-black px-2.5 py-1 rounded-full shadow uppercase tracking-wider">
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
            <div class="absolute inset-0 bg-[#003366]/0 group-hover:bg-[#003366]/5 transition-all duration-500 flex items-center justify-center">
                <div class="opacity-0 group-hover:opacity-100 transition-all duration-500 bg-white text-[#003366] text-[9px] font-black px-4 py-2 rounded-full uppercase tracking-widest shadow-lg transform translate-y-2 group-hover:translate-y-0 flex items-center gap-2">
                    <Eye class="w-3 h-3" /> View Details
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
            <Link :href="`/products/${product.slug}`" class="flex-grow mb-3">
                <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-wide leading-snug line-clamp-2 group-hover:text-[#003366] transition-colors">
                    {{ product.name }}
                </h3>
            </Link>

            <!-- Price -->
            <div class="flex items-baseline gap-2 mb-4">
                <span class="text-[17px] font-black text-[#003366] tracking-tighter leading-none">
                    ৳{{ parseFloat(discountedPrice).toLocaleString() }}
                </span>
                <span v-if="product.discount_type" class="text-[10px] text-slate-300 line-through font-bold">
                    ৳{{ parseFloat(product.price).toLocaleString() }}
                </span>
            </div>

            <!-- 2 Action Buttons -->
            <div class="grid grid-cols-2 gap-2">
                <button
                    :disabled="product.stock <= 0"
                    @click.prevent="handleAddToCart"
                    class="h-10 rounded-xl border-2 border-[#003366] text-[#003366] text-[9px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 hover:bg-[#003366] hover:text-white transition-all duration-200 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed"
                >
                    <ShoppingCart class="w-3.5 h-3.5 flex-shrink-0" />
                    Add Cart
                </button>
                <button
                    :disabled="product.stock <= 0"
                    @click.prevent="handleBuyNow"
                    class="h-10 rounded-xl bg-[#FF6600] text-white text-[9px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 hover:bg-orange-600 transition-all duration-200 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed"
                >
                    <Zap class="w-3.5 h-3.5 flex-shrink-0" />
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