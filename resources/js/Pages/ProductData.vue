<template>
    <StoreLayout>
        <div class="bg-[#F8F9FA] min-h-screen py-12 font-sans">
            <div class="max-w-[1400px] mx-auto px-4">
                
                <!-- Main Product Section -->
                <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 p-8 sm:p-12 mb-12">
                    <div class="lg:grid lg:grid-cols-12 lg:gap-16 items-start">
                        
                        <!-- Left: Image Section -->
                        <div class="lg:col-span-7 space-y-6">
                            <!-- Main Active Image -->
                            <div @click="openLightboxMain" class="aspect-square rounded-[2.5rem] overflow-hidden bg-slate-50 border border-slate-100 flex items-center justify-center p-8 cursor-zoom-in group relative transition-all duration-500 hover:shadow-2xl hover:border-[#FF6600]/20">
                                <img :src="activeImage" :alt="product.name" class="max-w-full max-h-full object-contain transition-transform duration-700 group-hover:scale-110" />
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center shadow-2xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                                        <Maximize2 class="w-8 h-8 text-[#FF6600]" />
                                    </div>
                                </div>
                            </div>

                            <!-- Thumbnails (Now Below) -->
                            <div class="flex flex-wrap justify-center gap-4" v-if="allImages.length > 1">
                                <button 
                                    v-for="(img, index) in allImages" 
                                    :key="index"
                                    @click="activeImage = img"
                                    :class="['w-20 sm:w-24 aspect-square rounded-2xl overflow-hidden border-2 transition-all duration-300 transform active:scale-90', activeImage === img ? 'border-[#FF6600] shadow-lg shadow-orange-500/20' : 'border-slate-100 opacity-60 hover:opacity-100']"
                                >
                                    <img :src="img" class="w-full h-full object-cover" />
                                </button>
                            </div>
                        </div>

                        <!-- Right: Product Info -->
                        <div class="lg:col-span-5 mt-10 lg:mt-0">
                            <!-- Category Badge -->
                            <div class="inline-flex items-center space-x-2 mb-6 px-4 py-2 bg-[#003366]/5 rounded-full">
                                <span class="text-[10px] font-black text-[#003366] uppercase tracking-[0.2em] italic">Category</span>
                                <span class="w-1 h-1 bg-[#FF6600] rounded-full"></span>
                                <span class="text-[10px] font-black text-[#FF6600] uppercase tracking-[0.2em]">{{ product.category?.name || 'Organic' }}</span>
                            </div>

                            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter leading-tight">{{ product.name }}</h1>
                            
                            <!-- Pricing Section -->
                            <div class="flex items-baseline space-x-6 mb-6 pb-6 border-b border-slate-100">
                                <span class="text-3xl sm:text-4xl font-black text-[#FF6600] drop-shadow-sm">৳{{ parseFloat(product.price).toLocaleString() }}</span>
                                <template v-if="product.discount_type">
                                    <span class="text-lg text-slate-300 line-through font-bold italic">৳{{ (product.discount_type === 'percentage' ? parseFloat(product.price) / (1 - product.discount_value/100) : parseFloat(product.price) + parseFloat(product.discount_value)).toFixed(0) }}</span>
                                    <span class="text-[10px] font-black text-green-500 uppercase tracking-widest bg-green-50 px-3 py-1 rounded-lg">
                                        {{ product.discount_type === 'percentage' ? product.discount_value + '%' : '৳' + product.discount_value }} Discount
                                    </span>
                                </template>
                            </div>

                            <!-- Note / Remarks -->
                            <div v-if="product.remarks" class="mb-8 p-5 bg-amber-50 border-l-4 border-amber-400 rounded-r-2xl">
                                <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1 flex items-center gap-2">
                                    <span>📋</span> Product Note
                                </p>
                                <p class="text-sm font-bold text-amber-800 leading-relaxed">{{ product.remarks }}</p>
                            </div>

                            <!-- Inventory Status (Small text only, no bar) -->
                            <div class="mb-8">
                                <span :class="product.stock <= 5 ? 'text-red-600' : 'text-green-600'" class="text-[10px] font-black uppercase tracking-widest">
                                    {{ product.stock > 0 ? 'Available in Stock' : 'Currently Out of Stock' }}
                                </span>
                            </div>

                            <!-- Quantity Selector -->
                            <div class="flex items-center space-x-6 mb-8">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Select Quantity:</span>
                                <div class="flex items-center bg-white border-2 border-slate-100 rounded-2xl overflow-hidden h-12 shadow-sm">
                                    <button @click="quantity > 1 && quantity--" class="px-4 h-full text-[#003366] hover:bg-slate-50 transition-colors"><Minus class="w-3 h-3" /></button>
                                    <input type="number" v-model="quantity" class="w-12 h-full text-center font-black text-sm border-none focus:ring-0 text-[#003366]" />
                                    <button @click="quantity < product.stock && quantity++" class="px-4 h-full text-[#003366] hover:bg-slate-50 transition-colors"><Plus class="w-3 h-3" /></button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                                <button 
                                    @click="handleAddToCart"
                                    :disabled="product.stock <= 0"
                                    class="group relative w-full h-16 bg-white border-2 border-[#FF6600] text-[#FF6600] rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-[#FF6600] hover:text-white transition-all duration-500 flex items-center justify-center gap-4 overflow-hidden shadow-lg shadow-orange-500/5 active:scale-95"
                                >
                                    <ShoppingCart class="w-5 h-5 transition-transform group-hover:scale-110" /> 
                                    Add To Cart
                                </button>
                                <button 
                                    @click="handleBuyNow"
                                    :disabled="product.stock <= 0"
                                    class="w-full h-16 bg-[#003366] text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-black transition-all duration-500 flex items-center justify-center gap-4 shadow-2xl shadow-blue-900/20 active:scale-95"
                                >
                                    <CheckCircle class="w-5 h-5" />
                                    Buy It Now
                                </button>
                            </div>


                            <!-- Trust Badges -->
                            <div class="mt-12 grid grid-cols-3 gap-4 py-8 border-t border-slate-100">
                                <div class="flex flex-col items-center text-center space-y-2">
                                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center"><Truck class="w-5 h-5 text-slate-400" /></div>
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Fast Shipping</span>
                                </div>
                                <div class="flex flex-col items-center text-center space-y-2">
                                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center"><ShieldCheck class="w-5 h-5 text-slate-400" /></div>
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Secure Payment</span>
                                </div>
                                <div class="flex flex-col items-center text-center space-y-2">
                                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center"><RotateCcw class="w-5 h-5 text-slate-400" /></div>
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">7 Day Return</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Section -->
                <div class="bg-white rounded-[3rem] shadow-xl shadow-slate-200/30 border border-slate-100 overflow-hidden mb-16">
                    <div class="flex border-b border-slate-100 bg-slate-50/50">
                        <button 
                            @click="activeTab = 'description'" 
                            :class="[activeTab === 'description' ? 'bg-white text-[#FF6600] border-b-4 border-[#FF6600]' : 'text-slate-400 hover:text-slate-600']"
                            class="px-12 py-8 font-black text-xs uppercase tracking-[0.2em] transition-all italic"
                        >
                            Detailed Description
                        </button>
                        <button 
                            @click="activeTab = 'reviews'" 
                            :class="[activeTab === 'reviews' ? 'bg-white text-[#FF6600] border-b-4 border-[#FF6600]' : 'text-slate-400 hover:text-slate-600']"
                            class="px-12 py-8 font-black text-xs uppercase tracking-[0.2em] transition-all italic"
                        >
                            Customer Reviews
                        </button>
                    </div>

                    <div class="p-8 sm:p-12">
                        <div v-if="activeTab === 'description'" class="max-w-4xl">
                            <h3 class="text-sm font-black text-[#003366] mb-6 uppercase tracking-[0.3em] italic">Product Specifications</h3>
                            <div class="prose prose-slate prose-sm text-slate-600 leading-relaxed font-bold italic">
                                {{ product.description }}
                            </div>
                        </div>

                        <div v-if="activeTab === 'reviews'" class="grid grid-cols-1 lg:grid-cols-2 gap-20">
                            <div>
                                <div class="flex items-center gap-6 mb-10">
                                    <span class="text-7xl font-black text-slate-900 italic tracking-tighter">0.0</span>
                                    <div>
                                        <p class="text-xs font-black text-[#FF6600] uppercase tracking-[0.2em] mb-2">Verified Rating</p>
                                        <div class="flex text-slate-200">
                                            <Star v-for="i in 5" :key="i" class="w-6 h-6 fill-current" />
                                        </div>
                                    </div>
                                </div>
                                <p class="text-sm font-bold text-slate-400 italic">No reviews yet. Be the first to share your experience with this premium selection.</p>
                            </div>

                            <div class="bg-slate-50 p-10 rounded-[2.5rem] border border-slate-100">
                                <h3 class="text-sm font-black text-[#003366] mb-8 uppercase tracking-[0.2em] italic">Submit Your Feedback</h3>
                                <form @submit.prevent class="space-y-6">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Rating Experience</label>
                                        <div class="flex space-x-3">
                                            <Star v-for="i in 5" :key="i" class="w-6 h-6 text-slate-300 hover:text-[#FF6600] cursor-pointer transition-colors" />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Detailed Opinion</label>
                                        <textarea rows="4" placeholder="Write your thoughts here..." class="w-full rounded-[1.5rem] bg-white border-2 border-white focus:border-[#FF6600]/20 focus:ring-0 text-sm font-bold p-6 transition-all shadow-inner"></textarea>
                                    </div>
                                    <button class="w-full bg-[#003366] text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all shadow-xl shadow-blue-900/10">Publish Review</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                <div class="mt-20">
                    <div class="flex justify-between items-end mb-12 border-l-8 border-[#FF6600] pl-8">
                        <div>
                            <h2 class="text-3xl font-black text-[#003366] uppercase tracking-tighter italic leading-none mb-2">You May Also Like</h2>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Handpicked related collections</p>
                        </div>
                        <Link href="/shop" class="text-[10px] font-black text-[#FF6600] hover:underline uppercase tracking-[0.3em]">Explore Full Catalog →</Link>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <ProductCard v-for="p in relatedProducts" :key="p.id" :product="p" @view-image="openLightbox" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Mobile Buy Bar -->
        <div class="fixed bottom-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-t border-slate-100 p-4 lg:hidden flex items-center gap-4 animate-in slide-in-from-bottom duration-500">
            <div class="flex-grow">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest line-clamp-1">{{ product.name }}</p>
                <p class="text-lg font-black text-[#FF6600]">৳{{ parseFloat(product.price).toLocaleString() }}</p>
            </div>
            <button @click="handleBuyNow" class="flex-grow-[2] h-14 bg-[#FF6600] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-orange-500/20 active:scale-95 transition-all">Buy Now</button>
        </div>

        <ImageLightbox 
            :show="showLightbox" 
            :product="lightboxProduct" 
            @close="showLightbox = false" 
        />
    </StoreLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import ImageLightbox from '@/Components/ImageLightbox.vue';
