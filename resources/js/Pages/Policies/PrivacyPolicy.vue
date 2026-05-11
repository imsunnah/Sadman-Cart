<template>
    <StoreLayout>
        <div class="bg-slate-50 min-h-screen py-20 font-sans">
            <div class="max-w-4xl mx-auto px-4">
                <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 p-12 md:p-20">
                    <div class="inline-flex items-center space-x-2 mb-8 px-4 py-2 bg-[#003366]/5 rounded-full">
                        <span class="text-[10px] font-black text-[#003366] uppercase tracking-[0.2em] italic">Information</span>
                        <span class="w-1 h-1 bg-[#FF6600] rounded-full"></span>
                        <span class="text-[10px] font-black text-[#FF6600] uppercase tracking-[0.2em]">Privacy Policy</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-black text-[#003366] mb-12 uppercase italic tracking-tighter leading-none">Privacy Matters</h1>

                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 font-bold italic leading-relaxed space-y-8">
                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Data Collection</h3>
                            <p>We collect essential information to process your orders, including your name, shipping address, phone number, and email. This data is used solely for order fulfillment and improving your shopping experience.</p>
                        </section>

                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Security</h3>
                            <p>Your data is protected using industry-standard security measures. We do not sell or share your personal information with third parties for marketing purposes.</p>
                        </section>

                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Cookies</h3>
                            <p>Our website uses cookies to enhance functionality and remember your cart items. You can manage cookie settings in your browser at any time.</p>
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
