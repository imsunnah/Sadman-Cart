<!-- ProductCarousel: scrollable horizontal carousel with arrow nav, no visible scrollbar -->
<template>
    <div>
        <!-- Section Header -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-4">
                <div class="w-1 h-8 bg-[#FF6600] rounded-full"></div>
                <h2 class="text-xl font-black text-[#003366] uppercase tracking-tight italic">{{ title }}</h2>
            </div>
            <div class="flex gap-2">
                <button @click="scroll('left')"
                    class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-[#003366] hover:bg-[#003366] hover:text-white transition-all duration-200 active:scale-90 shadow-sm">
                    <ChevronLeft class="w-4 h-4" />
                </button>
                <button @click="scroll('right')"
                    class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-[#003366] hover:bg-[#003366] hover:text-white transition-all duration-200 active:scale-90 shadow-sm">
                    <ChevronRight class="w-4 h-4" />
                </button>
            </div>
        </div>

        <!-- Scrollable row -->
        <div
            ref="carousel"
            class="flex gap-5 overflow-x-auto scroll-smooth no-scrollbar"
        >
            <div
                v-for="item in items"
                :key="item.id"
                class="flex-none w-[260px] md:w-[280px]"
            >
                <slot :item="item">
                    <ProductCard :product="item" @view-image="$emit('view-image', $event)" />
                </slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import ProductCard from '@/Components/ProductCard.vue';

defineProps({ title: String, items: Array });
defineEmits(['view-image']);

const carousel = ref(null);
const scroll = (dir) => {
    if (!carousel.value) return;
    // scroll by 3 card widths
    const amount = dir === 'left' ? -700 : 700;
    carousel.value.scrollBy({ left: amount, behavior: 'smooth' });
};
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
