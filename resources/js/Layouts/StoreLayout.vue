<template>
    <div class="min-h-screen flex flex-col font-sans bg-white text-slate-800">
        <!-- Header -->
        <header class="bg-white sticky top-0 z-50 shadow-sm">
            <!-- Top bar -->
            <div class="bg-[#003366] text-white py-3 md:py-4 text-sm font-medium">
                <div
                    class="max-w-[1600px] mx-auto px-4 flex justify-between items-center"
                >
                    <div class="flex space-x-6">
                        <span class="flex items-center"
                            ><Phone class="w-3 h-3 mr-2" />
                            {{ $page.props.settings.footer_phone }}</span
                        >
                        <span class="flex items-center"
                            ><Mail class="w-3 h-3 mr-2" />
                            {{ $page.props.settings.footer_email }}</span
                        >
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-2 mr-2">
                            <a href="/language/en" :class="$page.props.locale === 'en' ? 'text-white font-bold' : 'text-slate-300 hover:text-white'" class="transition-colors">EN</a>
                            <span class="text-slate-400">|</span>
                            <a href="/language/bn" :class="$page.props.locale === 'bn' ? 'text-white font-bold' : 'text-slate-300 hover:text-white'" class="transition-colors">BN</a>
                        </div>
                        <Link
                            v-if="!$page.props.auth.user"
                            href="/login"
                            class="hover:text-[#FF6600]"
                            >{{ $t('Login / Register') }}</Link
                        >
                        <div v-else class="flex items-center space-x-4">
                            <span class="text-slate-300"
                                >{{ $t('Welcome') }}, {{ $page.props.auth.user.name }}</span
                            >
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                class="hover:text-[#FF6600]"
                                >{{ $t('Logout') }}</Link
                            >
                        </div>
                        <Link
                            v-if="$page.props.auth.user?.role === 'admin'"
                            href="/admin/dashboard"
                            class="text-[#FF6600] font-bold"
                            >{{ $t('Admin Panel') }}</Link
                        >
                    </div>
                </div>
            </div>

            <!-- Main Header -->
            <div
                class="max-w-[1600px] mx-auto px-4 py-3 md:py-5 flex flex-wrap md:flex-nowrap justify-between items-center gap-3 md:gap-10"
            >
                <Link href="/" class="flex items-center flex-shrink-0 order-1">
                    <div v-if="$page.props.settings.site_logo" class="flex items-center">
                        <img
                            :src="$page.props.settings.site_logo"
                            :alt="$page.props.settings.site_name"
                            class="h-16 md:h-24 w-auto object-contain mr-2 md:mr-4"
                        />
                        <span class="text-xl md:text-3xl font-black text-[#003366] tracking-tight">
                            {{ $page.props.settings.site_name }}
                        </span>
                    </div>
                    <div v-else class="flex items-center">
                        <ShoppingBag class="w-7 h-7 md:w-10 md:h-10 text-[#003366] mr-2 md:mr-3" />
                        <span class="text-xl md:text-3xl font-black text-[#003366] tracking-tight">
                            {{ $page.props.settings.site_name }}
                        </span>
                    </div>
                </Link>

                <!-- Search -->
                <div class="w-full md:w-auto md:flex-grow md:max-w-xl order-3 md:order-2 mt-2 md:mt-0">
                    <form @submit.prevent="handleSearch" class="relative group">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search for products..."
                            class="w-full pl-12 pr-4 py-2.5 md:py-3 bg-slate-100 border-none rounded-full text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all"
                        />
                        <Search
                            class="w-4 h-4 md:w-5 md:h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#003366] transition-colors"
                        />
                    </form>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-2 md:space-x-6 order-2 md:order-3">
                    <Link href="/cart" class="relative p-2 group">
                        <ShoppingCart
                            class="w-6 h-6 md:w-7 md:h-7 text-[#003366] group-hover:text-[#FF6600] transition-colors"
                        />
                        <span
                            v-if="cartCount > 0"
                            class="absolute -top-1 -right-1 bg-[#FF6600] text-white text-[10px] font-bold w-4 h-4 md:w-5 md:h-5 flex items-center justify-center rounded-full shadow-lg border-2 border-white"
                        >
                            {{ cartCount }}
                        </span>
                    </Link>
                    <button
                        @click="isMobileMenuOpen = !isMobileMenuOpen"
                        class="md:hidden p-2"
                    >
                        <Menu class="w-6 h-6 text-[#003366]" />
                    </button>
                </div>
            </div>

            <!-- Navbar -->
            <nav class="border-t border-slate-50 hidden md:block">
                <div
                    class="max-w-[1600px] mx-auto px-4 py-3 flex justify-center space-x-10 text-xs font-bold uppercase tracking-wider"
                >
                    <Link
                        href="/"
                        :class="[
                            $page.url === '/'
                                ? 'text-[#FF6600]'
                                : 'text-slate-600 hover:text-[#FF6600]',
                        ]"
                        >{{ $t('Home') }}</Link
                    >
                    <Link
                        href="/shop"
                        :class="[
                            $page.url.startsWith('/shop')
                                ? 'text-[#FF6600]'
                                : 'text-slate-600 hover:text-[#FF6600]',
                        ]"
                        >{{ $t('Shop All') }}</Link
                    >
                    <Link
                        v-for="category in $page.props.categories"
                        :key="category.id"
                        :href="`/shop?category=${category.slug}`"
                        :class="[
                            $page.url.includes(`category=${category.slug}`)
                                ? 'text-[#FF6600]'
                                : 'text-slate-600 hover:text-[#FF6600]',
                        ]"
                    >
                        {{ category.name }}
                    </Link>
                </div>
            </nav>
        </header>

        <!-- Mobile Menu -->
        <div
            v-if="isMobileMenuOpen"
            class="fixed inset-0 z-[60] bg-white md:hidden p-6"
        >
            <div class="flex justify-between items-center mb-10 pb-6 border-b">
                <span class="text-xl font-bold text-[#003366]">Navigation</span>
                <button
                    @click="isMobileMenuOpen = false"
                    class="p-2 bg-slate-50 rounded-full"
                >
                    <X class="w-6 h-6 text-[#003366]" />
                </button>
            </div>
            <div class="space-y-6">
                <Link
                    href="/"
                    class="block text-lg font-bold text-slate-800"
                    @click="isMobileMenuOpen = false"
                    >{{ $t('Home') }}</Link
                >
                <Link
                    href="/shop"
                    class="block text-lg font-bold text-slate-800"
                    @click="isMobileMenuOpen = false"
                    >{{ $t('Shop All') }}</Link
                >
                <div
                    v-for="category in $page.props.categories"
                    :key="category.id"
                >
                    <Link
                        :href="`/shop?category=${category.slug}`"
                        class="block text-lg font-bold text-slate-800"
                        @click="isMobileMenuOpen = false"
                        >{{ category.name }}</Link
                    >
                </div>
                <hr />
                <Link
                    href="/cart"
                    class="block text-lg font-bold text-[#FF6600]"
                    @click="isMobileMenuOpen = false"
                    >{{ $t('View Cart') }}</Link
                >
            </div>
        </div>

        <!-- Toast Notifications -->
        <Teleport to="body">
            <div
                class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none"
                style="min-width: 320px; max-width: 380px"
            >
                <TransitionGroup name="toast">
                    <div
                        v-for="toast in toasts"
                        :key="toast.id"
                        class="flex items-start gap-4 rounded-2xl shadow-2xl px-5 py-4 pointer-events-auto backdrop-blur-sm border"
                        :class="{
                            'bg-white border-green-100 shadow-green-100/50':
                                toast.type === 'success',
                            'bg-white border-red-100 shadow-red-100/50':
                                toast.type === 'error',
                            'bg-white border-blue-100 shadow-blue-100/50':
                                toast.type === 'info',
                        }"
                    >
                        <!-- Icon -->
                        <div
                            class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center mt-0.5"
                            :class="{
                                'bg-green-50 text-green-600':
                                    toast.type === 'success',
                                'bg-red-50 text-red-600':
                                    toast.type === 'error',
                                'bg-blue-50 text-blue-600':
                                    toast.type === 'info',
                            }"
                        >
                            <CheckCircle
                                v-if="toast.type === 'success'"
                                class="w-5 h-5"
                            />
                            <XCircle
                                v-else-if="toast.type === 'error'"
                                class="w-5 h-5"
                            />
                            <Info v-else class="w-5 h-5" />
                        </div>
                        <!-- Message -->
                        <div class="flex-1 min-w-0">
                            <p
                                class="text-[11px] font-black uppercase tracking-widest mb-0.5"
                                :class="{
                                    'text-green-700': toast.type === 'success',
                                    'text-red-700': toast.type === 'error',
                                    'text-blue-700': toast.type === 'info',
                                }"
                            >
                                {{
                                    toast.type === "success"
                                        ? "Added to Cart"
                                        : toast.type === "error"
                                          ? "Error"
                                          : "Notice"
                                }}
                            </p>
                            <p
                                class="text-xs font-bold text-slate-600 leading-snug"
                            >
                                {{ toast.message }}
                            </p>
                        </div>
                        <!-- Close -->
                        <button
                            @click="removeToast(toast.id)"
                            class="flex-shrink-0 text-slate-300 hover:text-slate-500 transition-colors mt-0.5"
                        >
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
        <footer class="bg-slate-50 border-t border-slate-200 pt-16 pb-10">
            <div
                class="max-w-[1600px] mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12"
            >
                <div class="space-y-6">
                    <div v-if="$page.props.settings.site_logo" class="flex items-center">
                        <img
                            :src="$page.props.settings.site_logo"
                            class="h-16 md:h-24 w-auto object-contain mr-3"
                        />
                        <span class="text-3xl font-black tracking-tight text-[#003366]">{{
                            $page.props.settings.site_name
                        }}</span>
                    </div>
                    <div v-else class="flex items-center">
                        <ShoppingBag class="w-8 h-8 text-[#003366] mr-2" />
                        <span class="text-2xl font-bold text-[#003366]">{{
                            $page.props.settings.site_name
                        }}</span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed font-bold">
                        {{ $page.props.settings.footer_about }}
                    </p>
                </div>
                <div>
                    <h3
                        class="font-black text-[#003366] mb-8 uppercase text-xs tracking-widest"
                    >
                        Quick Links
                    </h3>
                    <ul class="space-y-4 text-sm text-slate-500 font-bold">
                        <li>
                            <Link href="/" class="hover:text-[#FF6600]"
                                >Home</Link
                            >
                        </li>
                        <li>
                            <Link href="/shop" class="hover:text-[#FF6600]"
                                >Shop Products</Link
                            >
                        </li>
                        <li>
                            <Link href="/cart" class="hover:text-[#FF6600]"
                                >My Cart</Link
                            >
                        </li>
                        <li>
                            <Link href="/checkout" class="hover:text-[#FF6600]"
                                >Checkout</Link
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h3
                        class="font-black text-[#003366] mb-8 uppercase text-xs tracking-widest"
                    >
                        Store Policy
                    </h3>
                    <ul class="space-y-4 text-sm text-slate-500 font-bold">
                        <li>
                            <a href="#" class="hover:text-[#FF6600]"
                                >Return Policy</a
                            >
                        </li>
                        <li>
                            <a href="#" class="hover:text-[#FF6600]"
                                >Terms of Service</a
                            >
                        </li>
                        <li>
                            <a href="#" class="hover:text-[#FF6600]"
                                >Privacy Policy</a
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h3
                        class="font-black text-[#003366] mb-8 uppercase text-xs tracking-widest"
                    >
                        Contact Info
                    </h3>
                    <ul class="space-y-5 text-sm text-slate-500 font-bold">
                        <li class="flex items-start">
                            <MapPin
                                class="w-5 h-5 mr-3 text-[#FF6600] mt-0.5"
                            />
                            {{ $page.props.settings.footer_address }}
                        </li>
                        <li class="flex items-center">
                            <Phone class="w-5 h-5 mr-3 text-[#FF6600]" />
                            {{ $page.props.settings.footer_phone }}
                        </li>
                        <li class="flex items-center">
                            <Mail class="w-5 h-5 mr-3 text-[#FF6600]" />
                            {{ $page.props.settings.footer_email }}
                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="max-w-[1600px] mx-auto px-4 mt-16 pt-8 border-t border-slate-200 text-center text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]"
            >
                <p>
                    &copy; 2026 {{ $page.props.settings.site_name }}. All rights
                    reserved.
                </p>
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