import { useCart } from '@/Composables/useCart';
import { Plus, Minus, ShoppingCart, PhoneIncoming, Star, Maximize2, Truck, ShieldCheck, RotateCcw, CheckCircle } from 'lucide-vue-next';

const props = defineProps({
    product: Object,
    relatedProducts: Array
});

const { addToCart } = useCart();
const quantity = ref(1);
const activeTab = ref('description');
const showLightbox = ref(false);
const lightboxProduct = ref(null);

const openLightbox = (product) => {
    lightboxProduct.value = product;
    showLightbox.value = true;
};

const openLightboxMain = () => {
    // Create a temporary product object for the lightbox that uses the currently active image
    const tempProduct = { ...props.product, image: activeImage.value };
    openLightbox(tempProduct);
};

const allImages = computed(() => {
    let images = [];
    if (props.product.image) images.push(props.product.image);
    if (props.product.gallery && Array.isArray(props.product.gallery)) {
        props.product.gallery.forEach(img => images.push(img.image));
    }
    return images;
});

const activeImage = ref(allImages.value.length > 0 ? allImages.value[0] : null);

const handleAddToCart = async () => {
    if (quantity.value > props.product.stock) {
        quantity.value = props.product.stock;
    }
    await addToCart(props.product, quantity.value);
};

const handleBuyNow = async () => {
    if (quantity.value > props.product.stock) {
        quantity.value = props.product.stock;
    }
    await addToCart(props.product, quantity.value);
    router.visit('/cart');
};
</script>
