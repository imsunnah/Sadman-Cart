<template>
    <StoreLayout>

        <!-- ─── HERO SLIDER ─────────────────────────────────── -->
        <section class="bg-white">
            <div class="max-w-[1600px] mx-auto px-4 pt-6 pb-10">
                <div class="relative h-[220px] md:h-[480px] rounded-[2rem] overflow-hidden shadow-xl group">
                    <div class="absolute inset-0 flex transition-transform duration-1000 ease-in-out" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                        <div v-for="(img, i) in sliderImages" :key="i" class="min-w-full h-full relative">
                            <img :src="img" class="w-full h-full object-cover" />
                            <div class="absolute inset-0 bg-gradient-to-r from-[#003366]/70 via-[#003366]/20 to-transparent"></div>
                        </div>
                    </div>
                    <div v-if="sliderImages.length" class="absolute inset-0 flex items-center px-8 md:px-20 pointer-events-none">
                        <div class="max-w-xl pointer-events-auto">
                            <p class="text-[9px] font-black text-[#FF6600] uppercase tracking-[0.4em] mb-2">Premium Organic Store</p>
                            <h1 class="text-3xl md:text-6xl font-black text-white uppercase tracking-tighter italic leading-none mb-5">
                                Pure. Fresh. <span class="text-[#FF6600]">Direct.</span>
                            </h1>
                            <Link href="/shop" class="inline-flex items-center gap-2 px-8 py-4 bg-[#FF6600] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-2xl hover:bg-white hover:text-[#003366] transition-all duration-300 active:scale-95">
                                Explore All Products <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>
                    </div>
                    <button @click="prevSlide" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-xl bg-white/30 backdrop-blur hover:bg-white flex items-center justify-center text-[#003366] transition-all opacity-0 group-hover:opacity-100 active:scale-90">
                        <ChevronLeft class="w-5 h-5" />
                    </button>
                    <button @click="nextSlide" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-xl bg-white/30 backdrop-blur hover:bg-white flex items-center justify-center text-[#003366] transition-all opacity-0 group-hover:opacity-100 active:scale-90">
                        <ChevronRight class="w-5 h-5" />
                    </button>
                    <div v-if="sliderImages.length > 1" class="absolute bottom-3 left-0 right-0 flex justify-center gap-2">
                        <button v-for="(_, i) in sliderImages" :key="i" @click="currentSlide = i" :class="currentSlide === i ? 'w-5 bg-[#FF6600]' : 'w-2 bg-white/50'" class="h-2 rounded-full transition-all duration-300"></button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ─── ELITE SELECTIONS (Categories) ────────────────── -->
        <section class="py-12 bg-slate-50 border-y border-slate-100">
            <div class="max-w-[1600px] mx-auto px-4">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-1 h-7 bg-[#FF6600] rounded-full"></div>
                    <h2 class="text-xl font-black text-[#003366] uppercase tracking-tight italic">Elite Selections</h2>
                </div>
                <div class="flex flex-wrap justify-start gap-6 md:gap-8">
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
            <div class="max-w-[1600px] mx-auto px-4">
                <SectionTitle title="Combo Bundles & Offers" subtitle="Exclusive bundle deals" />
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-5">
                    <ComboCard v-for="combo in combos" :key="combo.id" :combo="combo" />
                </div>
            </div>
        </section>

        <!-- ─── HOT DEALS ─────────────────────────────────────── -->
        <section v-if="discountedProducts.length" class="py-14 bg-slate-50 border-b border-slate-100">
            <div class="max-w-[1600px] mx-auto px-4">
                <SectionTitle title="Hot Flash Deals" subtitle="Limited time discounts" />
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-5">
                    <ProductCard v-for="p in discountedProducts" :key="p.id" :product="p" />
                </div>
            </div>
        </section>

        <!-- ─── LATEST ARRIVALS ───────────────────────────────── -->
        <section class="py-14 bg-white border-b border-slate-100">
            <div class="max-w-[1600px] mx-auto px-4">
                <SectionTitle title="Newly Arrived" subtitle="Fresh in from the farms" />
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-5">
                    <ProductCard v-for="p in latestProducts" :key="p.id" :product="p" />
                </div>
            </div>
        </section>

        <!-- ─── TRUST BADGES ──────────────────────────────────── -->
        <section class="py-16 bg-[#003366]">
            <div class="max-w-[1600px] mx-auto px-4">
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
                <div class="max-w-[1600px] mx-auto px-4">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-1 h-7 bg-[#FF6600] rounded-full"></div>
                            <h2 class="text-xl font-black text-[#003366] uppercase tracking-tight italic">{{ section.name }}</h2>
                        </div>
                        <Link :href="`/shop?category=${section.slug}`" class="text-[9px] font-black text-[#003366] uppercase tracking-widest flex items-center gap-1 hover:text-[#FF6600] transition-colors">
                            View All <ArrowRight class="w-3 h-3" />
                        </Link>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-5">
                        <ProductCard v-for="p in section.products" :key="p.id" :product="p" />
                    </div>
                </div>
            </section>
        </template>

        <!-- ─── CUSTOMER REVIEWS ──────────────────────────────── -->
        <section v-if="reviews && reviews.length" class="py-20 bg-[#003366]">
            <div class="max-w-[1600px] mx-auto px-4">
                <div class="text-center mb-12">
                    <p class="text-[9px] font-black text-[#FF6600] uppercase tracking-[0.4em] mb-2">Real Experiences</p>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter italic">Customer Reviews</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div v-for="review in reviews" :key="review.id" class="bg-white/5 border border-white/10 rounded-3xl p-7 relative group hover:bg-white/10 transition-all duration-300">
                        <Quote class="absolute top-5 right-5 w-7 h-7 text-white/10" />
                        <div class="flex gap-1 mb-4">
                            <Star v-for="i in 5" :key="i" class="w-4 h-4" :class="i <= review.rating ? 'text-[#FF6600] fill-[#FF6600]' : 'text-white/20'" />
                        </div>
                        <p class="text-sm text-slate-300 font-bold italic leading-relaxed mb-5">"{{ review.comment }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#FF6600]/20 border border-[#FF6600]/30 flex items-center justify-center text-[#FF6600] font-black text-sm italic">
                                {{ review.customer_name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-white uppercase tracking-widest">{{ review.customer_name }}</p>
                                <p class="text-[9px] text-slate-500 uppercase tracking-widest">Verified Customer</p>
                            </div>
                        </div>
                    </div>
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
import { ChevronLeft, ChevronRight, Truck, ShieldCheck, RotateCcw, ShoppingCart, ArrowRight, Quote, Star } from 'lucide-vue-next';
import ProductCard from '@/Components/ProductCard.vue';
import ComboCard from '@/Components/ComboCard.vue';
import ImageLightbox from '@/Components/ImageLightbox.vue';

// Inline SectionTitle component
const SectionTitle = defineComponent({
    props: { title: String, subtitle: String },
    setup(props) {
        return () => h('div', { class: 'flex items-center gap-4 mb-8' }, [
            h('div', { class: 'w-1 h-7 bg-[#FF6600] rounded-full flex-shrink-0' }),
            h('div', [
                h('h2', { class: 'text-xl font-black text-[#003366] uppercase tracking-tight italic leading-none' }, props.title),
                h('p', { class: 'text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1' }, props.subtitle),
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
    { icon: Truck,        color: 'bg-[#FF6600]', title: 'Fast Delivery',    desc: '48hr nationwide' },
    { icon: ShieldCheck,  color: 'bg-[#0099FF]', title: 'Certified Organic', desc: 'Verified farms only' },
    { icon: RotateCcw,    color: 'bg-[#FF3366]', title: 'Easy Returns',     desc: '7-day policy' },
    { icon: ShoppingCart, color: 'bg-[#00CC66]', title: 'Cash on Delivery', desc: 'Safe payments' },
];

let autoSlide;
onMounted(() => { if (sliderImages.value.length > 1) autoSlide = setInterval(nextSlide, 6000); });
onUnmounted(() => clearInterval(autoSlide));
</script>