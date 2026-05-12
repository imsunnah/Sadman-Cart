<template>
    <StoreLayout>
        <div class="bg-[#F8F9FA] min-h-screen py-6 font-sans">
            <div class="max-w-[1400px] mx-auto px-4">
                
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-8 ml-2">
                    <Link href="/" class="hover:text-[#FF6600]">Home</Link>
                    <ChevronRight class="w-3 h-3" />
                    <Link href="/shop" class="hover:text-[#FF6600]">Products</Link>
                    <ChevronRight class="w-3 h-3" />
                    <span class="text-slate-600 truncate max-w-[200px]">{{ product.name }}</span>
                </nav>

                <!-- Main Product Section -->
                <div class="bg-white rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] border border-slate-100 p-6 md:p-12 mb-10">
                    <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-start">
                        
                        <!-- Left: Image Section -->
                        <div class="space-y-6">
                            <!-- Main Active Image -->
                            <div @click="openLightboxMain" class="aspect-square rounded-3xl overflow-hidden bg-slate-50 border border-slate-100 flex items-center justify-center p-6 cursor-zoom-in group relative transition-all duration-500">
                                <img :src="activeImage" :alt="product.name" class="max-w-full max-h-full object-contain transition-transform duration-700 group-hover:scale-105" />
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-xl">
                                        <Maximize2 class="w-6 h-6 text-[#FF6600]" />
                                    </div>
                                </div>
                            </div>

                            <!-- Thumbnails -->
                            <div class="flex flex-wrap gap-3" v-if="allImages.length > 1">
                                <button 
                                    v-for="(img, index) in allImages" 
                                    :key="index"
                                    @click="activeImage = img"
                                    :class="['w-16 md:w-20 aspect-square rounded-xl overflow-hidden border-2 transition-all duration-300', activeImage === img ? 'border-[#FF6600]' : 'border-slate-50 opacity-60 hover:opacity-100']"
                                >
                                    <img :src="img" class="w-full h-full object-cover" />
                                </button>
                            </div>
                        </div>

                        <!-- Right: Product Info -->
                        <div class="mt-8 lg:mt-0 space-y-6">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-black text-slate-900 mb-2 uppercase tracking-tight">{{ product.name }}</h1>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xl md:text-2xl font-black text-[#FF6600]">৳{{ parseFloat(product.price).toLocaleString() }}</span>
                                        <span v-if="product.discount_type" class="text-sm text-slate-300 line-through font-bold">৳{{ calculateOriginalPrice() }}</span>
                                    </div>
                                    <span v-if="product.discount_type" class="text-[9px] font-black text-white bg-green-500 px-2 py-1 rounded-md uppercase tracking-widest">
                                        {{ product.discount_type === 'percentage' ? product.discount_value + '%' : '৳' + product.discount_value }} OFF
                                    </span>
                                </div>
                            </div>

                            <!-- Brand / Category info -->
                            <div class="flex flex-wrap gap-4 py-4 border-y border-slate-50">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Brand:</span>
                                    <span class="text-[10px] font-black text-[#003366] uppercase tracking-widest bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                                        {{ product.brand || 'Premium' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Category:</span>
                                    <span class="text-[10px] font-black text-[#FF6600] uppercase tracking-widest bg-orange-50 px-2.5 py-1 rounded-lg border border-orange-100">
                                        {{ product.category?.name || 'Organic' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="flex items-center gap-6">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Quantity:</span>
                                <div class="flex items-center bg-slate-50 border border-slate-100 rounded-xl overflow-hidden h-10">
                                    <button @click="quantity > 1 && quantity--" class="px-4 h-full text-[#003366] hover:bg-slate-100 transition-colors"><Minus class="w-3 h-3" /></button>
                                    <input type="number" v-model="quantity" class="w-10 h-full text-center font-black text-xs border-none bg-transparent focus:ring-0 text-[#003366]" />
                                    <button @click="quantity < (product.stock || 99) && quantity++" class="px-4 h-full text-[#003366] hover:bg-slate-100 transition-colors"><Plus class="w-3 h-3" /></button>
                                </div>
                                <span v-if="product.stock <= 5 && product.stock > 0" class="text-[10px] font-black text-red-500 uppercase tracking-widest">
                                    Only {{ product.stock }} left!
                                </span>
                            </div>

                            <!-- Main Action Buttons -->
                            <div class="grid grid-cols-2 gap-4">
                                <button 
                                    @click="handleAddToCart"
                                    :disabled="product.stock <= 0"
                                    class="h-14 bg-[#FF6600] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#003366] transition-all active:scale-95 flex items-center justify-center gap-3 shadow-lg shadow-orange-900/10"
                                >
                                    <ShoppingCart class="w-4 h-4" /> Add To Cart
                                </button>
                                <button 
                                    @click="handleBuyNow"
                                    :disabled="product.stock <= 0"
                                    class="h-14 bg-[#003366] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all active:scale-95 flex items-center justify-center gap-3 shadow-lg shadow-blue-900/10"
                                >
                                    <Zap class="w-4 h-4" /> Buy Now
                                </button>
                            </div>

                            <!-- Support Action Buttons -->
                            <div class="grid grid-cols-2 gap-4">
                                <a 
                                    :href="`https://wa.me/${$page.props.settings.footer_phone}?text=Hi, I want to order ${product.name}`"
                                    target="_blank"
                                    class="h-12 bg-[#25D366] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#128C7E] transition-all active:scale-95 flex items-center justify-center gap-3"
                                >
                                    <MessageSquare class="w-4 h-4" /> Order On WhatsApp
                                </a>
                                <a 
                                    :href="`tel:${$page.props.settings.footer_phone}`"
                                    class="h-12 bg-[#003366] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all active:scale-95 flex items-center justify-center gap-3"
                                >
                                    <Phone class="w-4 h-4" /> Call For Order
                                </a>
                            </div>

                            <!-- Admin Remarks / Note -->
                            <div v-if="product.remarks" class="p-4 bg-blue-50 border border-blue-100 rounded-2xl">
                                <p class="text-[9px] font-black text-blue-400 uppercase tracking-[0.2em] mb-1 flex items-center gap-2">
                                    <Info class="w-3 h-3" /> Important Note
                                </p>
                                <p class="text-xs font-bold text-blue-800 italic leading-relaxed">{{ product.remarks }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Section (Description & Reviews) -->
                <div class="bg-white rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden mb-12">
                    <div class="flex border-b border-slate-100">
                        <button 
                            @click="activeTab = 'description'" 
                            :class="[activeTab === 'description' ? 'text-[#FF6600] border-b-2 border-[#FF6600]' : 'text-slate-400 hover:text-slate-600']"
                            class="px-8 py-6 font-black text-[10px] uppercase tracking-widest transition-all"
                        >
                            Description
                        </button>
                        <button 
                            @click="activeTab = 'reviews'" 
                            :class="[activeTab === 'reviews' ? 'text-[#FF6600] border-b-2 border-[#FF6600]' : 'text-slate-400 hover:text-slate-600']"
                            class="px-8 py-6 font-black text-[10px] uppercase tracking-widest transition-all"
                        >
                            Customer Reviews (0)
                        </button>
                    </div>

                    <div class="p-8 md:p-12">
                        <div v-if="activeTab === 'description'">
                            <h3 class="text-xs font-black text-[#003366] mb-6 uppercase tracking-widest">Product Details</h3>
                            <div class="prose prose-slate prose-sm max-w-none text-slate-600 font-medium leading-relaxed whitespace-pre-line">
                                {{ product.description }}
                            </div>
                        </div>

                        <div v-if="activeTab === 'reviews'" class="max-w-3xl">
                            <div class="flex items-center gap-8 mb-10 pb-10 border-b border-slate-50">
                                <div class="text-center">
                                    <p class="text-5xl font-black text-slate-900">0.0</p>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Overall Rating</p>
                                </div>
                                <div class="flex-grow space-y-2">
                                    <div v-for="i in 5" :key="i" class="flex items-center gap-3">
                                        <span class="text-[10px] font-bold text-slate-400 w-2">{{ 6-i }}</span>
                                        <Star class="w-3 h-3 text-slate-200 fill-current" />
                                        <div class="flex-grow h-1.5 bg-slate-50 rounded-full overflow-hidden">
                                            <div class="h-full bg-slate-200" style="width: 0%"></div>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-400 w-6">0%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100">
                                <h3 class="text-[10px] font-black text-[#003366] mb-6 uppercase tracking-widest">Submit Your Review</h3>
                                <form @submit.prevent class="space-y-4">
                                    <div class="flex gap-2 mb-4">
                                        <Star v-for="i in 5" :key="i" class="w-5 h-5 text-slate-300 hover:text-[#FF6600] cursor-pointer transition-colors" />
                                    </div>
                                    <textarea rows="4" placeholder="Write your opinion here..." class="w-full rounded-2xl border-none bg-white p-4 text-xs font-medium focus:ring-1 focus:ring-[#FF6600]/20 transition-all"></textarea>
                                    <button class="px-8 py-3 bg-[#003366] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all">Submit Review</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
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

        <ImageLightbox :show="showLightbox" :product="lightboxProduct" @close="showLightbox = false" />
    </StoreLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import ImageLightbox from '@/Components/ImageLightbox.vue';
import { useCart } from '@/Composables/useCart';
import { Plus, Minus, ShoppingCart, Star, Maximize2, ChevronRight, Zap, MessageSquare, Phone, Info } from 'lucide-vue-next';

const props = defineProps({
    product: Object,
    relatedProducts: Array
});

const { addToCart } = useCart();
const quantity = ref(1);
const activeTab = ref('description');
const showLightbox = ref(false);
const lightboxProduct = ref(null);

const allImages = computed(() => {
    let images = [];
    if (props.product.image) images.push(props.product.image);
    if (props.product.gallery && Array.isArray(props.product.gallery)) {
        props.product.gallery.forEach(img => {
            if (img.image) images.push(img.image);
        });
    }
    return images;
});

const activeImage = ref(null);

// Watch for product changes to reset active image (important for client-side navigation)
watch(() => props.product.id, () => {
    activeImage.value = props.product.image;
    quantity.value = 1;
}, { immediate: true });

const calculateOriginalPrice = () => {
    const price = parseFloat(props.product.price);
    const value = parseFloat(props.product.discount_value);
    if (props.product.discount_type === 'percentage') {
        return Math.round(price / (1 - value / 100));
    }
    return Math.round(price + value);
};

const handleAddToCart = async () => {
    await addToCart(props.product, quantity.value);
};

const handleBuyNow = async () => {
    await addToCart(props.product, quantity.value);
    router.visit('/cart');
};

const openLightboxMain = () => {
    lightboxProduct.value = { ...props.product, image: activeImage.value };
    showLightbox.value = true;
};
</script>
