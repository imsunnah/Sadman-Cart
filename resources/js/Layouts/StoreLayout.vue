<template>
    <div class="min-h-screen flex flex-col font-sans bg-white text-slate-800">
        
        <!-- Header -->
        <header class="bg-white sticky top-0 z-50 shadow-sm">
            <!-- Top bar -->
            <div class="bg-[#003366] text-white py-2 text-xs font-medium">
                <div class="max-w-[1600px] mx-auto px-4 flex justify-between items-center">
                    <div class="flex space-x-6">
                        <span class="flex items-center"><Phone class="w-3 h-3 mr-2" /> {{ $page.props.settings.footer_phone }}</span>
                        <span class="flex items-center"><Mail class="w-3 h-3 mr-2" /> {{ $page.props.settings.footer_email }}</span>
                    </div>
                    <div class="flex items-center space-x-6">
                        <Link v-if="!$page.props.auth.user" href="/login" class="hover:text-[#FF6600]">Login / Register</Link>
                        <div v-else class="flex items-center space-x-4">
                            <span class="text-slate-300">Welcome, {{ $page.props.auth.user.name }}</span>
                            <Link href="/logout" method="post" as="button" class="hover:text-[#FF6600]">Logout</Link>
                        </div>
                        <Link v-if="$page.props.auth.user?.role === 'admin'" href="/admin/dashboard" class="text-[#FF6600] font-bold">Admin Panel</Link>
                    </div>
                </div>
            </div>

            <!-- Main Header -->
            <div class="max-w-[1600px] mx-auto px-4 py-5 flex justify-between items-center gap-10">
                <Link href="/" class="flex items-center flex-shrink-0">
                    <img v-if="$page.props.settings.site_logo" :src="$page.props.settings.site_logo" :alt="$page.props.settings.site_name" class="h-10 w-auto" />
                    <span v-else class="text-2xl font-black text-[#003366] tracking-tight">{{ $page.props.settings.site_name }}</span>
                </Link>

                <!-- Search -->
                <div class="flex-grow max-w-xl">
                    <form @submit.prevent="handleSearch" class="relative group">
                        <input 
                            v-model="searchQuery" 
                            type="text" 
                            placeholder="Search for products..." 
                            class="w-full pl-12 pr-4 py-3 bg-slate-100 border-none rounded-full text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all"
                        >
                        <Search class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#003366] transition-colors" />
                    </form>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-6">
                    <Link href="/cart" class="relative p-2 group">
                        <ShoppingCart class="w-7 h-7 text-[#003366] group-hover:text-[#FF6600] transition-colors" />
                        <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-[#FF6600] text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full shadow-lg border-2 border-white">
                            {{ cartCount }}
                        </span>
                    </Link>
                    <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="md:hidden p-2">
                        <Menu class="w-7 h-7 text-[#003366]" />
                    </button>
                </div>
            </div>

            <!-- Navbar -->
            <nav class="border-t border-slate-50 hidden md:block">
                <div class="max-w-[1600px] mx-auto px-4 py-3 flex justify-center space-x-10 text-xs font-bold uppercase tracking-wider">
                    <Link href="/" :class="[$page.url === '/' ? 'text-[#FF6600]' : 'text-slate-600 hover:text-[#FF6600]']">Home</Link>
                    <Link href="/shop" :class="[$page.url.startsWith('/shop') ? 'text-[#FF6600]' : 'text-slate-600 hover:text-[#FF6600]']">Shop All</Link>
                    <Link v-for="category in $page.props.categories" :key="category.id" :href="`/shop?category=${category.slug}`" 
                        :class="[$page.url.includes(`category=${category.slug}`) ? 'text-[#FF6600]' : 'text-slate-600 hover:text-[#FF6600]']"
                    >
                        {{ category.name }}
                    </Link>
                </div>
            </nav>
        </header>

        <!-- Mobile Menu -->
        <div v-if="isMobileMenuOpen" class="fixed inset-0 z-[60] bg-white md:hidden p-6">
            <div class="flex justify-between items-center mb-10 pb-6 border-b">
                <span class="text-xl font-bold text-[#003366]">Navigation</span>
                <button @click="isMobileMenuOpen = false" class="p-2 bg-slate-50 rounded-full"><X class="w-6 h-6 text-[#003366]" /></button>
            </div>
            <div class="space-y-6">
                <Link href="/" class="block text-lg font-bold text-slate-800" @click="isMobileMenuOpen = false">Home</Link>
                <Link href="/shop" class="block text-lg font-bold text-slate-800" @click="isMobileMenuOpen = false">Shop All</Link>
                <div v-for="category in $page.props.categories" :key="category.id">
                    <Link :href="`/shop?category=${category.slug}`" class="block text-lg font-bold text-slate-800" @click="isMobileMenuOpen = false">{{ category.name }}</Link>
                </div>
                <hr />
                <Link href="/cart" class="block text-lg font-bold text-[#FF6600]" @click="isMobileMenuOpen = false">View Cart</Link>
            </div>
        </div>

        <!-- Page Content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-slate-50 border-t border-slate-200 pt-16 pb-10">
            <div class="max-w-[1600px] mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <div class="space-y-6">
                    <div class="flex items-center">
                        <img v-if="$page.props.settings.site_logo" :src="$page.props.settings.site_logo" class="h-8 w-auto" />
                        <span class="ml-2 text-xl font-bold text-[#003366]">{{ $page.props.settings.site_name }}</span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed font-bold">
                        {{ $page.props.settings.footer_about }}
                    </p>
                </div>
                <div>
                    <h3 class="font-black text-[#003366] mb-8 uppercase text-xs tracking-widest">Quick Links</h3>
                    <ul class="space-y-4 text-sm text-slate-500 font-bold">
                        <li><Link href="/" class="hover:text-[#FF6600]">Home</Link></li>
                        <li><Link href="/shop" class="hover:text-[#FF6600]">Shop Products</Link></li>
                        <li><Link href="/cart" class="hover:text-[#FF6600]">My Cart</Link></li>
                        <li><Link href="/checkout" class="hover:text-[#FF6600]">Checkout</Link></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-black text-[#003366] mb-8 uppercase text-xs tracking-widest">Store Policy</h3>
                    <ul class="space-y-4 text-sm text-slate-500 font-bold">
                        <li><a href="#" class="hover:text-[#FF6600]">Return Policy</a></li>
                        <li><a href="#" class="hover:text-[#FF6600]">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-[#FF6600]">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-black text-[#003366] mb-8 uppercase text-xs tracking-widest">Contact Info</h3>
                    <ul class="space-y-5 text-sm text-slate-500 font-bold">
                        <li class="flex items-start"><MapPin class="w-5 h-5 mr-3 text-[#FF6600] mt-0.5" /> {{ $page.props.settings.footer_address }}</li>
                        <li class="flex items-center"><Phone class="w-5 h-5 mr-3 text-[#FF6600]" /> {{ $page.props.settings.footer_phone }}</li>
                        <li class="flex items-center"><Mail class="w-5 h-5 mr-3 text-[#FF6600]" /> {{ $page.props.settings.footer_email }}</li>
                    </ul>
                </div>
            </div>
            <div class="max-w-[1600px] mx-auto px-4 mt-16 pt-8 border-t border-slate-200 text-center text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">
                <p>&copy; 2026 {{ $page.props.settings.site_name }}. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { ShoppingCart, Search, Menu, X, MapPin, Phone, Mail } from 'lucide-vue-next';
import { useCart } from '@/Composables/useCart';

const { cartCount } = useCart();
const isMobileMenuOpen = ref(false);
const searchQuery = ref('');

const handleSearch = () => {
    if (searchQuery.value.trim()) {
        router.visit(`/shop?search=${searchQuery.value}`);
    } else {
        router.visit('/shop');
    }
};
</script>
