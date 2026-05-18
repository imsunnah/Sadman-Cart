<template>
    <StoreLayout>
        <div class="bg-[#F8F9FA] min-h-screen py-6 font-sans">
            <div class="max-w-[1400px] mx-auto px-4">
                
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-8 ml-2">
                    <Link href="/" class="hover:text-[#FF6600]">Home</Link>
                    <ChevronRight class="w-3 h-3" />
                    <span class="text-slate-600 truncate max-w-[200px]">{{ combo.name }}</span>
                </nav>

                <!-- Main Section -->
                <div class="bg-white rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] border border-slate-100 p-6 md:p-12 mb-10">
                    <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-start">
                        
                        <!-- Left: Image Section -->
                        <div class="space-y-6">
                            <div class="aspect-square rounded-3xl overflow-hidden bg-slate-50 border border-slate-100 flex items-center justify-center p-6 group relative transition-all duration-500">
                                <img :src="combo.image" :alt="combo.name" class="max-w-full max-h-full object-contain transition-transform duration-700 group-hover:scale-105" />
                            </div>

                            <!-- Included Products -->
                            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <Package class="w-3 h-3" /> Included in this Bundle
                                </h3>
                                <div class="space-y-3">
                                    <div v-for="p in combo.products" :key="p.id" class="flex items-center gap-3 p-2 bg-white rounded-xl border border-slate-100 group hover:border-[#FF6600]/20 transition-all">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-50 flex-shrink-0">
                                            <img :src="p.image" class="w-full h-full object-cover" />
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <p class="text-[10px] font-black text-[#003366] uppercase truncate">{{ p.name }}</p>
                                        </div>
                                        <Link :href="`/products/${p.slug}`" class="text-slate-300 hover:text-[#FF6600] transition-colors">
                                            <ExternalLink class="w-3 h-3" />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Info -->
                        <div class="mt-8 lg:mt-0 space-y-6">
                            <div>
                                <span class="inline-block text-[9px] font-black text-white bg-[#003366] px-2 py-1 rounded-md uppercase tracking-[0.2em] mb-4">Combo Bundle</span>
                                <h1 class="text-2xl md:text-3xl font-black text-slate-900 mb-2 uppercase tracking-tight">{{ combo.name }}</h1>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xl md:text-2xl font-black text-[#FF6600]">৳{{ parseFloat(combo.price).toLocaleString() }}</span>
                                        <span v-if="combo.original_price" class="text-sm text-slate-300 line-through font-bold">৳{{ parseFloat(combo.original_price).toLocaleString() }}</span>
                                    </div>
                                    <span v-if="combo.original_price" class="text-[9px] font-black text-white bg-green-500 px-2 py-1 rounded-md uppercase tracking-widest">
                                        Save ৳{{ (parseFloat(combo.original_price) - parseFloat(combo.price)).toFixed(0) }}
                                    </span>
                                </div>
                            </div>

                            <div class="prose prose-slate prose-sm text-slate-600 font-medium leading-relaxed italic">
                                {{ combo.description }}
                            </div>

                            <!-- Quantity -->
                            <div class="flex items-center gap-6 pt-4 border-t border-slate-50">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Quantity:</span>
                                <div class="flex items-center bg-slate-50 border border-slate-100 rounded-xl overflow-hidden h-10">
                                    <button @click="quantity > 1 && quantity--" class="px-4 h-full text-[#003366] hover:bg-slate-100 transition-colors"><Minus class="w-3 h-3" /></button>
                                    <input type="number" v-model="quantity" class="w-10 h-full text-center font-black text-xs border-none bg-transparent focus:ring-0 text-[#003366]" />
                                    <button @click="quantity++" class="px-4 h-full text-[#003366] hover:bg-slate-100 transition-colors"><Plus class="w-3 h-3" /></button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="grid grid-cols-2 gap-4">
                                <button 
                                    @click="handleAddToCart"
                                    class="h-14 bg-[#FF6600] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#003366] transition-all active:scale-95 flex items-center justify-center gap-3 shadow-lg shadow-orange-900/10"
                                >
                                    <ShoppingCart class="w-4 h-4" /> Add Bundle
                                </button>
                                <button 
                                    @click="handleBuyNow"
                                    class="h-14 bg-[#003366] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all active:scale-95 flex items-center justify-center gap-3 shadow-lg shadow-blue-900/10"
                                >
                                    <Zap class="w-4 h-4" /> Buy Now
                                </button>
                            </div>

                            <!-- Support Action Buttons -->
                            <div class="w-full">
                                <a 
                                    :href="`https://wa.me/${$page.props.settings.footer_phone}?text=Hi, I want to order ${combo.name}`"
                                    target="_blank"
                                    class="h-12 bg-[#25D366] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#128C7E] transition-all active:scale-95 flex items-center justify-center gap-3 w-full"
                                >
                                    <MessageSquare class="w-4 h-4" /> Order On WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related -->
                <div>
                    <div class="flex justify-between items-center mb-8 px-2">
                        <h2 class="text-lg font-black text-[#003366] uppercase tracking-tighter italic">Related Products</h2>
                        <Link href="/shop" class="text-[9px] font-black text-[#FF6600] uppercase tracking-widest hover:underline">View More Products →</Link>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        <ProductCard v-for="p in relatedProducts" :key="p.id" :product="p" />
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { useCart } from '@/Composables/useCart';
import { Plus, Minus, ShoppingCart, ChevronRight, Zap, MessageSquare, Phone, Package, ExternalLink } from 'lucide-vue-next';

const props = defineProps({
    combo: Object,
    relatedProducts: Array
});

const { addToCart } = useCart();
const quantity = ref(1);

const handleAddToCart = async () => {
    await addToCart(props.combo, quantity.value);
};

const handleBuyNow = async () => {
    await addToCart(props.combo, quantity.value);
    router.visit('/checkout');
};
</script>
