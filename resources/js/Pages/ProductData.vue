<template>
    <StoreLayout>
        <div class="bg-[#F8F9FA] min-h-screen py-6 font-sans">
            <div class="max-w-[1400px] mx-auto px-4">
                
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-8 ml-2">
                    <Link href="/" class="hover:text-[#FF6600]">{{ $t('Home') }}</Link>
                    <ChevronRight class="w-3 h-3" />
                    <Link href="/shop" class="hover:text-[#FF6600]">{{ $t('Products') }}</Link>
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
                                        <span class="text-xl md:text-2xl font-black text-[#FF6600]">৳{{ discountedPrice.toLocaleString() }}</span>
                                        <span v-if="product.discount_type" class="text-sm text-slate-300 line-through font-bold">৳{{ parseFloat(product.price).toLocaleString() }}</span>
                                    </div>
                                    <span v-if="product.discount_type" class="text-[9px] font-black text-white bg-green-500 px-2 py-1 rounded-md uppercase tracking-widest">
                                        {{ product.discount_type === 'percentage' ? product.discount_value + '%' : '৳' + product.discount_value }} OFF
                                    </span>
                                </div>
                            </div>

                            <!-- Brand / Category info -->
                            <div class="flex flex-wrap gap-4 py-4 border-y border-slate-50">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Brand:') }}</span>
                                    <span class="text-[10px] font-black text-[#003366] uppercase tracking-widest bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                                        {{ product.brand || 'Premium' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Category:') }}</span>
                                    <span class="text-[10px] font-black text-[#FF6600] uppercase tracking-widest bg-orange-50 px-2.5 py-1 rounded-lg border border-orange-100">
                                        {{ product.category?.name || 'Organic' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="flex items-center gap-6">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Quantity:') }}</span>
                                <div class="flex items-center bg-slate-50 border border-slate-100 rounded-xl overflow-hidden h-10">
                                    <button @click="quantity > 1 && quantity--" class="px-4 h-full text-[#003366] hover:bg-slate-100 transition-colors"><Minus class="w-3 h-3" /></button>
                                    <input type="number" v-model="quantity" class="w-10 h-full text-center font-black text-xs border-none bg-transparent focus:ring-0 text-[#003366]" />
                                    <button @click="quantity < (product.stock || 99) && quantity++" class="px-4 h-full text-[#003366] hover:bg-slate-100 transition-colors"><Plus class="w-3 h-3" /></button>
                                </div>
                                <span v-if="product.stock <= 5 && product.stock > 0" class="text-[10px] font-black text-red-500 uppercase tracking-widest">
                                    {{ $t('Only') }} {{ product.stock }} {{ $t('left!') }}
                                </span>
                            </div>

                            <!-- Main Action Buttons -->
                            <!-- Desktop View -->
                            <div class="hidden md:grid grid-cols-2 gap-4">
                                <button 
                                    @click="handleAddToCart"
                                    :disabled="product.stock <= 0"
                                    class="h-14 bg-[#003366] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#0f172a] transition-all active:scale-95 flex items-center justify-center gap-3 shadow-lg shadow-blue-900/10 font-sans cursor-pointer"
                                >
                                    <ShoppingCart class="w-4 h-4" /> {{ $t('Add To Cart') }}
                                </button>
                                <button 
                                    @click="handleBuyNow"
                                    :disabled="product.stock <= 0"
                                    class="h-14 bg-[#FF6600] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#e55c00] transition-all active:scale-95 flex items-center justify-center gap-3 shadow-lg shadow-orange-950/15 font-sans cursor-pointer"
                                >
                                    <Zap class="w-4 h-4" /> {{ $t('Buy Now') }}
                                </button>
                            </div>

                            <!-- Mobile View -->
                            <div class="grid grid-cols-1 md:hidden gap-4">
                                <button 
                                    @click="handleBuyNow"
                                    :disabled="product.stock <= 0"
                                    class="h-14 bg-[#FF6600] text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#e55c00] transition-all active:scale-95 flex items-center justify-center gap-4 shadow-xl shadow-orange-950/20 font-sans cursor-pointer"
                                >
                                    <Zap class="w-5 h-5" /> অর্ডার করুন
                                </button>
                            </div>


                            <!-- Admin Remarks / Note -->
                            <div v-if="product.remarks" class="p-4 bg-blue-50 border border-blue-100 rounded-2xl">
                                <p class="text-[9px] font-black text-blue-400 uppercase tracking-[0.2em] mb-1 flex items-center gap-2">
                                    <Info class="w-3 h-3" /> {{ $t('Important Note') }}
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
                            {{ $t('Description') }}
                        </button>
                        <button 
                            @click="activeTab = 'reviews'" 
                            :class="[activeTab === 'reviews' ? 'text-[#FF6600] border-b-2 border-[#FF6600]' : 'text-slate-400 hover:text-slate-600']"
                            class="px-8 py-6 font-black text-[10px] uppercase tracking-widest transition-all"
                        >
                            {{ $t('Customer Reviews') }} ({{ product.reviews ? product.reviews.length : 0 }})
                        </button>
                    </div>

                    <div class="p-8 md:p-12">
                        <div v-if="activeTab === 'description'">
                            <h3 class="text-xs font-black text-[#003366] mb-6 uppercase tracking-widest">{{ $t('Product Details') }}</h3>
                            <div class="prose prose-slate prose-sm max-w-none text-slate-600 font-medium leading-relaxed whitespace-pre-line">
                                {{ product.description }}
                            </div>
                        </div>

                        <div v-if="activeTab === 'reviews'" class="max-w-3xl">
                            <div class="flex items-center gap-8 mb-10 pb-10 border-b border-slate-50">
                                <div class="text-center">
                                    <p class="text-5xl font-black text-slate-900">{{ averageRating }}</p>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">{{ $t('Overall Rating') }}</p>
                                </div>
                                <div class="flex-grow space-y-2">
                                    <div v-for="star in 5" :key="star" class="flex items-center gap-3">
                                        <span class="text-[10px] font-bold text-slate-400 w-2">{{ 6-star }}</span>
                                        <Star class="w-3 h-3 text-[#FF6600] fill-current" />
                                        <div class="flex-grow h-1.5 bg-slate-50 rounded-full overflow-hidden">
                                            <div class="h-full bg-[#FF6600]" :style="{ width: getRatingPercentage(6-star) + '%' }"></div>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-400 w-6">{{ getRatingPercentage(6-star).toFixed(0) }}%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- List of Reviews -->
                            <div v-if="product.reviews && product.reviews.length > 0" class="mb-10 space-y-6">
                                <div v-for="r in product.reviews" :key="r.id" class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">{{ r.customer_name }}</h4>
                                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ new Date(r.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) }}</p>
                                        </div>
                                        <div class="flex gap-0.5">
                                            <Star v-for="star in 5" :key="star" class="w-3 h-3" :class="star <= r.rating ? 'text-[#FF6600] fill-current' : 'text-slate-200'" />
                                        </div>
                                    </div>
                                    <p class="text-xs font-medium text-slate-600 leading-relaxed italic">"{{ r.comment }}"</p>
                                </div>
                            </div>
                            <div v-else class="text-center py-10 mb-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $t('No customer remarks/reviews yet. Be the first to leave a note!') }}</p>
                            </div>

                            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100">
                                <h3 class="text-[10px] font-black text-[#003366] mb-2 uppercase tracking-widest">{{ $t('Submit Your Remark / Review') }}</h3>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-6">{{ $t('No login required — open to all customers') }}</p>
                                
                                <div v-if="reviewSuccessMessage" class="p-4 mb-6 bg-green-50 border border-green-200 text-green-700 text-xs font-black rounded-xl uppercase tracking-wider">
                                    {{ reviewSuccessMessage }}
                                </div>

                                <form @submit.prevent="submitReview" class="space-y-4">
                                    <div>
                                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ $t('Your Name') }}</label>
                                        <input type="text" v-model="reviewForm.customer_name" placeholder="e.g. Anonymous Guest" class="w-full rounded-2xl border-none bg-white px-5 py-4 text-xs font-medium focus:ring-1 focus:ring-[#FF6600]/20 transition-all" />
                                    </div>
                                    <div>
                                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ $t('Rating') }}</label>
                                        <div class="flex gap-2">
                                            <Star 
                                                v-for="star in 5" 
                                                :key="star" 
                                                @click="reviewForm.rating = star"
                                                class="w-6 h-6 cursor-pointer transition-colors" 
                                                :class="star <= reviewForm.rating ? 'text-[#FF6600] fill-current' : 'text-slate-300'"
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ $t('Remarks / Opinion') }}</label>
                                        <textarea rows="4" v-model="reviewForm.comment" required placeholder="Write your remark or opinion here..." class="w-full rounded-2xl border-none bg-white p-4 text-xs font-medium focus:ring-1 focus:ring-[#FF6600]/20 transition-all"></textarea>
                                    </div>
                                    <button 
                                        type="submit" 
                                        :disabled="isSubmittingReview"
                                        class="px-8 py-4 bg-[#003366] text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all disabled:opacity-50"
                                    >
                                        {{ isSubmittingReview ? $t('Submitting...') : $t('Submit Remark') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verified Reviews Showcase Below Tab Section -->
                <div class="bg-white rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] border border-slate-100 p-8 md:p-12 mb-12">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-50">
                        <div>
                            <span class="text-[9px] font-black text-[#FF6600] uppercase tracking-[0.3em] block mb-1">{{ $t('Customer Testimonials') }}</span>
                            <h3 class="text-xl font-black text-[#003366] uppercase tracking-tighter italic">{{ $t('Latest Reviews for this Product') }}</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-black text-slate-800 bg-slate-50 px-3.5 py-1.5 rounded-xl border border-slate-100 flex items-center gap-1.5">
                                <Star class="w-4 h-4 text-[#FF6600] fill-current" />
                                {{ averageRating }} / 5
                            </span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                ({{ product.reviews ? product.reviews.length : 0 }} {{ $t('Verified Reviews') }})
                            </span>
                        </div>
                    </div>

                    <!-- Reviews Showcase List -->
                    <div v-if="product.reviews && product.reviews.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div v-for="r in product.reviews" :key="r.id" class="p-6 bg-slate-50 rounded-3xl border border-slate-100 hover:shadow-md hover:border-[#FF6600]/20 transition-all duration-300 relative group">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">{{ r.customer_name }}</h4>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                        {{ new Date(r.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) }}
                                    </p>
                                </div>
                                <div class="flex gap-0.5 bg-white px-2.5 py-1.5 rounded-xl border border-slate-100">
                                    <Star v-for="star in 5" :key="star" class="w-3 h-3" :class="star <= r.rating ? 'text-[#FF6600] fill-current' : 'text-slate-200'" />
                                </div>
                            </div>
                            <p class="text-[13px] font-bold text-slate-600 leading-relaxed italic">"{{ r.comment }}"</p>
                        </div>
                    </div>
                    <div v-else class="text-center py-12 bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                        <MessageSquare class="w-10 h-10 text-slate-300 mx-auto mb-3" />
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $t('No customer remarks/reviews yet. Be the first to leave a note!') }}</p>
                    </div>
                </div>

                <!-- Related Products -->
                <div>
                    <div class="flex justify-between items-center mb-8 px-2">
                        <h2 class="text-lg font-black text-[#003366] uppercase tracking-tighter italic">{{ $t('Related Products') }}</h2>
                        <Link href="/shop" class="text-[9px] font-black text-[#FF6600] uppercase tracking-widest hover:underline">{{ $t('View More Products →') }}</Link>
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
import { Link, router, usePage } from '@inertiajs/vue3';
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

const reviewForm = ref({
    customer_name: '',
    comment: '',
    rating: 5
});

const isSubmittingReview = ref(false);
const reviewSuccessMessage = ref('');

const averageRating = computed(() => {
    if (!props.product.reviews || props.product.reviews.length === 0) return '0.0';
    const sum = props.product.reviews.reduce((acc, r) => acc + r.rating, 0);
    return (sum / props.product.reviews.length).toFixed(1);
});

const getRatingPercentage = (stars) => {
    if (!props.product.reviews || props.product.reviews.length === 0) return 0;
    const count = props.product.reviews.filter(r => r.rating === stars).length;
    return (count / props.product.reviews.length) * 100;
};

const submitReview = async () => {
    isSubmittingReview.value = true;
    reviewSuccessMessage.value = '';
    
    try {
        await router.post(`/products/${props.product.slug}/reviews`, {
            customer_name: reviewForm.value.customer_name || 'Anonymous Guest',
            comment: reviewForm.value.comment,
            rating: reviewForm.value.rating
        }, {
            preserveScroll: true,
            onSuccess: () => {
                reviewForm.value.customer_name = '';
                reviewForm.value.comment = '';
                reviewForm.value.rating = 5;
                reviewSuccessMessage.value = usePage().props.translations?.['Thank you! Your remark/review has been posted successfully.'] || 'Thank you! Your remark/review has been posted successfully.';
            }
        });
    } catch (e) {
        console.error(e);
    } finally {
        isSubmittingReview.value = false;
    }
};

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

const discountedPrice = computed(() => {
    const price = parseFloat(props.product.price || 0);
    if (!props.product.discount_type) return price;
    if (props.product.discount_type === 'percentage') {
        return price * (1 - parseFloat(props.product.discount_value || 0) / 100);
    }
    return Math.max(0, price - parseFloat(props.product.discount_value || 0));
});

const handleAddToCart = async () => {
    await addToCart(props.product, quantity.value);
};

const handleBuyNow = async () => {
    await addToCart(props.product, quantity.value);
    router.visit('/checkout');
};

const openLightboxMain = () => {
    lightboxProduct.value = { ...props.product, image: activeImage.value };
    showLightbox.value = true;
};
</script>
