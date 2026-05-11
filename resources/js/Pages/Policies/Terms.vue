<template>
    <StoreLayout>
        <div class="bg-slate-50 min-h-screen py-20 font-sans">
            <div class="max-w-4xl mx-auto px-4">
                <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 p-12 md:p-20">
                    <div class="inline-flex items-center space-x-2 mb-8 px-4 py-2 bg-[#003366]/5 rounded-full">
                        <span class="text-[10px] font-black text-[#003366] uppercase tracking-[0.2em] italic">Information</span>
                        <span class="w-1 h-1 bg-[#FF6600] rounded-full"></span>
                        <span class="text-[10px] font-black text-[#FF6600] uppercase tracking-[0.2em]">Terms of Service</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-black text-[#003366] mb-12 uppercase italic tracking-tighter leading-none">Terms of Service</h1>

                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 font-bold italic leading-relaxed space-y-8">
                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Acceptance of Terms</h3>
                            <p>By accessing and using Sadman Cart, you agree to comply with and be bound by these terms. If you do not agree, please refrain from using our services.</p>
                        </section>

                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Product Representation</h3>
                            <p>We strive to display products as accurately as possible. However, actual colors and textures may vary depending on your device settings.</p>
                        </section>

                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Governing Law</h3>
                            <p>These terms are governed by the laws of Bangladesh. Any disputes will be subject to the exclusive jurisdiction of the courts in Dhaka.</p>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import StoreLayout from '@/Layouts/StoreLayout.vue';
</script>
