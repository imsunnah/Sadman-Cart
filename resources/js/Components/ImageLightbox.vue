<template>
    <transition 
        enter-active-class="transition duration-500 ease-out" 
        enter-from-class="opacity-0 backdrop-blur-0 scale-95" 
        enter-to-class="opacity-100 backdrop-blur-md scale-100" 
        leave-active-class="transition duration-300 ease-in" 
        leave-from-class="opacity-100 backdrop-blur-md scale-100" 
        leave-to-class="opacity-0 backdrop-blur-0 scale-95"
    >
        <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-xl" @click.self="$emit('close')">
            <!-- Close Button & Action -->
            <div class="absolute top-8 right-8 flex items-center space-x-4 z-[110]">
                <Link :href="`/products/${product?.slug}`" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] transition-all backdrop-blur-md border border-white/10">
                    View Product Details
                </Link>
                <button @click="$emit('close')" class="p-3 bg-white text-[#003366] rounded-full hover:scale-110 transition-all shadow-2xl">
                    <X class="w-6 h-6" />
                </button>
            </div>

            <div class="max-w-5xl w-full flex flex-col items-center">
                <div class="relative group w-full aspect-square md:aspect-video flex items-center justify-center">
                        <img 
                            v-if="product?.image"
                            :src="product.image" 
                            class="max-w-full max-h-[80vh] object-contain drop-shadow-[0_35px_35px_rgba(0,0,0,0.5)] transition-transform duration-700 group-hover:scale-105" 
                        />
                    
                    <!-- Info Overlay (Bottom) -->
                    <div class="absolute bottom-0 left-0 right-0 p-8 text-center translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                        <h3 class="text-4xl font-black text-white uppercase tracking-tighter italic mb-2 drop-shadow-lg">{{ product?.name }}</h3>
                        <div class="flex items-center justify-center space-x-6">
                            <span class="text-[#FF6600] text-3xl font-black">৳{{ parseFloat(product?.price || 0).toLocaleString() }}</span>
                            <div class="h-6 w-[1px] bg-white/20"></div>
                            <span class="text-white/60 text-lg font-bold uppercase tracking-widest">{{ product?.category?.name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { X } from 'lucide-vue-next';

defineProps({
    show: Boolean,
    product: Object
});

defineEmits(['close']);
</script>
