<template>
    <div class="min-h-screen flex flex-col font-sans bg-white text-slate-800">
        <!-- Header -->
        <header class="bg-white sticky top-0 z-50 shadow-sm">
            <!-- Top bar -->
            <div class="bg-[#003366] text-white py-2 md:py-3 text-[9px] md:text-xs font-bold uppercase tracking-[0.2em]">
                <div class="max-w-[1550px] mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-2 md:gap-0">
                    <div class="flex flex-wrap justify-center items-center gap-4 md:gap-8">
                        <a :href="`tel:${$page.props.settings.footer_phone}`" class="flex items-center hover:text-[#FF6600] transition-colors">
                            <Phone class="w-3 h-3 mr-2 text-[#FF6600]" />
                            {{ $page.props.settings.footer_phone }}
                        </a>
                        <a :href="`mailto:${$page.props.settings.footer_email}`" class="flex items-center hover:text-[#FF6600] transition-colors">
                            <Mail class="w-3 h-3 mr-2 text-[#FF6600]" />
                            {{ $page.props.settings.footer_email }}
                        </a>
                    </div>
                    <div class="flex items-center gap-4 md:gap-8">
                        <div class="flex items-center gap-2">
                            <a href="/language/en" :class="$page.props.locale === 'en' ? 'text-[#FF6600]' : 'text-slate-300 hover:text-white'" class="transition-colors">EN</a>
                            <span class="text-slate-500">|</span>
                            <a href="/language/bn" :class="$page.props.locale === 'bn' ? 'text-[#FF6600]' : 'text-slate-300 hover:text-white'" class="transition-colors">BN</a>
                        </div>
                        <div class="flex items-center gap-4 border-l border-slate-700 pl-4 md:pl-8">
                            <Link v-if="!$page.props.auth.user" href="/login" class="hover:text-[#FF6600] transition-colors">Login</Link>
                            <div v-else class="flex items-center gap-4">
                                <span class="text-slate-400 hidden sm:inline">{{ $page.props.auth.user.name }}</span>
                                <Link href="/logout" method="post" as="button" class="hover:text-[#FF6600] transition-colors">Logout</Link>
                                <Link v-if="$page.props.auth.user?.role === 'admin'" href="/admin/dashboard" class="text-[#FF6600] font-black italic">Admin</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Header (Compact & Elite) -->
            <div class="max-w-[1550px] mx-auto px-4 py-4 md:py-6 flex items-center justify-between gap-4 md:gap-12">
                <!-- Logo (Simple & Clean) -->
                <Link href="/" class="flex items-center group flex-shrink-0">
                    <div v-if="$page.props.settings.site_logo" class="flex items-center">
                        <img :src="$page.props.settings.site_logo" :alt="$page.props.settings.site_name" class="h-10 md:h-16 w-auto object-contain transition-transform" />
                        <span class="ml-3 block text-xl md:text-3xl font-black text-[#003366] tracking-tighter italic uppercase leading-tight">{{ $page.props.settings.site_name }}</span>
                    </div>
                    <div v-else class="flex items-center">
                        <ShoppingBag class="w-8 h-8 text-[#003366] mr-2" />
                        <span class="text-xl md:text-2xl font-black text-[#003366] tracking-tighter italic uppercase leading-none">{{ $page.props.settings.site_name }}</span>
                    </div>
                </Link>

                <!-- Search (Clean & Wide) -->
                <div class="hidden md:block flex-grow max-w-2xl relative">
                    <form @submit.prevent="handleSearch" class="relative group">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Find your selection..."
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:bg-white focus:border-[#003366]/20 focus:ring-0 outline-none transition-all shadow-sm"
                        />
                        <Search class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#003366] transition-colors" />
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-3 md:gap-6">
                    <button @click="isCartOpen = true" class="relative p-2.5 group bg-slate-50 rounded-xl hover:bg-[#003366]/5 transition-all">
                        <ShoppingCart class="w-5 h-5 text-[#003366] group-hover:scale-110 transition-transform" />
                        <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-[#FF6600] text-white text-[9px] font-black w-4 h-4 flex items-center justify-center rounded-full shadow-lg border-2 border-white">
                            {{ cartCount }}
                        </span>
                    </button>
                    <button @click="isMobileMenuOpen = true" class="md:hidden p-2.5 bg-[#003366] text-white rounded-xl active:scale-95 transition-all">
                        <Menu class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <!-- Search (Mobile Only) -->
            <div class="md:hidden px-4 pb-4">
                <form @submit.prevent="handleSearch" class="relative group">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none"
                    />
                    <Search class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                </form>
            </div>

            <!-- Desktop Nav (Bold & Prominent) -->
            <nav class="bg-[#003366] hidden md:block border-y border-white/5">
                <div class="max-w-[1550px] mx-auto px-4 py-5 flex justify-center items-center gap-12">
                    <!-- Standard listing (up to 5 categories) -->
                    <template v-if="($page.props.categories || []).length <= 5">
                        <Link 
                            v-for="category in $page.props.categories" 
                            :key="category.id" 
                            :href="`/shop?category=${category.slug}`"
                            :class="[$page.url.includes(`category=${category.slug}`) ? 'text-[#FF6600]' : 'text-white hover:text-[#FF6600]']"
                            class="text-[11px] font-black uppercase tracking-[0.3em] transition-all drop-shadow-sm"
                        >
                            {{ category.name }}
                        </Link>
                    </template>

                    <!-- Too many categories - show first 5, then "More ->" dropdown -->
                    <template v-else>
                        <Link 
                            v-for="category in $page.props.categories.slice(0, 5)" 
                            :key="category.id" 
                            :href="`/shop?category=${category.slug}`"
                            :class="[$page.url.includes(`category=${category.slug}`) ? 'text-[#FF6600]' : 'text-white hover:text-[#FF6600]']"
                            class="text-[11px] font-black uppercase tracking-[0.3em] transition-all drop-shadow-sm"
                        >
                            {{ category.name }}
                        </Link>

                        <!-- More -> Dropdown -->
                        <div class="relative group/more">
                            <button 
                                class="text-[11px] font-black uppercase tracking-[0.3em] text-white hover:text-[#FF6600] transition-all drop-shadow-sm flex items-center gap-1 cursor-pointer"
                            >
                                More →
                            </button>
                            <div class="absolute left-1/2 -translate-x-1/2 top-full pt-4 opacity-0 invisible group-hover/more:opacity-100 group-hover/more:visible transition-all duration-300 z-50">
                                <div class="bg-[#002244] border border-white/10 rounded-2xl shadow-2xl py-3 w-64 flex flex-col overflow-hidden">
                                    <Link 
                                        v-for="category in $page.props.categories.slice(5)" 
                                        :key="category.id" 
                                        :href="`/shop?category=${category.slug}`"
                                        :class="[$page.url.includes(`category=${category.slug}`) ? 'text-[#FF6600]' : 'text-slate-200 hover:bg-[#FF6600] hover:text-white']"
                                        class="px-6 py-3 text-[10px] font-black uppercase tracking-[0.2em] transition-colors text-left"
                                    >
                                        {{ category.name }}
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Floating Cart Card (Right Side) - Matching Image Design Exactly -->
        <button 
            @click="isCartOpen = true"
            class="fixed right-0 top-1/2 -translate-y-1/2 z-[100] flex flex-col items-center w-16 bg-white shadow-[-5px_0_20px_rgba(0,0,0,0.1)] rounded-l-lg overflow-hidden group transition-all active:scale-95 border-l border-y border-slate-100"
        >
            <!-- Top Section (Orange) -->
            <div class="w-full bg-[#FF6600] pt-4 pb-3 flex flex-col items-center justify-center">
                <ShoppingBag class="w-6 h-6 text-white mb-1" />
                <span class="text-[9px] font-black text-white uppercase tracking-tight leading-none">{{ cartCount }} Items</span>
            </div>
            
            <!-- Bottom Section (White) -->
            <div class="w-full bg-white py-3 flex items-center justify-center">
                <span class="text-[11px] font-black text-[#FF6600] flex items-center gap-0.5">
                    <span class="text-xs">৳</span>{{ cartTotal.toLocaleString() }}
                </span>
            </div>
        </button>

        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />

        <!-- Elite Floating Chat (Messenger Style) -->
        <a 
            href="https://m.me/sadmancart" 
            target="_blank" 
            class="fixed bottom-8 right-8 z-[90] flex items-center gap-3 bg-white pl-5 pr-2 py-2 rounded-full shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] hover:shadow-[0_20px_50px_-10px_rgba(0,0,0,0.25)] hover:-translate-y-1 transition-all group border border-slate-100"
        >
            <div class="flex flex-col items-end">
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Online Support</span>
                <span class="text-[10px] font-black text-[#003366] uppercase tracking-wider leading-none group-hover:text-[#FF6600] transition-colors">Chat with us</span>
            </div>
            <div class="w-12 h-12 bg-[#0084FF] text-white rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-all">
                <MessageCircle class="w-6 h-6 fill-current" />
                <span class="absolute -top-1 -left-1 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500 border-2 border-white"></span>
                </span>
            </div>
        </a>

        <!-- Premium Mobile Menu Overlay -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0 translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-400 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 translate-x-full"
        >
            <div v-if="isMobileMenuOpen" class="fixed inset-0 z-[100] md:hidden">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isMobileMenuOpen = false"></div>
                <div class="absolute right-0 top-0 bottom-0 w-[85%] bg-white shadow-2xl flex flex-col p-8 animate-in slide-in-from-right duration-500">
                    <div class="flex justify-between items-center mb-12">
                        <div class="flex items-center">
                            <ShoppingBag class="w-8 h-8 text-[#003366] mr-2" />
                            <span class="text-xl font-black text-[#003366] uppercase italic">Menu</span>
                        </div>
                        <button @click="isMobileMenuOpen = false" class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 hover:text-[#FF6600] transition-colors">
                            <X class="w-6 h-6" />
                        </button>
                    </div>

                    <div class="flex-grow space-y-2 overflow-y-auto">
                        <Link href="/" @click="isMobileMenuOpen = false" class="block py-4 text-2xl font-black italic uppercase tracking-tighter text-[#003366] border-b border-slate-50">Home</Link>
                        <Link href="/shop" @click="isMobileMenuOpen = false" class="block py-4 text-2xl font-black italic uppercase tracking-tighter text-[#003366] border-b border-slate-50">Shop Catalog</Link>
                        
                        <div class="py-6">
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-6">Collections</p>
                            <div class="grid grid-cols-1 gap-4">
                                <Link 
                                    v-for="category in $page.props.categories" 
                                    :key="category.id" 
                                    :href="`/shop?category=${category.slug}`"
                                    @click="isMobileMenuOpen = false"
                                    class="text-lg font-bold text-slate-600 hover:text-[#FF6600] transition-colors"
                                >
                                    {{ category.name }}
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto pt-8 border-t border-slate-100 space-y-6">
                        <div class="flex flex-col gap-4">
                            <a :href="`tel:${$page.props.settings.footer_phone}`" class="flex items-center gap-4 text-sm font-bold text-slate-600">
                                <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-[#FF6600]"><Phone class="w-5 h-5" /></div>
                                {{ $page.props.settings.footer_phone }}
                            </a>
                            <a :href="`mailto:${$page.props.settings.footer_email}`" class="flex items-center gap-4 text-sm font-bold text-slate-600">
                                <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-[#FF6600]"><Mail class="w-5 h-5" /></div>
                                {{ $page.props.settings.footer_email }}
                            </a>
                        </div>
                        <Link href="/cart" @click="isMobileMenuOpen = false" class="w-full py-5 bg-[#FF6600] text-white rounded-2xl font-black text-xs uppercase tracking-widest text-center block shadow-xl shadow-orange-500/20">View My Cart</Link>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Toast Notifications (Remain same) -->
        <Teleport to="body">
            <div class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none" style="min-width: 320px; max-width: 380px">
                <TransitionGroup name="toast">
                    <div v-for="toast in toasts" :key="toast.id" class="flex items-start gap-4 rounded-2xl shadow-2xl px-5 py-4 pointer-events-auto backdrop-blur-sm border bg-white" :class="toast.type === 'success' ? 'border-green-100 shadow-green-100/50' : (toast.type === 'error' ? 'border-red-100 shadow-red-100/50' : 'border-blue-100 shadow-blue-100/50')">
                        <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center mt-0.5" :class="toast.type === 'success' ? 'bg-green-50 text-green-600' : (toast.type === 'error' ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600')">
                            <CheckCircle v-if="toast.type === 'success'" class="w-5 h-5" />
                            <XCircle v-else-if="toast.type === 'error'" class="w-5 h-5" />
                            <Info v-else class="w-5 h-5" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-black uppercase tracking-widest mb-0.5" :class="toast.type === 'success' ? 'text-green-700' : (toast.type === 'error' ? 'text-red-700' : 'text-blue-700')">
                                {{ toast.type === "success" ? "Added to Cart" : (toast.type === "error" ? "Error" : "Notice") }}
                            </p>
                            <p class="text-xs font-bold text-slate-600 leading-snug">{{ toast.message }}</p>
                        </div>
                        <button @click="removeToast(toast.id)" class="flex-shrink-0 text-slate-300 hover:text-slate-500 transition-colors mt-0.5">
                            <X class="w-4 h-4" />
                        </button>
                    </div>
                </TransitionGroup>
            </div>
        </Teleport>

        <!-- Page Content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Footer (Clean & Detailed) -->
        <footer class="bg-gradient-to-b from-[#003366] to-[#001A33] text-white pt-24 pb-12 relative overflow-hidden">
            <!-- Subtle Decorative Element -->
            <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-[#FF6600]/30 to-transparent"></div>
            
            <div class="max-w-[1550px] mx-auto px-4 grid grid-cols-2 md:grid-cols-5 gap-12 md:gap-16">
                <!-- Brand & Contact -->
                <div class="col-span-2 md:col-span-1 space-y-8">
                    <div class="space-y-6">
                        <Link href="/" v-if="$page.props.settings.site_logo" class="flex items-center group">
                            <div class="p-2 bg-white/5 rounded-xl group-hover:bg-white/10 transition-all">
                                <img :src="$page.props.settings.site_logo" class="h-16 w-auto object-contain" />
                            </div>
                            <div class="ml-3">
                                <span class="block text-xl font-black text-white tracking-tight italic uppercase">{{ $page.props.settings.site_name }}</span>
                            </div>
                        </Link>
                        <div v-else class="flex items-center">
                            <ShoppingBag class="w-8 h-8 text-white mr-2" />
                            <span class="text-2xl font-black text-white uppercase italic">{{ $page.props.settings.site_name }}</span>
                        </div>
                        <p class="text-[10px] text-slate-300 leading-relaxed font-bold uppercase tracking-widest max-w-[280px]">{{ $page.props.settings.footer_about }}</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <MapPin class="w-4 h-4 text-[#FF6600] shrink-0 mt-0.5" />
                            <span class="text-[10px] font-bold text-slate-300 uppercase leading-relaxed">{{ $page.props.settings.footer_address }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <Phone class="w-4 h-4 text-[#FF6600] shrink-0" />
                            <span class="text-[10px] font-bold text-slate-300 uppercase">{{ $page.props.settings.footer_phone }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <Mail class="w-4 h-4 text-[#FF6600] shrink-0" />
                            <span class="text-[10px] font-bold text-slate-300 uppercase">{{ $page.props.settings.footer_email }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <a href="https://facebook.com/sadmancart" target="_blank" class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-slate-400 hover:bg-[#FF6600] hover:text-white transition-all">
                            <Facebook class="w-4 h-4" />
                        </a>
                        <a href="https://instagram.com/sadmancart" target="_blank" class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-slate-400 hover:bg-[#FF6600] hover:text-white transition-all">
                            <Instagram class="w-4 h-4" />
                        </a>
                        <a href="https://m.me/sadmancart" target="_blank" class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-slate-400 hover:bg-[#0084FF] hover:text-white transition-all">
                            <MessageCircle class="w-4 h-4" />
                        </a>
                    </div>
                </div>

                <!-- Information -->
                <div>
                    <h3 class="font-black text-white mb-8 uppercase text-[10px] tracking-[0.2em] italic border-b border-white/10 pb-2">Information</h3>
                    <ul class="space-y-4 text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <li v-for="page in ($page.props.activePages || []).filter(p => p.group === 'about_us')" :key="page.id">
                            <Link :href="`/pages/${page.slug}`" class="hover:text-[#FF6600] transition-colors">{{ page.title }}</Link>
                        </li>
                    </ul>
                </div>

                <!-- Shop By -->
                <div>
                    <h3 class="font-black text-white mb-8 uppercase text-[10px] tracking-[0.2em] italic border-b border-white/10 pb-2">Shop By</h3>
                    <ul class="space-y-4 text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <li v-for="cat in $page.props.categories.slice(0, 5)" :key="cat.id">
                            <Link :href="`/shop?category=${cat.slug}`" class="hover:text-[#FF6600] transition-colors">{{ cat.name }}</Link>
                        </li>
                        <li><Link href="/shop" class="hover:text-[#FF6600] transition-colors font-black text-[#FF6600]">View All Shop</Link></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="font-black text-white mb-8 uppercase text-[10px] tracking-[0.2em] italic border-b border-white/10 pb-2">Support</h3>
                    <ul class="space-y-4 text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <li v-for="page in ($page.props.activePages || []).filter(p => p.group === 'support')" :key="page.id">
                            <Link :href="`/pages/${page.slug}`" class="hover:text-[#FF6600] transition-colors">{{ page.title }}</Link>
                        </li>
                        <li>
                            <Link href="/reviews" class="hover:text-[#FF6600] transition-colors">Customer Reviews</Link>
                        </li>
                    </ul>
                </div>

                <!-- Consumer Policy -->
                <div>
                    <h3 class="font-black text-white mb-8 uppercase text-[10px] tracking-[0.2em] italic border-b border-white/10 pb-2">Consumer Policy</h3>
                    <ul class="space-y-4 text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <li v-for="page in ($page.props.activePages || []).filter(p => p.group === 'consumer_policy')" :key="page.id">
                            <Link :href="`/pages/${page.slug}`" class="hover:text-[#FF6600] transition-colors">{{ page.title }}</Link>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="max-w-[1550px] mx-auto px-4 mt-20 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.3em]">
                    &copy; 2026 {{ $page.props.settings.site_name }}. All Rights Reserved.
                </p>
                <div class="flex items-center gap-2">
                    <ShieldCheck class="w-4 h-4 text-[#FF6600]" />
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest italic">Premium Verified Platform</span>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import {
    ShoppingCart,
    Search,
    Menu,
    X,
    MapPin,
    Phone,
    Mail,
    CheckCircle,
    XCircle,
    Info,
    ShoppingBag,
    MessageCircle,
    Facebook,
    Instagram,
} from "lucide-vue-next";
import { useCart } from "@/Composables/useCart";
import { useToast } from "@/Composables/useToast";
import CartDrawer from "@/Components/CartDrawer.vue";

const { cartCount, cartTotal } = useCart();
const { toasts, removeToast } = useToast();
const isMobileMenuOpen = ref(false);
const isCartOpen = ref(false);
const searchQuery = ref("");

const handleSearch = () => {
    if (searchQuery.value.trim()) {
        router.visit(`/shop?search=${searchQuery.value}`);
    } else {
        router.visit("/shop");
    }
};
</script>

<style>
.toast-enter-active {
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.toast-leave-active {
    transition: all 0.25s ease-in;
}
.toast-enter-from {
    opacity: 0;
    transform: translateX(60px) scale(0.9);
}
.toast-leave-to {
    opacity: 0;
    transform: translateX(60px) scale(0.9);
}
</style>
