<!-- ComboCard: uniform sizing matching ProductCard width -->
<template>
    <div class="group relative bg-white rounded-3xl overflow-hidden border border-slate-100 flex flex-col transition-all duration-500 hover:-translate-y-1 hover:shadow-2xl hover:shadow-orange-100/60 hover:border-orange-100">
        <!-- Bundle badge -->
        <div class="absolute top-3 left-3 z-10 bg-[#003366] text-white text-[9px] font-black px-2.5 py-1 rounded-full shadow-md uppercase tracking-wider">
            Bundle
        </div>
        <div v-if="combo.original_price" class="absolute top-3 right-3 z-10 bg-[#FF6600] text-white text-[9px] font-black px-2.5 py-1 rounded-full shadow-md uppercase tracking-wider">
            Save ৳{{ (parseFloat(combo.original_price) - parseFloat(combo.price)).toFixed(0) }}
        </div>

        <!-- Image: same aspect-square as ProductCard -->
        <div class="relative w-full aspect-square bg-slate-50 overflow-hidden flex items-center justify-center">
            <img
                v-if="combo.image"
                :src="combo.image"
                :alt="combo.name"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            />
            <div v-else class="flex flex-col items-center gap-2 text-slate-200">
                <Layers class="w-12 h-12" />
            </div>
        </div>

        <!-- Info -->
        <div class="flex flex-col flex-1 p-4 gap-2">
            <span class="text-[9px] font-black text-[#FF6600] uppercase tracking-[0.15em]">Combo Deal</span>
            <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-wide leading-tight line-clamp-2 flex-grow group-hover:text-[#003366] transition-colors">
                {{ combo.name }}
            </h3>

            <!-- Included products as tiny pills -->
            <div class="flex flex-wrap gap-1 my-1">
                <span v-for="p in combo.products.slice(0, 3)" :key="p.id" class="text-[8px] font-black text-slate-400 bg-slate-50 border border-slate-100 px-1.5 py-0.5 rounded-full uppercase tracking-wide">
                    {{ p.name }}
                </span>
                <span v-if="combo.products.length > 3" class="text-[8px] font-black text-[#FF6600] bg-orange-50 border border-orange-100 px-1.5 py-0.5 rounded-full">
                    +{{ combo.products.length - 3 }} more
                </span>
            </div>

            <!-- Price -->
            <div class="flex items-center gap-2 mt-1">
                <span class="text-base font-black text-[#003366] tracking-tighter">
                    ৳{{ parseFloat(combo.price).toLocaleString() }}
                </span>
                <span v-if="combo.original_price" class="text-[10px] text-slate-300 line-through font-bold">
                    ৳{{ parseFloat(combo.original_price).toLocaleString() }}
                </span>
            </div>

            <button
                @click.stop="addToCart(combo)"
                class="mt-2 w-full h-9 rounded-xl bg-[#FF6600] text-white text-[9px] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-orange-600 transition-all duration-300 active:scale-95 shadow-sm"
            >
                <ShoppingCart class="w-3.5 h-3.5" />
                Add Bundle
            </button>
        </div>
    </div>
</template>

<script setup>
import { ShoppingCart, Layers } from 'lucide-vue-next';
import { useCart } from '@/Composables/useCart';

defineProps({ combo: Object });

const { addToCart } = useCart();
</script>
