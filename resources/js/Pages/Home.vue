<template>
    <StoreLayout>
        <!-- Hero Slider -->
        <div class="bg-white border-b border-slate-50 font-sans">
            <div class="max-w-[1600px] mx-auto px-4 py-8 flex flex-col lg:flex-row gap-6">
                <!-- Main Slider -->
                <div class="flex-grow relative h-[300px] md:h-[500px] rounded-2xl overflow-hidden shadow-2xl group">
                    <div class="absolute inset-0 flex transition-transform duration-700 ease-in-out" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                        <div v-for="(img, index) in sliderImages" :key="index" class="min-w-full h-full relative">
                            <img :src="img" class="w-full h-full object-cover" />
                            <div class="absolute inset-0 bg-black/10"></div>
                        </div>
                    </div>
                    <!-- Controls -->
                    <button @click="prevSlide" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/50 hover:bg-white p-2 rounded-full transition-all opacity-100 md:opacity-0 md:group-hover:opacity-100">
                        <ChevronLeft class="w-5 h-5 md:w-6 md:h-6 text-[#003366]" />
                    </button>
                    <button @click="nextSlide" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/50 hover:bg-white p-2 rounded-full transition-all opacity-100 md:opacity-0 md:group-hover:opacity-100">
                        <ChevronRight class="w-5 h-5 md:w-6 md:h-6 text-[#003366]" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Rounded Categories -->
        <div class="max-w-[1600px] mx-auto px-4 py-16">
            <h2 class="text-2xl font-black text-[#003366] mb-10 text-center uppercase tracking-widest italic">Browse Categories</h2>
            <div class="flex justify-center flex-wrap gap-8 md:gap-12">
                <Link v-for="category in featuredCategories" :key="category.id" :href="`/shop?category=${category.slug}`" class="group flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full bg-slate-50 border-4 border-slate-50 group-hover:border-[#FF6600] transition-all flex items-center justify-center overflow-hidden shadow-sm">
                        <img :src="category.image || `https://placehold.co/200x200/003366/ffffff?text=${category.name.charAt(0)}`" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    </div>
                    <span class="mt-4 text-xs font-black text-slate-500 group-hover:text-[#003366] uppercase tracking-wide">{{ category.name }}</span>
                </Link>
            </div>
        </div>

        <!-- Top Selling -->
        <div class="bg-slate-100 py-16 border-y border-slate-200">
            <div class="max-w-[1600px] mx-auto px-4">
                <div class="flex justify-between items-center mb-10">
                    <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight italic">Top Selling Products</h2>
                    <Link href="/shop" class="text-sm font-bold text-[#FF6600] hover:underline">View All</Link>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <ProductCard v-for="product in topSelling.slice(0, 4)" :key="product.id" :product="product" layout="horizontal" />
                </div>
            </div>
        </div>

    <!-- Featured Deals Carousel -->
    <div class="py-12 bg-white border-y border-slate-50">
        <div class="max-w-[1600px] mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-black text-[#003366] uppercase tracking-tight italic border-l-4 border-[#FF6600] pl-4">
                    Featured Deals
                </h2>
                <div class="flex space-x-2">
                    <button @click="prevDeal" class="p-2 bg-slate-50 rounded-full hover:bg-slate-100 transition-colors">
                        <ChevronLeft class="w-5 h-5 text-[#003366]" />
                    </button>
                    <button @click="nextDeal" class="p-2 bg-slate-50 rounded-full hover:bg-slate-100 transition-colors">
                        <ChevronRight class="w-5 h-5 text-[#003366]" />
                    </button>
                </div>
            </div>
            
            <div class="relative overflow-hidden mb-10">
                <div 
                    class="flex transition-transform duration-700 ease-in-out gap-6"
                    :style="{ transform: `translateX(-${currentDealPage * 100}%)` }"
                >
                    <div v-for="pageIndex in Math.ceil(justForYou.length / 4)" :key="pageIndex" class="min-w-full grid grid-cols-2 md:grid-cols-4 gap-6">
                        <ProductCard 
                            v-for="product in justForYou.slice((pageIndex - 1) * 4, pageIndex * 4)" 
                            :key="product.id" 
                            :product="product" 
                        />
                    </div>
                </div>
            </div>

            <div class="flex justify-center space-x-3">
                <button 
                    v-for="idx in Math.ceil(justForYou.length / 4)" 
                    :key="idx"
                    @click="currentDealPage = idx - 1"
                    :class="[currentDealPage === idx - 1 ? 'w-8 bg-[#FF6600]' : 'w-2 bg-slate-200 hover:bg-slate-300']"
                    class="h-2 rounded-full transition-all duration-300"
                ></button>
            </div>
        </div>
    </div>

        <!-- Dynamic Categories -->
        <div v-for="(section, idx) in categorySections" :key="section.id" :class="[idx % 2 === 0 ? 'bg-slate-50' : 'bg-white']" class="py-20 border-t border-slate-100">
            <div class="max-w-[1600px] mx-auto px-4">
                <div class="flex justify-between items-center mb-12">
                    <h2 class="text-2xl font-black text-[#003366] uppercase tracking-tight italic">{{ section.name }}</h2>
                    <Link :href="`/shop?category=${section.slug}`" class="text-sm font-bold text-[#FF6600] hover:underline">Explore Category</Link>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    <ProductCard v-for="product in section.products" :key="product.id" :product="product" />
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import ProductCard from '@/Components/ProductCard.vue';

const props = defineProps({
    topSelling: Array,
    justForYou: Array,
    featuredCategories: Array,
    categorySections: Array
});

const page = usePage();
const sliderImages = ref(JSON.parse(page.props.settings.slider_images || '[]'));
const currentSlide = ref(0);
const currentDealPage = ref(0);

const nextDeal = () => {
    const totalPages = Math.ceil(props.justForYou.length / 4);
    currentDealPage.value = (currentDealPage.value + 1) % totalPages;
};

const prevDeal = () => {
    const totalPages = Math.ceil(props.justForYou.length / 4);
    currentDealPage.value = (currentDealPage.value - 1 + totalPages) % totalPages;
};

const nextSlide = () => {
    if (sliderImages.value.length > 0) {
        currentSlide.value = (currentSlide.value + 1) % sliderImages.value.length;
    }
};

const prevSlide = () => {
    if (sliderImages.value.length > 0) {
        currentSlide.value = (currentSlide.value - 1 + sliderImages.value.length) % sliderImages.value.length;
    }
};

let slideInterval;
onMounted(() => {
    if (sliderImages.value.length > 1) {
        slideInterval = setInterval(nextSlide, 5000);
    }
});

onUnmounted(() => {
    clearInterval(slideInterval);
});
</script>