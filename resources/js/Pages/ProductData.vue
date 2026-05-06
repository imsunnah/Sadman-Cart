<template>
    <StoreLayout>
        <div class="bg-[#F8F9FA] min-h-screen py-8 font-sans">
            <div class="max-w-[1600px] mx-auto px-4">
                
                <!-- Main Product Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 mb-8">
                    <div class="lg:grid lg:grid-cols-12 lg:gap-12 items-start">
                        
                        <!-- Left: Image Gallery -->
                        <div class="lg:col-span-5 space-y-6">
                            <div class="flex gap-4">
                                <!-- Thumbnails -->
                                <div class="w-20 space-y-3 flex-shrink-0" v-if="allImages.length > 1">
                                    <button 
                                        v-for="(img, index) in allImages" 
                                        :key="index"
                                        @click="activeImage = img"
                                        :class="['w-full rounded-xl overflow-hidden aspect-square border-2 transition-all', activeImage === img ? 'border-[#FF6600]' : 'border-slate-100 opacity-60']"
                                    >
                                        <img :src="img" class="w-full h-full object-cover" />
                                    </button>
                                </div>
                                
                                <!-- Active Image -->
                                <div class="flex-grow aspect-square rounded-3xl overflow-hidden bg-white border border-slate-100 flex items-center justify-center p-4">
                                    <img :src="activeImage" :alt="product.name" class="max-w-full max-h-full object-contain mix-blend-multiply" />
                                </div>
                            </div>
                        </div>

                        <!-- Right: Product Info -->
                        <div class="lg:col-span-7 mt-10 lg:mt-0">
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-800 mb-2 italic uppercase tracking-tight">{{ product.name }}</h1>
                            
                            <!-- Pricing Section -->
                            <div class="flex flex-wrap items-center gap-2 sm:space-x-4 mb-6">
                                <span class="text-2xl sm:text-3xl font-black text-[#FF6600]">৳{{ parseFloat(product.price).toLocaleString() }}</span>
                                <span class="text-lg text-slate-400 line-through font-bold">৳{{ (parseFloat(product.price) * 1.2).toFixed(0) }}</span>
                                <span class="bg-[#FF6600] text-white text-[10px] font-black px-2 py-1 rounded">SAVE 20%</span>
                            </div>

                            <!-- Category -->
                            <div class="flex items-center space-x-2 mb-8 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <span>Category:</span>
                                <span class="text-[#003366]">{{ product.category?.name || 'Organic' }}</span>
                            </div>

                            <!-- Quantity Selector -->
                            <div class="flex items-center space-x-4 mb-8">
                                <span class="text-xs font-black text-slate-500 uppercase tracking-widest italic">Quantity:</span>
                                <div class="flex items-center border-2 border-slate-100 rounded-xl overflow-hidden h-12">
                                    <button @click="quantity > 1 && quantity--" class="px-4 h-full bg-slate-50 hover:bg-slate-100 border-r border-slate-100"><Minus class="w-3 h-3" /></button>
                                    <input type="number" v-model="quantity" class="w-16 h-full text-center font-black text-sm border-none focus:ring-0" />
                                    <button @click="quantity < product.stock && quantity++" class="px-4 h-full bg-slate-50 hover:bg-slate-100 border-l border-slate-100"><Plus class="w-3 h-3" /></button>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <button 
                                    @click="handleAddToCart"
                                    :disabled="product.stock <= 0"
                                    class="w-full bg-[#FF6600] text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-orange-600 transition-all disabled:opacity-50 flex items-center justify-center gap-2 shadow-xl shadow-orange-500/20"
                                >
                                    <ShoppingCart class="w-4 h-4" /> Add To Cart
                                </button>
                                <button 
                                    @click="handleBuyNow"
                                    :disabled="product.stock <= 0"
                                    class="w-full bg-[#003366] text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all disabled:opacity-50 flex items-center justify-center gap-2"
                                >
                                    Buy Now
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                                <a 
                                    :href="`https://wa.me/${$page.props.settings.footer_phone}`"
                                    class="w-full bg-[#25D366] text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-green-600 transition-all flex items-center justify-center gap-2"
                                >
                                    <PhoneIncoming class="w-4 h-4" /> WhatsApp Order
                                </a>
                                <a 
                                    :href="`tel:${$page.props.settings.footer_phone}`"
                                    class="w-full bg-[#003366] text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-900 transition-all flex items-center justify-center gap-2"
                                >
                                    <Phone class="w-4 h-4" /> Call To Order
                                </a>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-xl flex items-center justify-center border border-slate-100">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">100% Quality Assurance</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="flex border-b border-slate-100">
                        <button 
                            @click="activeTab = 'description'" 
                            :class="[activeTab === 'description' ? 'bg-[#FF6600] text-white' : 'text-slate-500 hover:bg-slate-50']"
                            class="px-8 py-5 font-black text-xs uppercase tracking-widest transition-all italic"
                        >
                            Description
                        </button>
                        <button 
                            @click="activeTab = 'reviews'" 
                            :class="[activeTab === 'reviews' ? 'bg-[#FF6600] text-white' : 'text-slate-500 hover:bg-slate-50']"
                            class="px-8 py-5 font-black text-xs uppercase tracking-widest transition-all italic"
                        >
                            Reviews (0)
                        </button>
                    </div>

                    <div class="p-5 sm:p-8">
                        <div v-if="activeTab === 'description'" class="prose prose-slate max-w-none">
                            <h3 class="text-sm font-black text-slate-800 mb-4 uppercase tracking-widest">Product Details</h3>
                            <p class="text-slate-600 leading-relaxed font-bold">
                                {{ product.description }}
                            </p>
                        </div>

                        <div v-if="activeTab === 'reviews'" class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <div>
                                <div class="flex items-center gap-4 mb-6">
                                    <span class="text-5xl font-black text-slate-800 italic">0.0</span>
                                    <div>
                                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Average Rating</p>
                                        <div class="flex text-slate-200 mt-1">
                                            <Star v-for="i in 5" :key="i" class="w-4 h-4 fill-current" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-black text-slate-800 mb-6 uppercase tracking-widest italic">Submit Review</h3>
                                <form class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Your Opinion</label>
                                        <textarea rows="4" class="w-full rounded-xl bg-slate-50 border-none focus:ring-[#FF6600] text-sm font-bold"></textarea>
                                    </div>
                                    <div class="flex justify-end">
                                        <button class="bg-[#003366] text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                <div class="mt-12">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-xl font-black text-[#003366] italic border-l-4 border-[#FF6600] pl-4 uppercase tracking-tight">Related Products</h2>
                        <Link href="/shop" class="text-xs font-black text-slate-400 hover:text-[#FF6600] transition-colors uppercase tracking-widest">Discover More →</Link>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                        <ProductCard v-for="p in relatedProducts" :key="p.id" :product="p" />
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { useCart } from '@/Composables/useCart';
import { Plus, Minus, ShoppingCart, PhoneIncoming, Phone, Star } from 'lucide-vue-next';

const props = defineProps({
    product: Object,
    relatedProducts: Array
});

const { addToCart } = useCart();
const quantity = ref(1);
const activeTab = ref('description');

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
    await addToCart(props.product, quantity.value);
};

const handleBuyNow = async () => {
    await addToCart(props.product, quantity.value);
    router.visit('/cart');
};
</script>
