<template>
    <transition
        enter-active-class="transition-opacity duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-500 delay-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="showing" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/80 backdrop-blur-md">
            <div class="relative flex flex-col items-center">
                <!-- Outer Ring -->
                <div class="w-20 h-20 rounded-full border-4 border-slate-100 animate-[spin_3s_linear_infinite]"></div>
                <!-- Inner Ring (Primary Color) -->
                <div class="absolute inset-0 w-20 h-20 rounded-full border-4 border-t-[#003366] border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
                <!-- Center Dot -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-3 h-3 bg-[#FF6600] rounded-full shadow-[0_0_15px_rgba(255,102,0,0.5)] animate-pulse"></div>
                </div>
                
                <!-- Text -->
                <div class="mt-6 flex flex-col items-center gap-1">
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-[#003366]">Sadman Cart</span>
                    <div class="flex gap-1">
                        <div class="w-1 h-1 bg-[#FF6600] rounded-full animate-bounce [animation-delay:-0.3s]"></div>
                        <div class="w-1 h-1 bg-[#FF6600] rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                        <div class="w-1 h-1 bg-[#FF6600] rounded-full animate-bounce"></div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
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
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
