<template>
    <div class="flex h-screen bg-slate-50 text-slate-900 font-sans selection:bg-accent-100 selection:text-accent-900">
        <!-- Sidebar -->
        <aside class="w-72 bg-primary-900 text-white flex flex-col transition-all duration-300 shadow-2xl z-30">
            <div class="h-24 flex items-center px-8 border-b border-white/5">
                <Link href="/admin/dashboard" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg group-hover:rotate-6 transition-transform">
                        <span class="text-primary-900 font-black text-xl">S</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-black tracking-tight text-white leading-none">Sadman Cart</span>
                        <span class="text-[10px] font-bold text-primary-400 uppercase tracking-widest mt-1">Management</span>
                    </div>
                </Link>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-8 px-4 custom-scrollbar">
                <div class="mb-4 px-4 text-[10px] font-black text-primary-400 uppercase tracking-widest">Main Menu</div>
                <ul class="space-y-2">
                    <li>
                        <Link href="/admin/dashboard" :class="[$page.url.startsWith('/admin/dashboard') ? 'bg-white/10 text-white shadow-sm' : 'text-primary-200 hover:bg-white/5 hover:text-white']" class="flex items-center px-4 py-3 rounded-xl transition-all group">
                            <LayoutDashboard class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" />
                            <span class="font-bold text-sm">Dashboard</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/orders" :class="[$page.url.startsWith('/admin/orders') ? 'bg-white/10 text-white shadow-sm' : 'text-primary-200 hover:bg-white/5 hover:text-white']" class="flex items-center px-4 py-3 rounded-xl transition-all group">
                            <ShoppingCart class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" />
                            <span class="font-bold text-sm">Orders</span>
                            <span v-if="$page.props.pendingOrdersCount > 0" class="ml-auto bg-accent-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full ring-4 ring-primary-900">{{ $page.props.pendingOrdersCount }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/products" :class="[$page.url.startsWith('/admin/products') ? 'bg-white/10 text-white shadow-sm' : 'text-primary-200 hover:bg-white/5 hover:text-white']" class="flex items-center px-4 py-3 rounded-xl transition-all group">
                            <Package class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" />
                            <span class="font-bold text-sm">Products</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/categories" :class="[$page.url.startsWith('/admin/categories') ? 'bg-white/10 text-white shadow-sm' : 'text-primary-200 hover:bg-white/5 hover:text-white']" class="flex items-center px-4 py-3 rounded-xl transition-all group">
                            <Tags class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" />
                            <span class="font-bold text-sm">Categories</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/subcategories" :class="[$page.url.startsWith('/admin/subcategories') ? 'bg-white/10 text-white shadow-sm' : 'text-primary-200 hover:bg-white/5 hover:text-white']" class="flex items-center px-4 py-3 rounded-xl transition-all group">
                            <ListTree class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" />
                            <span class="font-bold text-sm">Subcategories</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/customers" :class="[$page.url.startsWith('/admin/customers') ? 'bg-white/10 text-white shadow-sm' : 'text-primary-200 hover:bg-white/5 hover:text-white']" class="flex items-center px-4 py-3 rounded-xl transition-all group">
                            <Users class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" />
                            <span class="font-bold text-sm">Customers</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin/settings" :class="[$page.url.startsWith('/admin/settings') ? 'bg-white/10 text-white shadow-sm' : 'text-primary-200 hover:bg-white/5 hover:text-white']" class="flex items-center px-4 py-3 rounded-xl transition-all group">
                            <Settings class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" />
                            <span class="font-bold text-sm">Site Settings</span>
                        </Link>
                    </li>
                </ul>

                <div class="mt-10 mb-4 px-4 text-[10px] font-black text-primary-400 uppercase tracking-widest">Store Settings</div>
                <ul class="space-y-2">
                    <li>
                        <Link href="/" class="flex items-center px-4 py-3 text-primary-200 hover:bg-white/5 hover:text-white rounded-xl transition-all group">
                            <ExternalLink class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" />
                            <span class="font-bold text-sm">View Storefront</span>
                        </Link>
                    </li>
                </ul>
            </nav>

            <div class="p-6 mt-auto">
                <Link href="/admin/logout" method="post" as="button" class="flex items-center w-full px-4 py-3 bg-white/5 hover:bg-red-600/10 text-primary-200 hover:text-red-500 rounded-2xl transition-all font-bold text-sm group">
                    <LogOut class="w-5 h-5 mr-3 group-hover:-translate-x-1 transition-transform" />
                    Logout
                </Link>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="h-24 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-10 sticky top-0 z-20">
                <div class="relative w-96 hidden md:block">
                    <Search class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                    <input type="text" placeholder="Quick search..." class="w-full bg-slate-100 border-none rounded-2xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary-500/20 outline-none transition-all placeholder:text-slate-400 font-medium">
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="flex flex-col text-right hidden sm:flex">
                        <span class="text-sm font-black text-slate-900 leading-none">{{ $page.props.auth?.user?.name || 'Administrator' }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Super Admin</span>
                    </div>
                    <div class="relative group cursor-pointer">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-900 to-primary-700 flex items-center justify-center text-white font-black shadow-xl shadow-primary-900/20 group-hover:scale-105 transition-transform ring-4 ring-white">
                            {{ $page.props.auth?.user?.name ? $page.props.auth.user.name.charAt(0) : 'A' }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-10 custom-scrollbar bg-slate-50/50">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { LayoutDashboard, ShoppingCart, Package, Tags, LogOut, Search, ExternalLink, ListTree, Users } from 'lucide-vue-next';
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 51, 102, 0.1);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 51, 102, 0.2);
}
</style>
