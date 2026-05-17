<template>
    <Head :title="page.title" />
    
    <StoreLayout>
        <div class="bg-gradient-to-b from-[#003366]/5 via-white to-white min-h-screen py-16 px-4">
            <div class="max-w-[1000px] mx-auto">
                <!-- Navigation Breadcrumb -->
                <nav class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8">
                    <Link href="/" class="hover:text-[#FF6600] transition-colors">Home</Link>
                    <span>/</span>
                    <span class="text-[#FF6600]">{{ page.title }}</span>
                </nav>

                <!-- Page Banner Image (if exists) -->
                <div v-if="page.image" class="w-full h-[300px] md:h-[400px] rounded-[2rem] overflow-hidden mb-12 shadow-2xl relative">
                    <img :src="page.image" class="w-full h-full object-cover" :alt="page.title">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a]/90 via-[#0f172a]/40 to-transparent flex flex-col justify-end p-8 md:p-12">
                        <span class="text-[10px] font-black text-[#FF6600] uppercase tracking-[0.2em] block mb-3">
                            {{ getGroupLabel(page.group) }}
                        </span>
                        <h1 class="text-3xl md:text-5xl font-black text-white uppercase tracking-tight leading-none mb-4">
                            {{ page.title }}
                        </h1>
                        <p v-if="page.description" class="text-xs md:text-sm text-slate-300 font-bold max-w-2xl leading-relaxed uppercase tracking-wider">
                            {{ page.description }}
                        </p>
                    </div>
                </div>

                <!-- Page Header (if no image) -->
                <header v-else class="mb-12 border-b border-slate-100 pb-8">
                    <span class="text-[10px] font-black text-[#FF6600] uppercase tracking-[0.2em] block mb-3">
                        {{ getGroupLabel(page.group) }}
                    </span>
                    <h1 class="text-4xl md:text-5xl font-black text-[#003366] uppercase tracking-tight leading-none mb-4">
                        {{ page.title }}
                    </h1>
                    <p v-if="page.description" class="text-base md:text-lg text-slate-500 font-semibold leading-relaxed max-w-3xl italic">
                        {{ page.description }}
                    </p>
                </header>

                <!-- Page Content (Render HTML beautifully) -->
                <article class="prose prose-slate prose-lg max-w-none text-slate-600 font-medium leading-relaxed mt-8">
                    <div v-html="page.content" class="custom-content-styles"></div>
                </article>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';

defineProps({
    page: Object
});

const getGroupLabel = (group) => {
    switch(group) {
        case 'about_us': return 'Information & Company';
        case 'consumer_policy': return 'Consumer Policy & Protection';
        case 'support': return 'Support & Help Desk';
        default: return 'Information Page';
    }
};
</script>

<style>
.custom-content-styles h1 {
    font-size: 2.25rem;
    font-weight: 900;
    color: #003366;
    margin-top: 2rem;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: -0.025em;
}

.custom-content-styles h2 {
    font-size: 1.75rem;
    font-weight: 800;
    color: #003366;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
}

.custom-content-styles h3 {
    font-size: 1.25rem;
    font-weight: 800;
    color: #003366;
    margin-top: 1.25rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
}

.custom-content-styles p {
    font-size: 1rem;
    line-height: 1.75;
    color: #475569;
    margin-bottom: 1.25rem;
}

.custom-content-styles ul, .custom-content-styles ol {
    margin-bottom: 1.25rem;
    padding-left: 1.5rem;
    list-style-type: decimal;
}

.custom-content-styles ul {
    list-style-type: disc;
}

.custom-content-styles li {
    font-size: 1rem;
    color: #475569;
    margin-bottom: 0.5rem;
}

.custom-content-styles strong {
    color: #003366;
    font-weight: 700;
}
</style>
