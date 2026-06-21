<template>
    <StoreLayout>
        <div class="bg-[#F8F9FA] min-h-screen py-10 font-sans">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Header Page Title & Breadcrumbs -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight uppercase">
                            {{ activeCategoryName }}
                        </h1>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">
                            {{ $t('Browse through our premium selection') }}
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                        <Link href="/" class="hover:text-[#FF6600]">{{ $t('Home') }}</Link>
                        <span>&gt;</span>
                        <span class="text-slate-600">{{ activeCategoryName }}</span>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-8">
                    
                    <!-- Left Sidebar Filters -->
                    <div class="w-full lg:w-72 flex-shrink-0">
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-8 sticky top-24">
                            
                            <!-- Search In Shop -->
                            <div class="mb-8">
                                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                                    {{ appLocalizer('Search Product', 'পণ্য খুঁজুন') }}
                                </h3>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        v-model="localSearch" 
                                        @input="handleLocalSearchInput"
                                        :placeholder="appLocalizer('Search...', 'খুঁজুন...')"
                                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:bg-white focus:border-[#003366]/20 outline-none transition-all shadow-sm"
                                    >
                                    <Search class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                                </div>
                            </div>

                            <!-- Filter By Category -->
                            <div>
                                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                                    {{ $t('Filter By Category') }}
                                </h3>
                                <ul class="space-y-2">
                                    <li v-for="cat in $page.props.categories" :key="cat.id" class="flex items-center gap-2">
                                        <input 
                                            type="checkbox" 
                                            :id="'cat-' + cat.id" 
                                            :value="cat.slug" 
                                            v-model="selectedCategories"
                                            class="rounded text-[#FF6600] focus:ring-[#FF6600]/20 border-slate-200 w-4 h-4 cursor-pointer"
                                        />
                                        <label :for="'cat-' + cat.id" class="text-xs font-bold text-slate-650 cursor-pointer select-none hover:text-[#FF6600]">
                                            {{ cat.name }}
                                        </label>
                                    </li>
                                </ul>
                            </div>

                            <!-- Price Range Slider -->
                            <div>
                                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                                    {{ $t('Price Range') }}
                                </h3>
                                <div class="space-y-6">
                                    <!-- Double slider bar container -->
                                    <div class="relative w-full h-2 bg-slate-100 rounded-lg mt-4 mb-6">
                                        <!-- Active track color bar between min and max handles -->
                                        <div 
                                            class="absolute h-full bg-[#FF6600] rounded-lg"
                                            :style="{
                                                left: (minPrice / 15000) * 100 + '%',
                                                width: ((maxPrice - minPrice) / 15000) * 100 + '%'
                                            }"
                                        ></div>
                                        
                                        <!-- Min Handle Range slider -->
                                        <input 
                                            type="range" 
                                            min="0" 
                                            max="15000" 
                                            step="100"
                                            v-model.number="minPrice"
                                            @input="handleMinPriceInput"
                                            class="absolute w-full h-1 bg-transparent pointer-events-none appearance-none -top-0.5 left-0 cursor-pointer accent-[#FF6600] [&::-webkit-slider-thumb]:pointer-events-auto [&::-moz-range-thumb]:pointer-events-auto [&::-webkit-slider-runnable-track]:bg-transparent [&::-moz-range-track]:bg-transparent focus:outline-none"
                                        />
                                        
                                        <!-- Max Handle Range slider -->
                                        <input 
                                            type="range" 
                                            min="0" 
                                            max="15000" 
                                            step="100"
                                            v-model.number="maxPrice"
                                            @input="handleMaxPriceInput"
                                            class="absolute w-full h-1 bg-transparent pointer-events-none appearance-none -top-0.5 left-0 cursor-pointer accent-[#FF6600] [&::-webkit-slider-thumb]:pointer-events-auto [&::-moz-range-thumb]:pointer-events-auto [&::-webkit-slider-runnable-track]:bg-transparent [&::-moz-range-track]:bg-transparent focus:outline-none"
                                        />
                                    </div>
                                    
                                    <!-- Text inputs side-by-side -->
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1">
                                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">{{ $t('Min Price') }}</span>
                                            <div class="relative flex items-center">
                                                <span class="absolute left-2 text-[9px] font-bold text-slate-400">Tk</span>
                                                <input 
                                                    type="number" 
                                                    v-model.number="minPrice"
                                                    @input="handleMinPriceInput"
                                                    class="w-full text-[10px] font-black text-slate-700 bg-slate-50 border border-slate-200 rounded-lg pl-6 pr-1 py-1.5 focus:ring-1 focus:ring-[#FF6600]/20 outline-none"
                                                />
                                            </div>
                                        </div>
                                        <span class="text-slate-350 font-bold self-end mb-1.5">-</span>
                                        <div class="flex-1">
                                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">{{ $t('Max Price') }}</span>
                                            <div class="relative flex items-center">
                                                <span class="absolute left-2 text-[9px] font-bold text-slate-400">Tk</span>
                                                <input 
                                                    type="number" 
                                                    v-model.number="maxPrice"
                                                    @input="handleMaxPriceInput"
                                                    class="w-full text-[10px] font-black text-slate-700 bg-slate-50 border border-slate-200 rounded-lg pl-6 pr-1 py-1.5 focus:ring-1 focus:ring-[#FF6600]/20 outline-none"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Brands Filter -->
                            <div v-if="brands && brands.length > 0">
                                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                                    {{ $t('Brands') }}
                                </h3>
                                <ul class="space-y-2">
                                    <li v-for="brand in brands" :key="brand.id" class="flex items-center gap-2">
                                        <input 
                                            type="checkbox" 
                                            :id="'brand-' + brand.slug" 
                                            :value="brand.slug" 
                                            v-model="selectedBrands"
                                            class="rounded text-[#FF6600] focus:ring-[#FF6600]/20 border-slate-200 w-4 h-4 cursor-pointer"
                                        />
                                        <label :for="'brand-' + brand.slug" class="text-xs font-bold text-slate-650 cursor-pointer select-none hover:text-[#FF6600]">
                                            {{ brand.name }}
                                        </label>
                                    </li>
                                </ul>
                            </div>

                            <!-- Product Flags Filter -->
                            <div>
                                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                                    {{ $t('Product Flags') }}
                                </h3>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-2">
                                        <input 
                                            type="checkbox" 
                                            id="flag-best" 
                                            value="best-selling" 
                                            v-model="selectedFlags"
                                            class="rounded text-[#FF6600] focus:ring-[#FF6600]/20 border-slate-200 w-4 h-4 cursor-pointer"
                                        />
                                        <label for="flag-best" class="text-xs font-bold text-slate-650 cursor-pointer select-none hover:text-[#FF6600]">
                                            {{ $t('Best Selling') }}
                                        </label>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <input 
                                            type="checkbox" 
                                            id="flag-new" 
                                            value="new-arrival" 
                                            v-model="selectedFlags"
                                            class="rounded text-[#FF6600] focus:ring-[#FF6600]/20 border-slate-200 w-4 h-4 cursor-pointer"
                                        />
                                        <label for="flag-new" class="text-xs font-bold text-slate-650 cursor-pointer select-none hover:text-[#FF6600]">
                                            {{ $t('New Arrival') }}
                                        </label>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <!-- Right Products Area -->
                    <div class="flex-grow">
                        
                        <!-- Top Sort Bar -->
                        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mb-6 flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $t('Sort By:') }}</span>
                                <select 
                                    v-model="sortBy"
                                    class="text-[10px] font-black text-[#003366] uppercase tracking-widest border border-slate-200 rounded-lg px-3 py-1.5 bg-slate-50 focus:ring-1 focus:ring-[#FF6600]/20 cursor-pointer outline-none"
                                >
                                    <option value="Latest Arrival">{{ $t('Latest Arrival') }}</option>
                                    <option value="Price: Low to High">{{ $t('Price: Low to High') }}</option>
                                    <option value="Price: High to Low">{{ $t('Price: High to Low') }}</option>
                                </select>
                            </div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                {{ $t('Showing') }} <span class="text-[#FF6600]">{{ filteredProducts.length }}</span> {{ $t('products') }}
                            </div>
                        </div>

                        <!-- Products Grid (4 Columns on Desktop) -->
                        <div v-if="filteredProducts.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <ProductCard v-for="product in filteredProducts" :key="product.id" :product="product" @view-image="openLightbox" />
                        </div>

                        <!-- Empty State -->
                        <div v-else class="py-24 text-center bg-white rounded-3xl border border-dashed border-slate-200 shadow-sm">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <Package class="w-8 h-8 text-slate-300" />
                            </div>
                            <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">{{ appLocalizer('Product Not Available', 'এই মুহূর্তে পণ্যটি আমাদের স্টকে নেই') }}</h2>
                            <p class="mt-1 text-slate-400 font-bold uppercase tracking-[0.2em] text-[8px]">{{ appLocalizer('We will collect it for you soon. Stay tuned!', 'আমরা শীঘ্রই এটি সংগ্রহ করব। আমাদের সাথেই থাকুন!') }}</p>
                            <button @click="resetFilters" class="mt-6 px-6 py-3 bg-[#003366] text-white rounded-xl font-black uppercase tracking-widest text-[9px] hover:bg-black transition-all">{{ appLocalizer('Reset Filters', 'ফিল্টার রিসেট করুন') }}</button>
                        </div>

                        <!-- Pagination -->
                        <div v-if="products.last_page > 1" class="mt-16 flex justify-center space-x-2">
                            <Link v-for="link in products.links" :key="link.label" :href="link.url || '#'" v-html="link.label" 
                                :class="[link.active ? 'bg-[#FF6600] text-white shadow-lg' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-100', !link.url ? 'opacity-30 cursor-not-allowed' : '']"
                                class="w-10 h-10 rounded-xl flex items-center justify-center text-[10px] font-black transition-all uppercase tracking-widest active:scale-95">
                            </Link>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <ImageLightbox 
            :show="showLightbox" 
            :product="lightboxProduct" 
            @close="showLightbox = false" 
        />
    </StoreLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import ImageLightbox from '@/Components/ImageLightbox.vue';
