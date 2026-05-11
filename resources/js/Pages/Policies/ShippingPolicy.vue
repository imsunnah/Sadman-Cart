<template>
    <StoreLayout>
        <div class="bg-slate-50 min-h-screen py-20 font-sans">
            <div class="max-w-4xl mx-auto px-4">
                <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 p-12 md:p-20">
                    <div class="inline-flex items-center space-x-2 mb-8 px-4 py-2 bg-[#003366]/5 rounded-full">
                        <span class="text-[10px] font-black text-[#003366] uppercase tracking-[0.2em] italic">Information</span>
                        <span class="w-1 h-1 bg-[#FF6600] rounded-full"></span>
                        <span class="text-[10px] font-black text-[#FF6600] uppercase tracking-[0.2em]">Shipping Policy</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-black text-[#003366] mb-12 uppercase italic tracking-tighter leading-none">Shipping & Delivery</h1>

                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 font-bold italic leading-relaxed space-y-8">
                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Delivery Timeline</h3>
                            <p>At Sadman Cart, we prioritize the freshness and quality of our products. Orders within Dhaka city are typically delivered within 24-48 hours. For orders outside Dhaka, please allow 3-5 business days for delivery.</p>
                        </section>

                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Shipping Charges</h3>
                            <p>Our standard shipping rates are as follows:</p>
                            <ul class="list-disc pl-6 space-y-2">
                                <li>Inside Dhaka: ৳60</li>
                                <li>Outside Dhaka: ৳120</li>
                            </ul>
                            <p>Charges may vary based on promotional offers or bulk order weights.</p>
                        </section>

                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Tracking Your Order</h3>
                            <p>Once your order is processed, you will receive a confirmation call from our team. You can also track your order status by contacting our customer support via WhatsApp or Phone.</p>
                        </section>

                        <section>
                            <h3 class="text-[#003366] uppercase tracking-widest text-sm mb-4">Damaged Goods</h3>
                            <p>If you receive a damaged or incomplete order, please report it to our delivery agent immediately or contact us within 24 hours of delivery. We will initiate a replacement or refund as per our Returns Policy.</p>
                        </section>
                    </div>

                    <div class="mt-20 pt-12 border-t border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Need more help?</p>
                        <div class="flex flex-wrap gap-6">
                            <a :href="`tel:${$page.props.settings.footer_phone}`" class="flex items-center gap-3 text-sm font-black text-[#003366] hover:text-[#FF6600] transition-colors">
                                <Phone class="w-5 h-5 text-[#FF6600]" /> Call Us
                            </a>
                            <a :href="`https://wa.me/${$page.props.settings.footer_phone}`" class="flex items-center gap-3 text-sm font-black text-[#003366] hover:text-[#FF6600] transition-colors">
                                <MessageCircle class="w-5 h-5 text-[#25D366]" /> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { Phone, MessageCircle } from 'lucide-vue-next';
</script>
