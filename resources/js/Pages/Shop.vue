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
                    <div class="w-full lg:w-64 flex-shrink-0">
                        <div class="sticky top-32">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 border-b pb-2">Catalog Filter</h3>
                            <ul class="space-y-2">
                                <li>
                                    <Link href="/shop" :class="[!filters.category && !filters.subcategory ? 'text-[#FF6600] font-black' : 'text-slate-600 hover:text-[#003366] font-bold']" class="flex items-center text-xs py-2 transition-all uppercase tracking-widest">
                                        All Products
                                    </Link>
                                </li>
                                <li v-for="category in $page.props.categories" :key="category.id" class="space-y-1">
                                    <div class="flex items-center justify-between py-2 group">
                                        <Link :href="`/shop?category=${category.slug}`" :class="[filters.category === category.slug ? 'text-[#FF6600] font-black underline underline-offset-4' : 'text-slate-600 hover:text-[#003366] font-bold']" class="text-xs transition-all uppercase tracking-widest">
                                            {{ category.name }}
                                        </Link>
                                    </div>
                                    <!-- Subcategories -->
                                    <ul v-if="category.sub_categories && category.sub_categories.length > 0" class="pl-4 space-y-1 border-l-2 border-slate-100 ml-1">
                                        <li v-for="child in category.sub_categories" :key="child.id">
                                            <Link :href="`/shop?subcategory=${child.slug}`" :class="[filters.subcategory === child.slug ? 'text-[#FF6600] font-black' : 'text-slate-500 hover:text-[#003366] font-bold']" class="text-[10px] block py-1 transition-all uppercase tracking-widest">
                                                {{ child.name }}
                                            </Link>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="flex-grow">
                        <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-100">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Showing <span class="text-slate-900">{{ products.total }}</span> Product Entities
                            </div>
                        </div>

                        <div v-if="products.data.length > 0" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                            <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
                        </div>

                        <!-- Empty State -->
                        <div v-else class="py-20 text-center border-2 border-dashed border-slate-100 rounded-3xl bg-slate-50">
                            <Package class="w-12 h-12 text-slate-200 mx-auto mb-4" />
                            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight italic">No Products Found</h2>
                            <p class="mt-2 text-slate-500 font-bold uppercase tracking-widest text-[10px]">Try adjusting your filters</p>
                            <Link href="/shop" class="mt-6 inline-block text-[#FF6600] font-black underline underline-offset-4 uppercase tracking-widest text-[10px]">Clear Filters</Link>
                        </div>

                        <!-- Pagination -->
                        <div v-if="products.last_page > 1" class="mt-12 flex justify-center space-x-2">
                            <Link v-for="link in products.links" :key="link.label" :href="link.url || '#'" v-html="link.label" 
                                :class="[link.active ? 'bg-[#003366] text-white shadow-xl shadow-[#003366]/20' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200', !link.url ? 'opacity-50 cursor-not-allowed' : '']"
                                class="px-5 py-3 rounded-xl text-[10px] font-black transition-all uppercase tracking-widest">
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { Package } from 'lucide-vue-next';

defineProps({
    products: Object,
    filters: Object
});
</script>
