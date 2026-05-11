<template>
    <div class="min-h-screen flex flex-col font-sans bg-white text-slate-800">
        <!-- Header -->
        <header class="bg-white sticky top-0 z-50 shadow-sm">
            <!-- Top bar -->
            <div class="bg-[#003366] text-white py-2 md:py-3 text-[9px] md:text-xs font-bold uppercase tracking-[0.2em]">
                <div class="max-w-[1600px] mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-2 md:gap-0">
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

            <!-- Main Header -->
            <div class="max-w-[1600px] mx-auto px-4 py-3 md:py-6 flex flex-wrap md:flex-nowrap justify-between items-center gap-4">
                <!-- Logo -->
                <Link href="/" class="flex items-center flex-shrink-0">
                    <div v-if="$page.props.settings.site_logo" class="flex items-center">
                        <img :src="$page.props.settings.site_logo" :alt="$page.props.settings.site_name" class="h-12 md:h-20 w-auto object-contain mr-3" />
                        <span class="text-xl md:text-3xl font-black text-[#003366] tracking-tighter italic uppercase leading-none">{{ $page.props.settings.site_name }}</span>
                    </div>
                    <div v-else class="flex items-center">
                        <ShoppingBag class="w-8 h-8 md:w-10 md:h-10 text-[#003366] mr-3" />
                        <span class="text-xl md:text-3xl font-black text-[#003366] tracking-tighter italic uppercase leading-none">{{ $page.props.settings.site_name }}</span>
                    </div>
                </Link>

                <!-- Search (Responsive) -->
                <div class="w-full md:w-auto md:flex-grow md:max-w-xl order-3 md:order-2">
                    <form @submit.prevent="handleSearch" class="relative group">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Find your organic preference..."
                            class="w-full pl-12 pr-4 py-3 md:py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-xs font-bold focus:bg-white focus:border-[#003366]/10 focus:ring-0 outline-none transition-all shadow-inner"
                        />
                        <Search class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-[#003366] transition-colors" />
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2 md:gap-6 order-2 md:order-3">
                    <button @click="isCartOpen = true" class="relative p-3 group bg-slate-50 rounded-2xl hover:bg-[#003366]/5 transition-all">
                        <ShoppingCart class="w-6 h-6 text-[#003366] group-hover:scale-110 transition-transform" />
                        <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-[#FF6600] text-white text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full shadow-lg border-2 border-white animate-bounce">
                            {{ cartCount }}
                        </span>
                    </button>
                    <button @click="isMobileMenuOpen = true" class="md:hidden p-3 bg-[#003366] text-white rounded-2xl shadow-lg active:scale-95 transition-all">
                        <Menu class="w-6 h-6" />
                    </button>
                </div>
            </div>

            <!-- Desktop Nav -->
            <nav class="bg-[#003366] hidden md:block">
                <div class="max-w-[1600px] mx-auto px-4 py-5 flex justify-center items-center gap-12">
                    <Link href="/" :class="[$page.url === '/' ? 'text-[#FF6600]' : 'text-white hover:text-[#FF6600]']" class="text-xs md:text-sm font-black uppercase tracking-[0.2em] italic transition-all">Home</Link>
                    <Link href="/shop" :class="[$page.url.startsWith('/shop') ? 'text-[#FF6600]' : 'text-white hover:text-[#FF6600]']" class="text-xs md:text-sm font-black uppercase tracking-[0.2em] italic transition-all">Shop All</Link>
                    <Link 
                        v-for="category in $page.props.categories" 
                        :key="category.id" 
                        :href="`/shop?category=${category.slug}`"
                        :class="[$page.url.includes(`category=${category.slug}`) ? 'text-[#FF6600]' : 'text-white hover:text-[#FF6600]']"
                        class="text-xs md:text-sm font-black uppercase tracking-[0.2em] italic transition-all"
                    >
                        {{ category.name }}
                    </Link>
                </div>
            </nav>
        </header>

        <!-- Floating Cart (Right Middle) - Compact -->
        <button 
            @click="isCartOpen = true"
            class="fixed right-0 top-1/2 -translate-y-1/2 z-[90] bg-[#FF6600] text-white px-2 py-3 rounded-l-2xl shadow-xl flex flex-col items-center gap-1.5 hover:px-3 transition-all duration-300 group"
        >
            <ShoppingCart class="w-5 h-5" />
            <span v-if="cartCount > 0" class="text-[10px] font-black leading-none">{{ cartCount }}</span>
        </button>

        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />

        <!-- Refined Floating Messenger Chat (Right Bottom) -->
        <a 
            href="https://m.me/sadmancart" 
            target="_blank" 
            class="fixed bottom-8 right-8 z-[90] w-16 h-16 bg-[#0084FF] text-white rounded-full flex items-center justify-center shadow-2xl hover:scale-110 active:scale-95 transition-all group"
            title="Chat with us on Messenger"
        >
            <svg viewBox="0 0 24 24" class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.477 2 2 6.145 2 11.258c0 2.908 1.455 5.494 3.714 7.155.195.143.313.37.313.615v2.336c0 .487.564.76 1.01.467l2.613-1.716a.92.92 0 0 1 .536-.168h1.814c5.523 0 10-4.145 10-9.258C22 6.145 17.523 2 12 2zm1.268 12.392l-2.484-2.656-4.839 2.656 5.323-5.648 2.553 2.656 4.771-2.656-5.324 5.648z"/>
            </svg>
            <span class="absolute -top-1 -left-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#0084FF] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-[#0084FF]"></span>
            </span>
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

        <!-- Footer -->
        <footer class="bg-[#003366] text-white pt-16 pb-10">
            <div class="max-w-[1600px] mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
                <div class="col-span-2 md:col-span-1 space-y-6">
                    <div v-if="$page.props.settings.site_logo" class="flex items-center">
                        <img :src="$page.props.settings.site_logo" class="h-16 w-auto object-contain mr-3 brightness-0 invert" />
                        <span class="text-2xl font-black tracking-tight italic uppercase">{{ $page.props.settings.site_name }}</span>
                    </div>
                    <div v-else class="flex items-center">
                        <ShoppingBag class="w-8 h-8 text-white mr-2" />
                        <span class="text-2xl font-bold uppercase italic">{{ $page.props.settings.site_name }}</span>
                    </div>
                    <p class="text-[10px] text-slate-300 leading-relaxed font-bold uppercase tracking-widest">{{ $page.props.settings.footer_about }}</p>
                    
                    <div class="flex items-center gap-3 pt-2">
                        <a href="https://facebook.com/sadmancart" target="_blank" class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#FF6600] transition-all">
                            <Facebook class="w-4 h-4" />
                        </a>
                        <a href="https://instagram.com/sadmancart" target="_blank" class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#FF6600] transition-all">
                            <Instagram class="w-4 h-4" />
                        </a>
                        <a href="https://m.me/sadmancart" target="_blank" class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#0084FF] transition-all">
                            <MessageCircle class="w-4 h-4" />
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="font-black text-white mb-6 uppercase text-[10px] tracking-widest italic border-b border-white/10 pb-2">Links</h3>
                    <ul class="space-y-3 text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <li><Link href="/" class="hover:text-[#FF6600] transition-colors">Home</Link></li>
                        <li><Link href="/shop" class="hover:text-[#FF6600] transition-colors">Shop</Link></li>
                        <li><Link href="/cart" class="hover:text-[#FF6600] transition-colors">Cart</Link></li>
                        <li><Link href="/checkout" class="hover:text-[#FF6600] transition-colors">Checkout</Link></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-black text-white mb-6 uppercase text-[10px] tracking-widest italic border-b border-white/10 pb-2">Policy</h3>
                    <ul class="space-y-3 text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <li><Link href="/returns-refunds" class="hover:text-[#FF6600] transition-colors">Returns</Link></li>
                        <li><Link href="/shipping-policy" class="hover:text-[#FF6600] transition-colors">Shipping</Link></li>
                        <li><Link href="/privacy-policy" class="hover:text-[#FF6600] transition-colors">Privacy</Link></li>
                        <li><Link href="/terms" class="hover:text-[#FF6600] transition-colors">Terms</Link></li>
                    </ul>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <h3 class="font-black text-white mb-6 uppercase text-[10px] tracking-widest italic border-b border-white/10 pb-2">Contact</h3>
                    <ul class="space-y-4 text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <li class="flex items-start"><MapPin class="w-4 h-4 mr-3 text-[#FF6600] shrink-0" />{{ $page.props.settings.footer_address }}</li>
                        <li class="flex items-center"><Phone class="w-4 h-4 mr-3 text-[#FF6600] shrink-0" />{{ $page.props.settings.footer_phone }}</li>
                        <li class="flex items-center"><Mail class="w-4 h-4 mr-3 text-[#FF6600] shrink-0" />{{ $page.props.settings.footer_email }}</li>
                    </ul>
                </div>
            </div>
            <div class="max-w-[1600px] mx-auto px-4 mt-12 pt-8 border-t border-white/5 text-center text-[8px] text-slate-500 font-bold uppercase tracking-[0.3em]">
                <p>&copy; 2026 {{ $page.props.settings.site_name }}. Premium E-commerce Solutions.</p>
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

const { cartCount } = useCart();
const { toasts, removeToast } = useToast();
const isMobileMenuOpen = ref(false);
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
