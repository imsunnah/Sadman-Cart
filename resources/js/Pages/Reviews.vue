<template>
    <StoreLayout>
        <div class="bg-[#F8F9FA] min-h-screen py-16 font-sans">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Page Title -->
                <div class="text-center mb-16">
                    <span class="text-[9px] font-black text-[#FF6600] uppercase tracking-[0.4em] mb-2 block">Real Customer Experiences</span>
                    <h1 class="text-4xl font-black text-[#003366] uppercase tracking-tighter italic">Verified Ratings & Reviews</h1>
                    <div class="w-12 h-1 bg-[#FF6600] mx-auto mt-4 rounded-full"></div>
                </div>

                <!-- Rating Stats Summary -->
                <div class="bg-white rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] border border-slate-100 p-8 md:p-12 mb-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 items-center">
                        <!-- Score column -->
                        <div class="text-center md:border-r border-slate-100 md:pr-12">
                            <p class="text-6xl font-black text-[#003366]">{{ averageRating }}</p>
                            <div class="flex justify-center gap-1 my-3">
                                <Star v-for="i in 5" :key="i" class="w-5 h-5 fill-current" :class="i <= Math.round(averageRating) ? 'text-[#FF6600]' : 'text-slate-200'" />
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Overall Store Rating</p>
                            <p class="text-xs font-bold text-slate-400 mt-1">Based on {{ totalCount }} verified reviews</p>
                        </div>
                        
                        <!-- Progress breakdown column -->
                        <div class="md:col-span-2 space-y-3">
                            <div v-for="star in 5" :key="star" class="flex items-center gap-4">
                                <span class="text-[10px] font-black text-slate-500 w-12 text-right">{{ 6 - star }} Star</span>
                                <div class="flex-grow h-2.5 bg-slate-50 border border-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#FF6600] rounded-full transition-all duration-1000" :style="{ width: (starsBreakdown[6 - star] || 0) + '%' }"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 w-10">{{ Math.round(starsBreakdown[6 - star] || 0) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Grid -->
                <div v-if="reviews.data && reviews.data.length > 0" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div 
                            v-for="r in reviews.data" 
                            :key="r.id" 
                            class="bg-white border border-slate-100 rounded-[2rem] p-6 md:p-8 relative group hover:shadow-[0_20px_45px_-12px_rgba(0,0,0,0.06)] hover:border-[#003366]/20 transition-all duration-500 flex flex-col justify-between"
                        >
                            <Quote class="absolute top-6 right-8 w-8 h-8 text-slate-100 opacity-40 group-hover:text-[#FF6600]/20 transition-all" />
                            
                            <div>
                                <!-- Header info -->
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h4 class="text-xs font-black text-[#003366] uppercase tracking-wider">{{ r.customer_name }}</h4>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                            {{ new Date(r.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) }}
                                        </p>
                                    </div>
                                    <div class="flex gap-0.5">
                                        <Star v-for="star in 5" :key="star" class="w-3.5 h-3.5 fill-current" :class="star <= r.rating ? 'text-[#FF6600]' : 'text-slate-200'" />
                                    </div>
                                </div>

                                <!-- Comment -->
                                <p class="text-[13px] text-slate-600 font-bold italic leading-relaxed mb-6 whitespace-pre-line">
                                    "{{ r.comment }}"
                                </p>
                            </div>

                            <!-- Associated Product card (if any) -->
                            <div v-if="r.product" class="mt-4 p-3 bg-slate-50 border border-slate-100 rounded-2xl flex items-center gap-3 group/prod hover:border-[#FF6600]/20 transition-colors">
                                <div class="w-12 h-12 bg-white rounded-xl overflow-hidden p-1 flex-shrink-0 border border-slate-100">
                                    <img :src="r.product.image" class="w-full h-full object-contain" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Reviewing Product</span>
                                    <Link :href="`/products/${r.product.slug}`" class="text-[10px] font-black text-[#003366] uppercase truncate block hover:text-[#FF6600] transition-colors leading-tight">
                                        {{ r.product.name }}
                                    </Link>
                                </div>
                                <Link :href="`/products/${r.product.slug}`" class="w-8 h-8 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#FF6600] hover:border-[#FF6600]/30 transition-all flex-shrink-0">
                                    <ArrowRight class="w-3.5 h-3.5" />
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="reviews.links && reviews.links.length > 3" class="flex justify-center mt-12">
                        <nav class="flex items-center gap-1.5">
                            <template v-for="(link, i) in reviews.links" :key="i">
                                <div 
                                    v-if="link.url === null" 
                                    class="px-3.5 py-2 text-[10px] font-black text-slate-300 uppercase tracking-widest rounded-lg border border-slate-100 cursor-not-allowed select-none"
                                    v-html="link.label"
                                />
                                <Link 
                                    v-else 
                                    :href="link.url" 
                                    class="px-3.5 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg border transition-all"
                                    :class="link.active 
                                        ? 'bg-[#003366] text-white border-[#003366] shadow-md shadow-blue-900/10' 
                                        : 'bg-white text-slate-600 border-slate-100 hover:bg-slate-50'"
                                    v-html="link.label"
                                />
                            </template>
                        </nav>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-20 bg-white rounded-[2rem] border border-slate-100 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] p-12">
                    <MessageSquare class="w-16 h-16 text-slate-300 mx-auto mb-6" />
                    <h2 class="text-xl font-black text-[#003366] uppercase tracking-tight">No Verified Reviews Yet</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-sm mx-auto mt-2 leading-relaxed">
                        Be the first to submit a verified rating or review directly from our product details pages!
                    </p>
                    <div class="mt-8">
                        <Link href="/shop" class="inline-flex items-center gap-2 bg-[#FF6600] text-white px-8 py-3.5 rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-[#e55c00] transition-all shadow-md">
                            Browse Collection
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { Star, Quote, ArrowRight, MessageSquare } from 'lucide-vue-next';

defineProps({
    reviews: Object,
    averageRating: Number,
    totalCount: Number,
    starsBreakdown: Object
});
</script>
