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
    <!-- ==================== CORE METRICS ==================== -->
    <div class="mb-3 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('Core') }}</div>
    <ul class="mb-6 space-y-1">
        <li>
            <Link href="/admin/dashboard" :class="[$page.url.startsWith('/admin/dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                <LayoutDashboard class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                <span class="font-bold text-sm">{{ $t('Dashboard') }}</span>
            </Link>
        </li>
    </ul>

    <!-- ==================== SALES & ORDERS ==================== -->
    <div class="mb-3 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('Sales') }}</div>
    <ul class="mb-6 space-y-1">
        <li>
            <Link href="/admin/orders" :class="[$page.url.startsWith('/admin/orders') && !$page.url.startsWith('/admin/orders/courier') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                <ShoppingCart class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/orders') && !$page.url.startsWith('/admin/orders/courier') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                <span class="font-bold text-sm">{{ $t('Orders') }}</span>
                <span v-if="$page.props.pendingOrdersCount > 0" class="ml-auto bg-orange-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $page.props.pendingOrdersCount }}</span>
            </Link>
        </li>
        <li>
            <Link :href="route('admin.orders.courier')" :class="[$page.url.startsWith('/admin/orders/courier') ? 'bg-[#FF6600] text-white shadow-lg shadow-orange-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#003366]']" class="flex items-center px-6 py-4 rounded-2xl transition-all group border border-transparent hover:border-slate-100">
                <Truck class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/orders/courier') ? 'text-white' : 'text-slate-400 group-hover:text-[#003366]']" />
                <span class="font-black text-xs uppercase tracking-widest">{{ $t('Steadfast Status') }}</span>
            </Link>
        </li>
    </ul>

    <!-- ==================== INVENTORY & CATALOG ==================== -->
    <div class="mb-3 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('Catalog') }}</div>
    <ul class="mb-6 space-y-1">
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
            <Link href="/admin/categories" :class="[$page.url.startsWith('/admin/categories') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                <Tags class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/categories') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                <span class="font-bold text-sm">{{ $t('Categories') }}</span>
            </Link>
        </li>
        <li>
            <Link href="/admin/subcategories" :class="[$page.url.startsWith('/admin/subcategories') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                <ListTree class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/subcategories') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                <span class="font-bold text-sm">{{ $t('Subcategories') }}</span>
            </Link>
        </li>
        <li>
            <Link href="/admin/brands" :class="[$page.url.startsWith('/admin/brands') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                <Award class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/brands') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                <span class="font-bold text-sm">{{ $t('Brands') }}</span>
            </Link>
        </li>
    </ul>

    <!-- ==================== CUSTOMER RELATIONSHIP ==================== -->
    <div class="mb-3 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('Customers') }}</div>
    <ul class="mb-6 space-y-1">
        <li>
            <Link href="/admin/customers" :class="[$page.url.startsWith('/admin/customers') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                <Users class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/customers') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                <span class="font-bold text-sm">{{ $t('Customers') }}</span>
            </Link>
        </li>
        <li>
            <Link href="/admin/chat" :class="[$page.url.startsWith('/admin/chat') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                <MessageSquare class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/chat') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                <span class="font-bold text-sm">{{ $t('Support Inbox') }}</span>
                <span v-if="$page.props.unreadChatCount > 0" class="ml-auto bg-orange-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $page.props.unreadChatCount }}</span>
            </Link>
        </li>
        <li>
            <Link href="/admin/reviews" :class="[$page.url.startsWith('/admin/reviews') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                <Star class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/reviews') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                <span class="font-bold text-sm">{{ $t('Reviews') }}</span>
            </Link>
        </li>
    </ul>

    <!-- ==================== CONTENT & SITE MANAGEMENT ==================== -->
    <div class="mb-3 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('Content') }}</div>
    <ul class="mb-6 space-y-1">
        <li>
            <Link href="/admin/pages" :class="[$page.url.startsWith('/admin/pages') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                <FileText class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/pages') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                <span class="font-bold text-sm">{{ $t('Dynamic Pages') }}</span>
            </Link>
        </li>
        <li>
            <Link href="/admin/gallery" :class="[$page.url.startsWith('/admin/gallery') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                <Image class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/gallery') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                <span class="font-bold text-sm">{{ $t('Media Gallery') }}</span>
            </Link>
        </li>
    </ul>

    <!-- ==================== SETTINGS & LIVE VIEW ==================== -->
    <div class="mb-3 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $t('System') }}</div>
    <ul class="space-y-1">
        <li>
            <Link href="/admin/settings" :class="[$page.url.startsWith('/admin/settings') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white']" class="flex items-center px-3 py-2.5 rounded-lg transition-colors group">
                <Settings class="w-5 h-5 mr-3" :class="[$page.url.startsWith('/admin/settings') ? 'text-white' : 'text-slate-400 group-hover:text-white']" />
                <span class="font-bold text-sm">{{ $t('Site Settings') }}</span>
            </Link>
        </li>
        <li>
            <Link href="/" target="_blank" class="flex items-center px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
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
                    <!-- Steadfast Balance Badge -->
                    <div v-if="steadfastBalance !== null" class="hidden sm:flex items-center gap-2 px-3 py-2 bg-orange-50 border border-orange-100 rounded-lg text-[#FF6600] transition-all hover:bg-orange-100 group" title="Steadfast Account Balance">
                        <Truck class="w-4 h-4 text-orange-500 group-hover:scale-110 transition-transform" />
                        <div class="flex flex-col leading-none">
                            <span class="text-[8px] font-black uppercase tracking-widest opacity-60">Balance</span>
                            <span class="text-xs font-black tracking-tight">৳{{ parseFloat(steadfastBalance).toLocaleString() }}</span>
                        </div>
                    </div>

                    <Link href="/admin/chat" class="flex items-center gap-2 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-lg text-indigo-700 transition-colors text-xs font-black uppercase tracking-wider relative" title="Support Inbox">
                        <MessageSquare class="w-4 h-4 text-indigo-600" />
                        <span>Support Chat</span>
                        <span v-if="$page.props.unreadChatCount > 0" class="absolute -top-1.5 -right-1.5 w-4.5 h-4.5 bg-orange-500 text-white text-[8px] font-black rounded-full flex items-center justify-center border border-white">{{ $page.props.unreadChatCount }}</span>
                    </Link>
                    <div class="flex items-center space-x-2">
                        <a href="/language/en" :class="$page.props.locale === 'en' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:bg-slate-100'" class="px-2 py-1 rounded text-xs font-bold transition-colors">EN</a>
                        <span class="text-slate-300">|</span>
                        <a href="/language/bn" :class="$page.props.locale === 'bn' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:bg-slate-100'" class="px-2 py-1 rounded text-xs font-bold transition-colors">BN</a>
                    </div>
                    <div class="relative">
                        <div @click="isProfileOpen = !isProfileOpen" class="flex items-center space-x-3 cursor-pointer group">
                            <div class="flex flex-col text-right hidden sm:flex">
                                <span class="text-sm font-extrabold text-slate-900 leading-none group-hover:text-indigo-600 transition-colors">{{ $page.props.auth?.user?.name || 'Administrator' }}</span>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Super Admin</span>
                            </div>
                            <div class="relative">
                                <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-extrabold shadow-sm border border-indigo-700 active:scale-95 transition-transform">
                                    {{ $page.props.auth?.user?.name ? $page.props.auth.user.name.charAt(0) : 'A' }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <transition name="fade">
                            <div v-if="isProfileOpen" class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-3 z-[100]">
                                <div class="px-4 py-2 border-b border-slate-50 mb-2">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Account</p>
                                </div>
                                <button @click="showPassModal = true; isProfileOpen = false" class="w-full px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-3 transition-colors">
                                    <Lock class="w-4 h-4 text-slate-400" /> Change Password
                                </button>
                                <Link href="/admin/logout" method="post" as="button" class="w-full px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 flex items-center gap-3 transition-colors">
                                    <LogOut class="w-4 h-4 text-red-400" /> Logout
                                </Link>
                            </div>
                        </transition>
                    </div>
                </div>
            </header>

            <!-- Password Change Modal -->
            <transition name="fade">
                <div v-if="showPassModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative overflow-hidden border border-slate-100">
                        <button @click="showPassModal = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors">
                            <X class="w-5 h-5" />
                        </button>
                        
                        <div class="flex items-center gap-3 mb-8">
                            <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600">
                                <Lock class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-xl font-extrabold text-slate-900 leading-none">Security Settings</h3>
                                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-1.5">Update your password</p>
                            </div>
                        </div>

                        <div v-if="passwordForm.wasSuccessful" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-xs font-bold flex items-center gap-3 animate-fade-in-down">
                            <CheckCircle class="w-5 h-5" /> Password updated successfully!
                        </div>

                        <form @submit.prevent="updatePassword" class="space-y-5">
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Current Password</label>
                                <input v-model="passwordForm.current_password" type="password" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-500/5 focus:bg-white focus:border-indigo-500/20 outline-none transition-all" placeholder="••••••••">
                                <p v-if="passwordForm.errors.current_password" class="text-[10px] text-red-500 mt-1.5 font-bold ml-1">{{ passwordForm.errors.current_password }}</p>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">New Password</label>
                                <input v-model="passwordForm.password" type="password" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-500/5 focus:bg-white focus:border-indigo-500/20 outline-none transition-all" placeholder="••••••••">
                                <p v-if="passwordForm.errors.password" class="text-[10px] text-red-500 mt-1.5 font-bold ml-1">{{ passwordForm.errors.password }}</p>
                            </div>

                            <div class="pb-2">
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Confirm New Password</label>
                                <input v-model="passwordForm.password_confirmation" type="password" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-500/5 focus:bg-white focus:border-indigo-500/20 outline-none transition-all" placeholder="••••••••">
                            </div>

                            <button :disabled="passwordForm.processing" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-indigo-600/20 hover:bg-slate-900 transition-all transform active:scale-[0.98] disabled:opacity-50">
                                {{ passwordForm.processing ? 'Updating...' : 'Update Password' }}
                            </button>
                        </form>
                    </div>
                </div>
            </transition>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 custom-scrollbar">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { LayoutDashboard, ShoppingCart, Package, Tags, LogOut, Search, ExternalLink, ListTree, Users, Settings, Menu, Zap, Star, Image, FileText, Award, MessageSquare, Truck, Lock, X, CheckCircle } from 'lucide-vue-next';
import axios from 'axios';

const isSidebarOpen = ref(false); 
const isProfileOpen = ref(false);
const showPassModal = ref(false);
const steadfastBalance = ref(null);

const fetchBalance = async () => {
    try {
        const response = await axios.get(route('admin.steadfast-balance'));
        if (response.data && response.data.status == 200) {
            steadfastBalance.value = response.data.current_balance;
        }
    } catch (error) {
        console.error('Error fetching Steadfast balance:', error);
    }
};

onMounted(() => {
    fetchBalance();
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    passwordForm.post(route('password.change'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            setTimeout(() => {
                showPassModal.value = false;
                passwordForm.wasSuccessful = false;
            }, 2000);
        }
    });
};
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

/* Animations */
.fade-enter-active, .fade-leave-active {
    transition: all 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

@keyframes fade-in-down {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-down {
    animation: fade-in-down 0.3s ease-out;
}
</style>