import { Package } from 'lucide-vue-next';

const page = usePage();

const props = defineProps({
    products: Object,
    filters: Object,
    categories: Array,
    brands: Array
});

const localSearch = ref(props.filters.search || "");
let localSearchTimeout = null;
const handleLocalSearchInput = () => {
    if (localSearchTimeout) clearTimeout(localSearchTimeout);
    localSearchTimeout = setTimeout(() => {
        triggerSearch();
    }, 500);
};

const showLightbox = ref(false);
const lightboxProduct = ref(null);

// Filters State
const selectedCategories = ref(props.filters.category ? props.filters.category.split(',') : []);
const minPrice = ref(props.filters.min_price ? parseInt(props.filters.min_price) : 0);
const maxPrice = ref(props.filters.max_price ? parseInt(props.filters.max_price) : 15000);
const selectedBrands = ref(props.filters.brand ? props.filters.brand.split(',') : []);
const selectedFlags = ref([]);
const sortBy = ref(props.filters.sort || 'Latest Arrival');

const handleMinPriceInput = () => {
    if (minPrice.value > maxPrice.value - 100) {
        minPrice.value = maxPrice.value - 100;
    }
    if (minPrice.value < 0 || isNaN(minPrice.value)) minPrice.value = 0;
};

const handleMaxPriceInput = () => {
    if (maxPrice.value < minPrice.value + 100) {
        maxPrice.value = minPrice.value + 100;
    }
    if (maxPrice.value > 15000 || isNaN(maxPrice.value)) maxPrice.value = 15000;
};

