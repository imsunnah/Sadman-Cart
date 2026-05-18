<template>
    <StoreLayout>

        <!-- ─── HERO SECTION ─────────────────────────────────── -->
        <section class="bg-white">
            <div class="max-w-[1550px] mx-auto px-4 pt-6 pb-10">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                    
                    <!-- Left Slider (2 Columns) -->
                    <div class="lg:col-span-2 relative h-[300px] md:h-[520px] overflow-hidden group">
                        <div class="absolute inset-0 flex transition-transform duration-1000 ease-in-out" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                            <div v-for="(img, i) in sliderImages" :key="i" class="min-w-full h-full relative">
                                <img :src="img" class="w-full h-full object-cover" />
                                <div class="absolute inset-0 bg-gradient-to-r from-[#003366]/40 via-transparent to-transparent"></div>
                            </div>
                        </div>

                        <!-- Optional Text Overlay -->
                        <div v-if="$page.props.settings.hero_slider_text_show == '1'" class="absolute inset-0 flex items-center px-10 md:px-20 pointer-events-none">
                            <div class="max-w-xl pointer-events-auto prose prose-invert hero-custom-text" v-html="$page.props.settings.hero_slider_text"></div>
                        </div>

                        <!-- Slider Controls -->
                        <div v-if="sliderImages.length > 1">
                            <button @click="prevSlide" class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md hover:bg-white flex items-center justify-center text-[#003366] transition-all opacity-0 group-hover:opacity-100 active:scale-90 border border-white/20">
                                <ChevronLeft class="w-6 h-6" />
                            </button>
                            <button @click="nextSlide" class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md hover:bg-white flex items-center justify-center text-[#003366] transition-all opacity-0 group-hover:opacity-100 active:scale-90 border border-white/20">
                                <ChevronRight class="w-6 h-6" />
                            </button>
                            <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-3">
                                <button v-for="(_, i) in sliderImages" :key="i" @click="currentSlide = i" :class="currentSlide === i ? 'w-8 bg-[#FF6600]' : 'w-2.5 bg-white/40'" class="h-2.5 rounded-full transition-all duration-300 shadow-sm"></button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Static Image (1 Column) -->
                    <div class="h-[300px] md:h-[520px] overflow-hidden relative group">
                        <img v-if="$page.props.settings.hero_static_image" :src="$page.props.settings.hero_static_image" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                        <div v-else class="w-full h-full bg-slate-100 flex items-center justify-center">
                            <ShoppingBag class="w-12 h-12 text-slate-200" />
                        </div>
                        <!-- Subtle Gradient Overlay for consistency -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#003366]/20 to-transparent"></div>
                    </div>

                </div>
            </div>
        </section>

        <section class="py-16 bg-slate-50 border-y border-slate-100">
            <div class="max-w-[1550px] mx-auto px-4">
                <div class="flex flex-col items-center gap-2 mb-10 text-center">
                    <div class="w-12 h-1.5 bg-[#FF6600] rounded-full mb-2"></div>
                    <h2 class="text-3xl font-black text-[#003366] uppercase tracking-tighter italic">Elite Selections</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Premium Organic Categories</p>
                </div>
                <div class="flex flex-wrap justify-center gap-6 md:gap-12">
                    <Link v-for="cat in featuredCategories" :key="cat.id" :href="`/shop?category=${cat.slug}`" class="group flex flex-col items-center gap-2 min-w-[80px] cursor-pointer">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full border-4 border-white group-hover:border-[#FF6600] overflow-hidden flex items-center justify-center bg-white transition-all duration-300 shadow-md group-hover:shadow-orange-100">
                            <img v-if="cat.image" :src="cat.image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            <span v-else class="text-xl font-black text-[#003366]/30 italic uppercase">{{ cat.name.charAt(0) }}</span>
                        </div>
                        <span class="text-[9px] font-black text-slate-500 group-hover:text-[#003366] uppercase tracking-widest transition-colors text-center leading-tight">{{ cat.name }}</span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ─── COMBO BUNDLES ─────────────────────────────────── -->
        <section v-if="combos.length" class="py-14 bg-white border-b border-slate-100">
            <div class="max-w-[1550px] mx-auto px-4">
                <SectionTitle title="Combo Bundles & Offers" subtitle="Exclusive bundle deals" centered />
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 md:gap-8 justify-center">
                    <ComboCard v-for="combo in combos" :key="combo.id" :combo="combo" />
                </div>
            </div>
        </section>

        <!-- ─── HOT DEALS ─────────────────────────────────────── -->
        <section v-if="discountedProducts.length" class="py-14 bg-slate-50 border-b border-slate-100">
            <div class="max-w-[1550px] mx-auto px-4">
                <SectionTitle title="Hot Flash Deals" subtitle="Limited time discounts" centered />
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 md:gap-8 justify-center">
                    <ProductCard v-for="p in discountedProducts" :key="p.id" :product="p" />
                </div>
            </div>
        </section>

        <!-- ─── LATEST ARRIVALS ───────────────────────────────── -->
        <section class="py-14 bg-white border-b border-slate-100">
            <div class="max-w-[1550px] mx-auto px-4">
                <SectionTitle title="Newly Arrived" subtitle="Fresh in from the farms" centered />
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 md:gap-8 justify-center">
                    <ProductCard v-for="p in latestProducts" :key="p.id" :product="p" />
                </div>
            </div>
        </section>

        <!-- ─── TRUST BADGES ──────────────────────────────────── -->
        <section class="py-16 bg-[#003366]">
            <div class="max-w-[1550px] mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                    <div v-for="b in badges" :key="b.title" class="flex items-center gap-4 p-5 rounded-2xl bg-white/5 border border-white/10">
                        <div :class="b.color" class="w-12 h-12 rounded-xl flex-shrink-0 flex items-center justify-center shadow-lg">
                            <component :is="b.icon" class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-widest italic">{{ b.title }}</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ b.desc }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ─── CATEGORY SECTIONS (All products in grid) ──────── -->
        <template v-for="(section, idx) in categorySections" :key="section.id">
            <section v-if="section.products.length" :class="idx % 2 === 0 ? 'bg-white' : 'bg-slate-50'" class="py-14 border-t border-slate-100">
                <div class="max-w-[1550px] mx-auto px-4">
                    <div class="flex flex-col items-center gap-2 mb-10 text-center">
                        <div class="w-12 h-1.5 bg-[#FF6600] rounded-full mb-2"></div>
                        <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tighter italic leading-none">{{ section.name }}</h2>
                        <Link :href="`/shop?category=${section.slug}`" class="text-[9px] font-black text-[#FF6600] uppercase tracking-[0.3em] flex items-center gap-1 hover:underline transition-all mt-2">
                            View All Collection <ArrowRight class="w-3 h-3" />
                        </Link>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 md:gap-8 justify-center">
                        <ProductCard v-for="p in section.products" :key="p.id" :product="p" />
                    </div>
                </div>
            </section>
        </template>

        <!-- ─── CUSTOMER REVIEWS ──────────────────────────────── -->
        <section class="py-24 bg-white border-t border-slate-100">
            <div class="max-w-[1550px] mx-auto px-4">
                <div class="text-center mb-16">
                    <p class="text-[9px] font-black text-[#FF6600] uppercase tracking-[0.4em] mb-2">Real Experiences</p>
                    <h2 class="text-3xl font-black text-[#003366] uppercase tracking-tighter italic">Customer Reviews</h2>
                    <div class="w-12 h-1 bg-[#FF6600] mx-auto mt-4 rounded-full"></div>
                </div>
                
                <div v-if="reviews && reviews.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="review in reviews.slice(0, 3)" :key="review.id" class="bg-slate-50 border border-slate-100 rounded-[2rem] p-8 relative group hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                        <Quote class="absolute top-6 right-8 w-10 h-10 text-slate-200 opacity-50 group-hover:text-[#FF6600] group-hover:opacity-20 transition-all" />
                        
                        <div class="flex gap-1 mb-6">
                            <Star v-for="i in 5" :key="i" class="w-4 h-4" :class="i <= review.rating ? 'text-[#FF6600] fill-[#FF6600]' : 'text-slate-200'" />
                        </div>
                        
                        <p class="text-[13px] text-slate-600 font-bold italic leading-relaxed mb-8">"{{ review.comment }}"</p>
                        
                        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                            <div class="w-11 h-11 rounded-2xl bg-[#003366] flex items-center justify-center text-white font-black text-sm italic shadow-lg shadow-[#003366]/20">
                                {{ review.customer_name ? review.customer_name.charAt(0).toUpperCase() : 'A' }}
                            </div>
                            <div>
                                <p class="text-[11px] font-black text-[#003366] uppercase tracking-widest">{{ review.customer_name || 'Anonymous' }}</p>
                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Verified Purchase</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-12 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No customer reviews yet. Purchase a product and be the first to leave a review!</p>
                </div>

                <!-- Show More Link -->
                <div v-if="reviews && reviews.length > 3" class="mt-16 text-center">
                    <Link href="/reviews" class="inline-flex flex-col items-center group">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-2 group-hover:text-[#FF6600] transition-colors">See all experiences</span>
                        <div class="flex gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-[#FF6600] transition-all duration-300"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-300 group-hover:bg-[#FF6600] transition-all duration-500"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-400 group-hover:bg-[#FF6600] transition-all duration-700"></div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <ImageLightbox :show="showLightbox" :product="lightboxProduct" @close="showLightbox = false" />
    </StoreLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, defineComponent, h } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { ChevronLeft, ChevronRight, Truck, ShieldCheck, RotateCcw, ShoppingCart, ArrowRight, Quote, Star, ShoppingBag, Leaf } from 'lucide-vue-next';
import ProductCard from '@/Components/ProductCard.vue';
import ComboCard from '@/Components/ComboCard.vue';
import ImageLightbox from '@/Components/ImageLightbox.vue';

// Inline SectionTitle component
const SectionTitle = defineComponent({
    props: { title: String, subtitle: String, centered: Boolean },
    setup(props) {
        return () => h('div', { 
            class: props.centered 
                ? 'flex flex-col items-center gap-2 mb-10 text-center' 
                : 'flex items-center gap-4 mb-8' 
        }, [
            h('div', { class: props.centered ? 'w-12 h-1.5 bg-[#FF6600] rounded-full mb-2' : 'w-1 h-7 bg-[#FF6600] rounded-full flex-shrink-0' }),
            h('div', [
                h('h2', { class: 'text-2xl md:text-3xl font-black text-[#003366] uppercase tracking-tighter italic leading-none' }, props.title),
                h('p', { class: 'text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2' }, props.subtitle),
            ])
        ]);
    }
});

const props = defineProps({
    topSelling:         Array,
    latestProducts:     Array,
    discountedProducts: Array,
    combos:             Array,
    featuredCategories: Array,
    categorySections:   Array,
    reviews:            Array,
});

const page         = usePage();
const sliderImages = ref(JSON.parse(page.props.settings?.slider_images || '[]'));
const currentSlide = ref(0);
const showLightbox = ref(false);
const lightboxProduct = ref(null);

const nextSlide = () => { if (sliderImages.value.length > 0) currentSlide.value = (currentSlide.value + 1) % sliderImages.value.length; };
const prevSlide = () => { if (sliderImages.value.length > 0) currentSlide.value = (currentSlide.value - 1 + sliderImages.value.length) % sliderImages.value.length; };

const badges = [
    { title: 'Fresh & Organic', desc: 'Direct from the farm', icon: Leaf, color: 'bg-green-500' },
    { title: 'Fast Delivery', desc: 'Across the country', icon: Truck, color: 'bg-[#FF6600]' },
    { title: 'Secured Payment', desc: '100% safe checkout', icon: ShieldCheck, color: 'bg-[#003366]' },
    { title: 'Easy Returns', desc: '7 days return policy', icon: RotateCcw, color: 'bg-blue-500' },
];

let autoSlide;
onMounted(() => {
    if (sliderImages.value.length > 1) {
        autoSlide = setInterval(nextSlide, 6000);
    }
});

onUnmounted(() => {
    if (autoSlide) clearInterval(autoSlide);
});
</script>

<style scoped>
.hero-custom-text :deep(h1) {
    font-size: 1.875rem; /* text-3xl */
    font-weight: 900; /* font-black */
    color: white;
    text-transform: uppercase;
    letter-spacing: -0.05em; /* tracking-tighter */
    font-style: italic;
    line-height: 1;
    margin-bottom: 1rem;
}

@media (min-width: 768px) {
    .hero-custom-text :deep(h1) {
        font-size: 3.75rem; /* text-6xl */
    }
}

.hero-custom-text :deep(p) {
    font-size: 0.75rem; /* text-xs */
    font-weight: 700; /* font-bold */
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 0.1em; /* tracking-widest */
    line-height: 1.625;
}

@media (min-width: 768px) {
    .hero-custom-text :deep(p) {
        font-size: 0.875rem; /* text-sm */
    }
}

.hero-custom-text :deep(span) {
    color: #FF6600;
}
</style>