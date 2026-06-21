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
                            <div v-if="!$page.props.auth.user" class="flex items-center gap-2">
                                <Link href="/login" class="hover:text-[#FF6600] transition-colors">{{ $t('Login') }}</Link>
                                <span class="text-slate-500">/</span>
                                <Link href="/register" class="hover:text-[#FF6600] transition-colors">{{ $t('Register') }}</Link>
                            </div>
                            <div v-else class="flex items-center gap-4">
                                <span class="text-slate-400 hidden sm:inline">{{ $page.props.auth.user.name }}</span>
                                <Link href="/customer/orders" class="hover:text-[#FF6600] transition-colors">{{ $t('My Orders') }}</Link>
                                <Link href="/logout" method="post" as="button" class="hover:text-[#FF6600] transition-colors">{{ $t('Logout') }}</Link>
                                <Link v-if="$page.props.auth.user?.role === 'admin'" href="/admin/dashboard" class="text-[#FF6600] font-black italic">{{ $t('Admin') }}</Link>
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
                            @input="handleLiveSearch"
                            @focus="showSuggestions = true"
                            :placeholder="$t('Find your selection...')"
                            class="w-full pl-12 pr-12 py-4 bg-white border-2 border-slate-100 rounded-2xl text-sm font-bold focus:border-[#FF6600] focus:ring-4 focus:ring-[#FF6600]/5 outline-none transition-all shadow-sm group-focus-within:shadow-xl"
                        />
                        <Search class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#FF6600] transition-colors" />
                        
                        <!-- Search Icon at Right (matching user image) -->
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#FF6600]">
                            <Search class="w-5 h-5" />
                        </div>

                        <!-- Unified Search Suggestions Dropdown -->
                        <div v-if="showSuggestions && searchQuery.length >= 2" 
                             class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-[100] animate-in fade-in slide-in-from-top-2 duration-300 ring-1 ring-slate-100">
                            
                            <div class="divide-y divide-slate-50">
                                <!-- All results (Products & Combos merged for unified look) -->
                                <Link 
                                    v-for="item in [...suggestions.products, ...suggestions.combos]" 
                                    :key="item.type + '-' + item.id" 
                                    :href="item.type === 'product' ? `/products/${item.slug}` : `/combos/${item.slug}`"
                                    @click="showSuggestions = false"
                                    class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-all group"
                                >
                                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-50 flex-shrink-0 border border-slate-100">
                                        <img :src="item.image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-[13px] font-bold text-slate-800 truncate mb-0.5 group-hover:text-[#FF6600] transition-colors">
                                            {{ page.props.locale === 'bn' ? item.name_bn : item.name_en }}
                                        </p>
                                        <div class="flex flex-col">
                                            <span class="text-[12px] font-black text-[#FF6600]">৳{{ item.discounted_price }}</span>
                                            <span v-if="item.brand_name" class="text-[10px] font-medium text-slate-400 mt-0.5">{{ item.brand_name }}</span>
                                        </div>
                                    </div>
                                </Link>

                                <!-- No Results Logic -->
                                <div v-if="suggestions.products.length === 0 && suggestions.combos.length === 0" class="p-8 text-center bg-slate-50/30">
                                    <Search class="w-10 h-10 text-slate-200 mx-auto mb-3" />
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                        {{ page.props.locale === 'bn' ? 'দুঃখিত, কোনো পণ্য পাওয়া যায়নি' : 'Sorry, No Products Found' }}
                                    </p>
                                </div>
                            </div>
                        </div>
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
                        @input="handleLiveSearch"
                        @focus="showSuggestions = true"
                        :placeholder="$t('Search...')"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none"
                    />
                    <Search class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />

                    <!-- Mobile Search Suggestions Dropdown -->
                    <div v-if="showSuggestions && searchQuery.length >= 2" 
                         class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-[100] animate-in fade-in slide-in-from-top-2 duration-300">
                        
                        <!-- Products Section -->
                        <div v-if="suggestions.products.length > 0" class="p-2">
                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest px-4 py-2">{{ $t('Products') }}</p>
                            <div class="space-y-1">
                                <Link 
                                    v-for="item in suggestions.products" 
                                    :key="'mp-'+item.id" 
                                    :href="`/products/${item.slug}`"
                                    @click="showSuggestions = false"
                                    class="flex items-center gap-3 p-3 hover:bg-slate-50 rounded-xl transition-colors group"
                                >
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-100">
                                        <img :src="item.image" class="w-full h-full object-cover" />
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-[11px] font-bold text-slate-700 truncate">
                                            {{ page.props.locale === 'bn' ? item.name_bn : item.name_en }}
                                        </p>
                                        <p class="text-[10px] font-black text-[#FF6600]">৳{{ item.discounted_price }}</p>
                                    </div>
                                </Link>
                            </div>
                        </div>

                        <!-- Combos Section -->
                        <div v-if="suggestions.combos.length > 0" class="p-2 border-t border-slate-50">
                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest px-4 py-2">{{ $t('Combos') }}</p>
                            <div class="space-y-1">
                                <Link 
                                    v-for="item in suggestions.combos" 
                                    :key="'mc-'+item.id" 
                                    :href="`/combos/${item.slug}`"
                                    @click="showSuggestions = false"
                                    class="flex items-center gap-3 p-3 hover:bg-slate-50 rounded-xl transition-colors group"
                                >
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-100">
                                        <img :src="item.image" class="w-full h-full object-cover" />
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-[11px] font-bold text-slate-700 truncate">
                                            {{ page.props.locale === 'bn' ? item.name_bn : item.name_en }}
                                        </p>
                                        <p class="text-[10px] font-black text-[#FF6600]">৳{{ item.discounted_price }}</p>
                                    </div>
                                </Link>
                            </div>
                        </div>

                        <!-- No Results for Mobile -->
                        <div v-if="suggestions.products.length === 0 && suggestions.combos.length === 0" class="p-6 text-center border-t border-slate-50">
                            <Search class="w-8 h-8 text-slate-200 mx-auto mb-2" />
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                {{ page.props.locale === 'bn' ? 'কোন ফলাফল পাওয়া যায়নি' : 'No results found' }}
                            </p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Desktop Nav (Bold & Prominent) -->
            <nav class="bg-[#003366] hidden md:block border-y border-white/5">
                <div class="max-w-[1550px] mx-auto px-4 py-5 flex justify-center items-center gap-12">
                    <!-- Shop Link (Always First) -->
                    <Link 
                        href="/shop"
                        :class="[$page.url === '/shop' ? 'text-[#FF6600]' : 'text-white hover:text-[#FF6600]']"
                        class="text-[11px] font-black uppercase tracking-[0.3em] transition-all drop-shadow-sm"
                    >
                        {{ $page.props.locale === 'bn' ? 'শপ' : 'Shop' }}
                    </Link>

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
                                {{ $t('More') }} →
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
                <span class="text-[9px] font-black text-white uppercase tracking-tight leading-none">{{ cartCount }} {{ $t('Items') }}</span>
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
            href="https://m.me/61588509596175"
            target="_blank" 
            class="fixed bottom-8 right-8 z-[90] flex items-center gap-3 bg-white pl-5 pr-2 py-2 rounded-full shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] hover:shadow-[0_20px_50px_-10px_rgba(0,0,0,0.25)] hover:-translate-y-1 transition-all group border border-slate-100"
        >
            <div class="flex flex-col items-end">
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">{{ $t('Online Support') }}</span>
                <span class="text-[10px] font-black text-[#003366] uppercase tracking-wider leading-none group-hover:text-[#FF6600] transition-colors">{{ $t('chat with us') }}</span>
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
                            <span class="text-xl font-black text-[#003366] uppercase italic">{{ $t('Menu') }}</span>
                        </div>
                        <button @click="isMobileMenuOpen = false" class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 hover:text-[#FF6600] transition-colors">
                            <X class="w-6 h-6" />
                        </button>
                    </div>

                    <div class="flex-grow space-y-2 overflow-y-auto">
                        <Link href="/" @click="isMobileMenuOpen = false" class="block py-4 text-2xl font-black italic uppercase tracking-tighter text-[#003366] border-b border-slate-50">{{ $t('Home') }}</Link>
                        <Link href="/shop" @click="isMobileMenuOpen = false" class="block py-4 text-2xl font-black italic uppercase tracking-tighter text-[#003366] border-b border-slate-50">{{ $page.props.locale === 'bn' ? 'শপ' : 'Shop' }}</Link>
                        
                        <div class="py-6">
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-6">{{ $t('Collections') }}</p>
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
                        <div class="flex flex-col gap-3">
                            <div v-if="!$page.props.auth.user" class="grid grid-cols-2 gap-3">
                                <Link href="/login" @click="isMobileMenuOpen = false" class="w-full py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs uppercase tracking-widest text-center block">{{ $t('Login') }}</Link>
                                <Link href="/register" @click="isMobileMenuOpen = false" class="w-full py-3.5 bg-[#003366] text-white hover:bg-slate-800 rounded-xl font-bold text-xs uppercase tracking-widest text-center block">{{ $t('Register') }}</Link>
                            </div>
                            <div v-else class="flex flex-col gap-2 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-xs font-black text-[#003366] uppercase tracking-wider block mb-1">{{ $t('Hello') }}, {{ $page.props.auth.user.name }}</span>
                                <div class="flex gap-2">
                                    <Link href="/customer/orders" @click="isMobileMenuOpen = false" class="flex-grow py-2.5 bg-[#003366] text-white rounded-lg text-[10px] font-black uppercase tracking-wider text-center">{{ $t('My Orders') }}</Link>
                                    <Link href="/logout" method="post" as="button" @click="isMobileMenuOpen = false" class="flex-grow py-2.5 bg-slate-200 text-slate-700 rounded-lg text-[10px] font-black uppercase tracking-wider text-center">{{ $t('Logout') }}</Link>
                                </div>
                            </div>
                        </div>

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
                        <Link href="/cart" @click="isMobileMenuOpen = false" class="w-full py-5 bg-[#FF6600] text-white rounded-2xl font-black text-xs uppercase tracking-widest text-center block shadow-xl shadow-orange-500/20">{{ $t('View My Cart') }}</Link>
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
                            {{ toast.type === "success" ? $t("Added to Cart") : (toast.type === "error" ? $t("Error") : $t("Notice")) }}
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
                        <template v-if="$page.props.settings.social_media && $page.props.settings.social_media.length > 0">
                            <a v-for="(social, index) in $page.props.settings.social_media.filter(s => s.is_active)" :key="index" :href="social.url" target="_blank" class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-slate-400 hover:bg-[#FF6600] hover:text-white transition-all">
                                <component :is="getIcon(social.icon)" class="w-4 h-4" />
                            </a>
                        </template>
                  
                    </div>
                </div>

                <!-- Information -->
                <div>
                    <h3 class="font-black text-white mb-8 uppercase text-[10px] tracking-[0.2em] italic border-b border-white/10 pb-2">{{ $t('Information') }}</h3>
                    <ul class="space-y-4 text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <li v-for="page in ($page.props.activePages || []).filter(p => p.group === 'about_us')" :key="page.id">
                            <Link :href="`/pages/${page.slug}`" class="hover:text-[#FF6600] transition-colors">{{ page.title }}</Link>
                        </li>
                    </ul>
                </div>

                <!-- Shop By -->
                <div>
                    <h3 class="font-black text-white mb-8 uppercase text-[10px] tracking-[0.2em] italic border-b border-white/10 pb-2">{{ $t('Shop By') }}</h3>
                    <ul class="space-y-4 text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <li v-for="cat in $page.props.categories.slice(0, 5)" :key="cat.id">
                            <Link :href="`/shop?category=${cat.slug}`" class="hover:text-[#FF6600] transition-colors">{{ cat.name }}</Link>
                        </li>
                        <li><Link href="/shop" class="hover:text-[#FF6600] transition-colors font-black text-[#FF6600]">{{ $t('View All Shop') }}</Link></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="font-black text-white mb-8 uppercase text-[10px] tracking-[0.2em] italic border-b border-white/10 pb-2">{{ $t('Support') }}</h3>
                    <ul class="space-y-4 text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <li v-for="page in ($page.props.activePages || []).filter(p => p.group === 'support')" :key="page.id">
                            <Link :href="`/pages/${page.slug}`" class="hover:text-[#FF6600] transition-colors">{{ page.title }}</Link>
                        </li>
                        <li>
                            <Link href="/reviews" class="hover:text-[#FF6600] transition-colors">{{ $t('Customer Reviews') }}</Link>
                        </li>
                    </ul>
                </div>

                <!-- Consumer Policy -->
                <div>
                    <h3 class="font-black text-white mb-8 uppercase text-[10px] tracking-[0.2em] italic border-b border-white/10 pb-2">{{ $t('Consumer Policy') }}</h3>
                    <ul class="space-y-4 text-[10px] text-slate-300 font-bold uppercase tracking-widest">
                        <li v-for="page in ($page.props.activePages || []).filter(p => p.group === 'consumer_policy')" :key="page.id">
                            <Link :href="`/pages/${page.slug}`" class="hover:text-[#FF6600] transition-colors">{{ page.title }}</Link>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="max-w-[1550px] mx-auto px-4 mt-20 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.3em]">
                    &copy; 2026 {{ $page.props.settings.site_name }}. {{ $t('All Rights Reserved.') }}
                </p>
                <div class="flex items-center gap-2">
                    <ShieldCheck class="w-4 h-4 text-[#FF6600]" />
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest italic">{{ $t('Premium Verified Platform') }}</span>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
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
    ShieldCheck,
    Twitter,
    Youtube,
    Linkedin,
    Globe,
    AtSign
} from "lucide-vue-next";
import { useCart } from "@/Composables/useCart";
import { useToast } from "@/Composables/useToast";
import CartDrawer from "@/Components/CartDrawer.vue";

const { cartCount, cartTotal } = useCart();
const { toasts, removeToast } = useToast();
const page = usePage();
const isMobileMenuOpen = ref(false);
const isCartOpen = ref(false);
const searchQuery = ref("");
const showSuggestions = ref(false);
const suggestions = ref({ products: [], combos: [] });

import axios from "axios";
import { onMounted, onUnmounted } from "vue";

const fetchSuggestions = async () => {
    if (searchQuery.value.length < 2) {
        suggestions.value = { products: [], combos: [] };
        return;
    }
    try {
        const response = await axios.get('/api/search-suggestions', { params: { search: searchQuery.value } });
        suggestions.value = response.data;
    } catch (error) {
        console.error("Search fetch failed", error);
    }
}

const handleClickOutside = (e) => {
    if (!e.target.closest('.group')) {
        showSuggestions.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

const handleSearch = () => {
    if (searchQuery.value.trim()) {
        router.visit(`/shop?search=${encodeURIComponent(searchQuery.value)}`);
    } else {
        router.visit("/shop");
    }
};

let liveSearchTimeout = null;
const handleLiveSearch = () => {
    // Instant suggestion fetch - no debounce
    fetchSuggestions();
    showSuggestions.value = true;

    // Keep debounce only for the heavier shop-page filter request
    if (liveSearchTimeout) clearTimeout(liveSearchTimeout);
    liveSearchTimeout = setTimeout(() => {
        if (page.url.startsWith('/shop')) {
            const currentFilters = page.props.filters || {};
            router.get('/shop', {
                ...currentFilters,
                search: searchQuery.value
            }, {
                preserveState: true,
                preserveScroll: true,
                only: ['products', 'filters']
            });
        }
    }, 300);
};

const getIcon = (iconName) => {
    const icons = {
        Facebook,
        Instagram,
        Twitter,
        Youtube,
        MessageCircle,
        Linkedin,
        Globe,
        AtSign
    };
    return icons[iconName] || Globe;
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
