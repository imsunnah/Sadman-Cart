<template>
    <transition
        enter-active-class="transition-opacity duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-500 delay-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="showing" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/60 backdrop-blur-2xl">
            <div class="relative flex flex-col items-center p-12 rounded-[3rem]">
                <!-- Smart Spinner Evolution -->
                <div class="relative w-24 h-24">
                    <!-- Outer Halo -->
                    <div class="absolute inset-0 rounded-full border-[3px] border-slate-100/50"></div>
                    
                    <!-- Dynamic Progress Ring -->
                    <div class="absolute inset-0 rounded-full border-[3px] border-t-[#003366] border-r-transparent border-b-transparent border-l-transparent animate-[spin_1.2s_cubic-bezier(0.76,0,0.24,1)_infinite]"></div>
                    
                    <!-- Pulsing Core -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-10 h-10 bg-white shadow-[0_10px_30px_rgba(0,0,0,0.08)] rounded-2xl border border-slate-50 flex items-center justify-center">
                            <ShoppingCart class="w-5 h-5 text-[#FF6600] animate-bounce-subtle" />
                        </div>
                    </div>

                    <!-- Orbiting Particle -->
                    <div class="absolute inset-0 animate-[spin_4s_linear_infinite]">
                        <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-[#FF6600] rounded-full shadow-[0_0_15px_rgba(255,102,0,0.6)]"></div>
                    </div>
                </div>
                
                <!-- Status Text -->
                <div class="mt-10 flex flex-col items-center gap-2">
                    <span class="text-[11px] font-black uppercase tracking-[0.4em] text-[#003366] opacity-40 animate-pulse">Sadman Cart</span>
                    <div class="flex gap-2">
                        <div class="w-1.5 h-1.5 bg-[#FF6600] rounded-full animate-bounce [animation-delay:-0.3s] shadow-[0_4px_10px_rgba(255,102,0,0.3)]"></div>
                        <div class="w-1.5 h-1.5 bg-[#FF6600] rounded-full animate-bounce [animation-delay:-0.15s] shadow-[0_4px_10px_rgba(255,102,0,0.3)]"></div>
                        <div class="w-1.5 h-1.5 bg-[#FF6600] rounded-full animate-bounce shadow-[0_4px_10px_rgba(255,102,0,0.3)]"></div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ShoppingCart } from 'lucide-vue-next';
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

const showing = ref(false);
let timeout = null;

const start = () => {
    timeout = setTimeout(() => {
        showing.value = true;
    }, 150); // Small delay to avoid flicker on fast loads
};

const finish = () => {
    if (timeout) clearTimeout(timeout);
    showing.value = false;
};

onMounted(() => {
    router.on('start', start);
    router.on('finish', finish);
    router.on('error', finish);
});

onUnmounted(() => {
    // Clean up if component unmounts (though usually global)
    finish();
});
</script>

<style scoped>
@keyframes bounce-subtle {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
.animate-bounce-subtle {
    animation: bounce-subtle 2s infinite ease-in-out;
}
</style>
