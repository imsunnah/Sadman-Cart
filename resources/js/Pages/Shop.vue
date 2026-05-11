<template>
    <StoreLayout>
        <div class="bg-white min-h-screen py-12 font-sans">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-12">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Shop <span class="text-[#FF6600]">Products</span></h1>
                    <p class="text-slate-500 mt-2 font-bold text-sm uppercase tracking-widest">Find your perfect organic selection</p>
                </div>

                <div class="flex flex-col lg:flex-row gap-12">
                    
                    <!-- Sidebar Filters -->
                    <div class="w-full lg:w-72 flex-shrink-0">
                        <div class="sticky top-32 space-y-10">
                            <!-- Category Filter -->
                            <div>
                                <h3 class="text-[10px] font-black text-[#003366] uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-[#FF6600] rounded-full"></span>
                                    Catalog Collections
                                </h3>
                                <ul class="space-y-1">
                                    <li>
                                        <Link href="/shop" :class="[!filters.category && !filters.subcategory ? 'bg-[#003366] text-white shadow-lg shadow-blue-900/10' : 'text-slate-500 hover:bg-slate-50']" class="flex items-center text-[10px] px-4 py-3 rounded-xl transition-all uppercase tracking-[0.2em] font-black italic">
                                            All Entities
                                        </Link>
                                    </li>
                                    <li v-for="category in $page.props.categories" :key="category.id" class="pt-2">
                                        <Link :href="`/shop?category=${category.slug}`" :class="[filters.category === category.slug ? 'text-[#FF6600] font-black' : 'text-slate-600 hover:text-[#003366] font-bold']" class="text-[10px] px-4 py-2 flex items-center justify-between transition-all uppercase tracking-widest group">
                                            {{ category.name }}
                                            <ChevronRight class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" />
                                        </Link>
                                        <!-- Subcategories -->
                                        <ul v-if="category.sub_categories && category.sub_categories.length > 0 && (filters.category === category.slug || category.sub_categories.some(s => s.slug === filters.subcategory))" class="pl-6 space-y-1 mt-2 border-l-2 border-slate-100 ml-4">
                                            <li v-for="child in category.sub_categories" :key="child.id">
                                                <Link :href="`/shop?subcategory=${child.slug}`" :class="[filters.subcategory === child.slug ? 'text-[#FF6600] font-black' : 'text-slate-400 hover:text-[#003366] font-bold']" class="text-[9px] block py-1.5 transition-all uppercase tracking-widest">
                                                    {{ child.name }}
                                                </Link>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            <!-- Trust Info -->
                            <div class="p-6 bg-[#003366]/5 rounded-3xl border border-[#003366]/5">
                                <h4 class="text-[10px] font-black text-[#003366] uppercase tracking-widest mb-4 italic">Premium Quality</h4>
                                <p class="text-[9px] font-bold text-slate-500 leading-relaxed uppercase tracking-tighter">Every item in our catalog is hand-verified for 100% authenticity and freshness.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="flex-grow">
                        <div class="flex justify-between items-center mb-10 pb-6 border-b border-slate-50">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">
                                Inventory Census: <span class="text-[#003366]">{{ products.total }}</span> Items Available
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Sort By:</span>
                                <select class="text-[10px] font-black text-[#003366] uppercase tracking-widest border-none bg-transparent focus:ring-0 cursor-pointer">
                                    <option>Latest Arrival</option>
                                    <option>Price: Low to High</option>
                                    <option>Price: High to Low</option>
                                </select>
                            </div>
                        </div>

                        <div v-if="products.data.length > 0" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-3 gap-6">
                            <ProductCard v-for="product in products.data" :key="product.id" :product="product" @view-image="openLightbox" />
                        </div>

                        <!-- Empty State -->
                        <div v-else class="py-32 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-100 shadow-inner">
                            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                <Package class="w-10 h-10 text-slate-200" />
                            </div>
                            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter italic">No Products Found</h2>
                            <p class="mt-2 text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px]">Your search criteria returned zero entities</p>
                            <Link href="/shop" class="mt-8 inline-block px-8 py-4 bg-[#003366] text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-black transition-all shadow-xl shadow-blue-900/10">Reset Filters</Link>
                        </div>

                        <!-- Pagination -->
                        <div v-if="products.last_page > 1" class="mt-20 flex justify-center space-x-3">
                            <Link v-for="link in products.links" :key="link.label" :href="link.url || '#'" v-html="link.label" 
                                :class="[link.active ? 'bg-[#FF6600] text-white shadow-2xl shadow-orange-500/20 scale-110' : 'bg-white text-slate-400 hover:bg-slate-50 border border-slate-100', !link.url ? 'opacity-30 cursor-not-allowed' : '']"
                                class="w-12 h-12 rounded-2xl flex items-center justify-center text-[10px] font-black transition-all uppercase tracking-widest active:scale-90">
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
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import ImageLightbox from '@/Components/ImageLightbox.vue';
import { Package, ChevronRight } from 'lucide-vue-next';

defineProps({
    products: Object,
    filters: Object
});

const showLightbox = ref(false);
const lightboxProduct = ref(null);

const openLightbox = (product) => {
    lightboxProduct.value = product;
    showLightbox.value = true;
};
</script>
