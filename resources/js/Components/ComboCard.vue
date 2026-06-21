<!-- ComboCard: simplified for front page -->
<template>
    <div class="group relative bg-white rounded-[2rem] overflow-hidden border border-slate-100 flex flex-col transition-all duration-700 hover:-translate-y-2 hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] hover:border-orange-100">

        <!-- Image -->
        <Link :href="`/combos/${combo.slug}`" class="relative w-full aspect-square bg-slate-50 overflow-hidden flex items-center justify-center">
            <img
                v-if="combo.image"
                :src="combo.image"
                :alt="combo.name"
                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
            />
            <div v-else class="flex flex-col items-center gap-2 text-slate-200">
                <Layers class="w-16 h-16" />
                <span class="text-[9px] font-black uppercase tracking-widest text-[#FF6600]">{{ $t('No Image') }}</span>
            </div>
            <!-- Hover overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-orange-500/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-700 flex items-center justify-center">
                <div class="bg-white/90 backdrop-blur-md text-[#FF6600] text-[10px] font-black px-6 py-3 rounded-2xl uppercase tracking-[0.2em] shadow-2xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 flex items-center gap-2 border border-white">
                    <Eye class="w-4 h-4" /> {{ $t('View Combo') }}
                </div>
            </div>
        </Link>

        <!-- Info -->
        <div class="flex flex-col flex-1 p-6">
            <div class="flex items-center gap-2 mb-2 flex-wrap">
                <span class="text-[9px] font-black text-white bg-[#003366] px-2 py-0.5 rounded-md uppercase tracking-[0.15em]">{{ $t('Bundle') }}</span>
                <span v-if="combo.original_price" class="text-[9px] font-black text-[#FF6600] bg-orange-50 border border-orange-100 px-2 py-0.5 rounded-md uppercase tracking-[0.15em]">
                    {{ $t('Save') }} ৳{{ (parseFloat(combo.original_price) - parseFloat(combo.price)).toFixed(0) }}
                </span>
            </div>
            <Link :href="`/combos/${combo.slug}`" class="flex-grow mb-4">
                <h3 class="text-sm font-black text-[#003366] uppercase tracking-tight leading-snug line-clamp-2 group-hover:text-[#FF6600] transition-colors duration-300">
                    {{ combo.name }}
                </h3>
            </Link>

            <!-- Price -->
            <div class="flex items-center gap-3 mb-6">
                <span class="text-xl font-black text-[#FF6600] tracking-tighter leading-none drop-shadow-sm">
                    ৳{{ parseFloat(combo.price).toLocaleString() }}
                </span>
                <span v-if="combo.original_price" class="text-[10px] text-slate-300 line-through font-bold">
                    ৳{{ parseFloat(combo.original_price).toLocaleString() }}
                </span>
            </div>

            <!-- Action Buttons -->
            <!-- Desktop View -->
            <div class="hidden md:grid grid-cols-2 gap-2 mt-auto">
                <Link
                    :href="`/combos/${combo.slug}`"
                    class="h-11 rounded-xl bg-[#003366] text-white text-[9px] font-black uppercase tracking-widest flex items-center justify-center hover:bg-[#002244] transition-all duration-300 active:scale-95 shadow-lg shadow-blue-900/10"
                >
                    {{ $t('Details') }}
                </Link>
                <button
                    @click="buyNow"
                    class="h-11 rounded-xl bg-[#FF6600] text-white text-[9px] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-[#003366] transition-all duration-300 shadow-lg shadow-orange-900/10 active:scale-95"
                >
                    <Zap class="w-3 h-3" /> {{ $t('Order Now') }}
                </button>
            </div>

            <!-- Mobile View -->
            <div class="grid grid-cols-1 md:hidden mt-auto">
             
                <button
                    @click="buyNow"
                    class="h-14 w-full bg-[#FF6600] text-white active:bg-[#003366] transition-all duration-300 text-[14px] font-black uppercase tracking-[0.1em] rounded-2xl flex items-center justify-center gap-3 disabled:opacity-40 disabled:cursor-not-allowed shadow-[0_10px_25px_rgba(255,102,0,0.3)] font-sans cursor-pointer active:scale-[0.97]"
                >
                    <Zap class="w-5 h-5 fill-current" /> অর্ডার করুন
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { Layers, Eye, Zap } from 'lucide-vue-next';
import { useCart } from '@/Composables/useCart';
import { usePixel } from '@/Composables/usePixel';

const props = defineProps({ combo: Object });
const { addToCart } = useCart();
const { trackAddToCart } = usePixel();

const buyNow = async () => {
    try {
        trackAddToCart(props.combo, 1);
        await addToCart(props.combo, 1);
        router.visit('/checkout');
    } catch (error) {
        console.error('Error adding combo to cart:', error);
    }
};
</script>