const activeCategoryName = computed(() => {
    if (props.filters?.search) {
        return `${appLocalizer('Search Results for', 'অনুসন্ধান ফলাফল')}: "${props.filters.search}"`;
    }
    if ((selectedCategories.value || []).length > 0) {
        return selectedCategories.value.map(slug => {
            const found = props.categories?.find(c => c.slug === slug);
            return found ? found.name : slug;
        }).join(', ');
    }
    return appLocalizer('All Products', 'সকল পণ্য');
});

const appLocalizer = (en, bn) => {
    return page.props.locale === 'bn' ? bn : en;
};

const filteredProducts = computed(() => {
    if (!props.products || !props.products.data) return [];
    let result = [...props.products.data];

    // 1. Category Filter
    if (selectedCategories.value.length > 0) {
        result = result.filter(p => selectedCategories.value.includes(p.category?.slug));
    }

    // 2. Price Filter
    result = result.filter(p => {
        const price = parseFloat(p.discounted_price || p.price);
        return price >= minPrice.value && price <= maxPrice.value;
    });

    // 3. Brand Filter
    if (selectedBrands.value.length > 0) {
        result = result.filter(p => selectedBrands.value.includes(p.brand?.slug));
    }

    // 4. Flags Filter
    if (selectedFlags.value.length > 0) {
        result = result.filter(p => {
            let match = false;
            if (selectedFlags.value.includes('best-selling') && p.discount_type) {
                match = true;
            }
            if (selectedFlags.value.includes('new-arrival') && !p.discount_type) {
                match = true;
            }
            return match;
        });
    }

    // 5. Sort By
    if (sortBy.value === 'Price: Low to High') {
        result.sort((a, b) => parseFloat(a.discounted_price) - parseFloat(b.discounted_price));
    } else if (sortBy.value === 'Price: High to Low') {
        result.sort((a, b) => parseFloat(b.discounted_price) - parseFloat(a.discounted_price));
    } else {
        result.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    }

    return result;
});

// Watcher with 300ms Debounce to fetch matching products from backend
let timeoutId = null;

const triggerSearch = () => {
    if (timeoutId) clearTimeout(timeoutId);
    
    timeoutId = setTimeout(() => {
        router.get('/shop', {
            category: selectedCategories.value.join(','),
            min_price: minPrice.value,
            max_price: maxPrice.value,
            sort: sortBy.value,
            brand: selectedBrands.value.join(','),
            search: localSearch.value
        }, {
            preserveState: true,
            preserveScroll: true,
            only: ['products']
        });
    }, 300);
};

watch([selectedCategories, minPrice, maxPrice, sortBy, selectedBrands], triggerSearch);

const resetFilters = () => {
    selectedCategories.value = [];
    minPrice.value = 0;
    maxPrice.value = 15000;
    selectedBrands.value = [];
    selectedFlags.value = [];
    sortBy.value = 'Latest Arrival';
};

const openLightbox = (product) => {
    lightboxProduct.value = product;
    showLightbox.value = true;
};
</script>
