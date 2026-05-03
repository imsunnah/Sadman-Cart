<template>
    <StoreLayout>
        <div class="bg-slate-50 min-h-screen py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20">
                    <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase italic">Shop by <span class="text-primary-600">Category</span></h1>
                    <p class="mt-4 max-w-xl mx-auto text-sm font-bold text-slate-400 uppercase tracking-widest leading-loose">
                        Explore our professionally curated organic collections for a healthier lifestyle.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="category in categories" :key="category.id" class="group relative bg-white rounded-[3rem] p-10 shadow-2xl shadow-slate-200/50 border border-slate-100 hover:border-primary-900 transition-all duration-500 overflow-hidden flex flex-col items-center text-center">
                        <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary-900/5 rounded-full blur-2xl group-hover:bg-primary-900/10 transition-colors"></div>
                        
                        <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mb-8 shadow-inner group-hover:scale-110 transition-transform duration-500">
                            <div class="text-primary-900 font-black text-3xl italic">S</div>
                        </div>
                        
                        <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase tracking-tighter italic">
                            <Link :href="`/shop?category=${category.slug}`" class="hover:text-primary-600 transition-colors">{{ category.name }}</Link>
                        </h3>
                        
                        <p class="text-xs font-medium text-slate-500 mb-8 leading-relaxed line-clamp-2">{{ category.description || 'Discover the finest selection of ' + category.name + ' products at Sadman Cart.' }}</p>
                        
                        <div class="flex items-center space-x-4">
                            <span class="inline-flex items-center px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-600 border border-slate-100 group-hover:bg-primary-900 group-hover:text-white transition-colors">
                                {{ category.products_count }} Collections
                            </span>
                            <Link :href="`/shop?category=${category.slug}`" class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary-900 hover:border-primary-900 transition-all">
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>
                    </div>
                </div>

                <div v-if="!categories || categories.length === 0" class="mt-12 py-24 text-center bg-white rounded-[3rem] border-4 border-dashed border-slate-100 shadow-inner">
                    <Tags class="w-20 h-20 mx-auto text-slate-100 mb-6" />
                    <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Archives Empty</h2>
                    <p class="mt-2 text-slate-400 font-medium max-w-xs mx-auto">Our catalog is currently undergoing a refresh. Please check back shortly.</p>
                    <div class="mt-10">
                        <Link href="/shop" class="bg-primary-900 text-white px-10 py-4 rounded-full font-black uppercase tracking-widest text-xs shadow-xl shadow-primary-900/20 hover:bg-slate-800 transition-all">Establish Shop Access</Link>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { Tags, ArrowRight } from 'lucide-vue-next';

defineProps({
    categories: {
        type: Array,
        required: true
    }
});
</script>
