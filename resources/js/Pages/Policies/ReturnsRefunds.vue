<template>
    <StoreLayout>
        <div class="bg-slate-50 min-h-screen py-20 font-sans">
            <div class="max-w-4xl mx-auto px-4">
                <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 p-12 md:p-20">
                    <div class="inline-flex items-center space-x-2 mb-8 px-4 py-2 bg-[#003366]/5 rounded-full">
                        <span class="text-[10px] font-black text-[#003366] uppercase tracking-[0.2em] italic">Information</span>
                        <span class="w-1 h-1 bg-[#FF6600] rounded-full"></span>
                        <span class="text-[10px] font-black text-[#FF6600] uppercase tracking-[0.2em]">Returns & Refunds</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-black text-[#003366] mb-12 uppercase italic tracking-tighter leading-none">Returns Policy</h1>

                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 font-bold italic leading-relaxed space-y-8">
                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">7-Day Return Policy</h3>
                            <p>We want you to be completely satisfied with your purchase. If you receive a product that is damaged, defective, or not as described, you can return it within 7 days of delivery.</p>
                        </section>

                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Conditions for Return</h3>
                            <ul class="list-disc pl-6 space-y-2">
                                <li>The product must be unused and in the same condition as received.</li>
                                <li>Original packaging and tags must be intact.</li>
                                <li>Perishable items (like fresh vegetables) must be reported within 24 hours.</li>
                            </ul>
                        </section>

                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Refund Process</h3>
                            <p>Once we receive and inspect your return, we will process your refund. Refunds will be issued to the original payment method or as store credit, depending on your preference.</p>
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
