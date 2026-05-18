<template>
    <div class="flex h-screen bg-[#f8fafc] text-slate-900 font-sans relative">
        <!-- Sidebar -->
        <aside :class="['w-64 bg-[#0f172a] text-white flex flex-col transition-transform duration-300 z-[60] flex-shrink-0 fixed inset-y-0 left-0 md:relative', isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0']">
            <div class="h-20 flex items-center px-6 border-b border-slate-800">
                <Link href="/admin/dashboard" class="flex items-center space-x-3 group">
                    <div class="w-8 h-8 bg-white rounded-md flex items-center justify-center">
                        <span class="text-[#0f172a] font-black text-lg">S</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base font-extrabold tracking-tight text-white leading-none">Sadman Cart</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Admin</span>
                    </div>
                </Link>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-6 px-3 custom-scrollbar">
                <div class="mb-3 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('Main Menu') }}</div>
                <ul class="space-y-1">
                    <li>
                        <Link href="/admin/dashboard" :class="[$page.url.startsWith('/admin/dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <LayoutDashboard class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Dashboard') }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/orders" :class="[$page.url.startsWith('/admin/orders') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <ShoppingCart class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/orders') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Orders') }}</span>
                            <span v-if="$page.props.pendingOrdersCount > 0" class="ml-auto bg-orange-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $page.props.pendingOrdersCount }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/products" :class="[$page.url.startsWith('/admin/products') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <Package class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/products') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Products') }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/combos" :class="[$page.url.startsWith('/admin/combos') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <Zap class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/combos') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Combos') }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/reviews" :class="[$page.url.startsWith('/admin/reviews') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <Star class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/reviews') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Reviews') }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/categories" :class="[$page.url.startsWith('/admin/categories') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <Tags class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/categories') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Categories') }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/brands" :class="[$page.url.startsWith('/admin/brands') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <Award class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/brands') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Brands') }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/subcategories" :class="[$page.url.startsWith('/admin/subcategories') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <ListTree class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/subcategories') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Subcategories') }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/customers" :class="[$page.url.startsWith('/admin/customers') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <Users class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/customers') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Customers') }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/gallery" :class="[$page.url.startsWith('/admin/gallery') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <Image class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/gallery') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Media Gallery') }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/pages" :class="[$page.url.startsWith('/admin/pages') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <FileText class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/pages') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Dynamic Pages') }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/settings" :class="[$page.url.startsWith('/admin/settings') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                            <Settings class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/settings') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                            <span class="font-bold text-sm">{{ $t('Site Settings') }}</span>
                        </Link>
                    </li>
                </ul>

                <div class="mt-8 mb-3 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('Storefront') }}</div>
                <ul class="space-y-1">
                    <li>
                        <Link href="/" class="flex items-center px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                            <ExternalLink class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white" />
                            <span class="font-bold text-sm">{{ $t('View Store') }}</span>
                        </Link>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <Link href="/admin/logout" method="post" as="button" class="flex items-center w-full px-3 py-2.5 text-slate-400 hover:bg-red-500/10 hover:text-red-400 rounded-lg transition-colors font-bold text-sm group">
                    <LogOut class="w-5 h-5 mr-3 text-slate-500 group-hover:text-red-400" />
                    {{ $t('Logout') }}
                </Link>
            </div>
        </aside>

        <!-- Overlay for mobile sidebar -->
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-[50] md:hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8 z-20 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="isSidebarOpen = true" class="md:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg">
                        <Menu class="w-6 h-6" />
                    </button>
                    <div class="relative w-72 hidden lg:block">
                        <Search class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                        <input type="text" placeholder="Search..." class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 pl-10 pr-4 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors placeholder:text-slate-400 font-medium text-slate-900">
                    </div>
                </div>
                
                <div class="flex items-center space-x-5">
                    <div class="flex items-center space-x-2">
                        <a href="/language/en" :class="$page.props.locale === 'en' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:bg-slate-100'" class="px-2 py-1 rounded text-xs font-bold transition-colors">EN</a>
                        <span class="text-slate-300">|</span>
                        <a href="/language/bn" :class="$page.props.locale === 'bn' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:bg-slate-100'" class="px-2 py-1 rounded text-xs font-bold transition-colors">BN</a>
                    </div>
                    <div class="flex flex-col text-right hidden sm:flex">
                        <span class="text-sm font-extrabold text-slate-900 leading-none">{{ $page.props.auth?.user?.name || 'Administrator' }}</span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Super Admin</span>
                    </div>
                    <div class="relative cursor-pointer">
                        <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-extrabold shadow-sm border border-indigo-700">
                            {{ $page.props.auth?.user?.name ? $page.props.auth.user.name.charAt(0) : 'A' }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 custom-scrollbar">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { LayoutDashboard, ShoppingCart, Package, Tags, LogOut, Search, ExternalLink, ListTree, Users, Settings, Menu, Zap, Star, Image, FileText, Award } from 'lucide-vue-next';

const isSidebarOpen = ref(false);
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
