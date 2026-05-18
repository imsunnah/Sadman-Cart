<template>
    <AdminLayout>
        <!-- Title and Search Panel -->
        <div class="mb-10 flex flex-col md:flex-row md:justify-between md:items-center bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 gap-6">
            <div>
                <h1 class="text-4xl font-black text-[#003366] tracking-tighter uppercase italic font-sans">Brand <span class="text-slate-400">Management</span></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1 font-sans">Organize your brands and category associations</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <!-- Search bar -->
                <div class="relative w-full sm:w-64">
                    <input 
                        type="text" 
                        v-model="search" 
                        placeholder="Search brands..." 
                        @input="handleSearch"
                        class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold font-sans text-xs"
                    >
                    <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" />
                </div>
                <Link href="/admin/brands/create" class="w-full sm:w-auto bg-[#003366] text-white px-8 py-4 rounded-2xl hover:bg-slate-800 transition-all font-black text-xs uppercase tracking-[0.2em] flex items-center justify-center shadow-2xl shadow-[#003366]/30 active:scale-95 font-sans">
                    <Plus class="w-4 h-4 mr-3" /> Add Brand
                </Link>
            </div>
        </div>

        <!-- Success Toast -->
        <div v-if="$page.props.flash?.success" class="mb-8 bg-green-500 text-white p-6 rounded-2xl shadow-xl shadow-green-500/20 flex items-center justify-between animate-in fade-in slide-in-from-top-4 duration-500 font-sans">
            <div class="flex items-center space-x-4">
                <CheckCircle class="w-6 h-6" />
                <span class="text-xs font-black uppercase tracking-widest">{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="opacity-50 hover:opacity-100 transition-opacity"><XCircle class="w-5 h-5" /></button>
        </div>

        <!-- Brands Table -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden font-sans">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                            <th class="py-6 px-8 text-center w-24">ID</th>
                            <th class="py-6 px-8">Brand Info</th>
                            <th class="py-6 px-8">Dynamic Categories</th>
                            <th class="py-6 px-8 text-center">Status</th>
                            <th class="py-6 px-8 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="brand in brands.data" :key="brand.id" class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-6 px-8 text-center">
                                <span class="text-[10px] font-black text-slate-300 font-mono tracking-tighter">#{{ String(brand.id).padStart(3, '0') }}</span>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center">
                                        <img v-if="brand.image" :src="brand.image" class="w-full h-full object-contain" />
                                        <Layers v-else class="w-5 h-5 text-slate-300" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900 uppercase tracking-tight italic">{{ brand.name }}</div>
                                        <div class="text-[9px] text-slate-400 font-mono tracking-tighter mt-0.5">{{ brand.slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="cat in brand.categories" :key="cat.id" class="text-[9px] font-black text-[#003366] bg-blue-50/50 border border-blue-100/50 px-2 py-0.5 rounded-full uppercase tracking-wider">
                                        {{ cat.name }}
                                    </span>
                                    <span v-if="brand.categories.length === 0" class="text-[9px] text-slate-300 font-bold uppercase tracking-wider italic">
                                        Universal (No Limit)
                                    </span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <button 
                                    @click="toggleActive(brand.id)"
                                    :class="brand.is_active ? 'text-green-500 bg-green-50 hover:bg-green-100' : 'text-slate-300 bg-slate-50 hover:bg-slate-100'" 
                                    class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-current opacity-80 transition-all active:scale-95"
                                >
                                    {{ brand.is_active ? 'Active' : 'Archived' }}
                                </button>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex justify-end space-x-4">
                                    <Link :href="`/admin/brands/${brand.id}/edit`" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-[#003366] hover:text-white transition-all flex items-center justify-center shadow-sm hover:shadow-xl hover:shadow-[#003366]/20">
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button @click="confirmDelete(brand.id)" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center shadow-sm hover:shadow-xl hover:shadow-red-600/20">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="brands.data.length === 0">
                            <td colspan="5" class="py-24 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <Award class="w-10 h-10 text-slate-200" />
                                </div>
                                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">No Brands Found</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">Adjust your keywords or establish a new brand above.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div v-if="brands.last_page > 1" class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-center space-x-2">
                <button v-for="link in brands.links" :key="link.label" 
                    @click="link.url && router.visit(link.url)"
                    v-html="link.label"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                    :class="[
                        link.active ? 'bg-[#003366] text-white shadow-lg' : 'bg-white text-slate-400 hover:bg-slate-100',
                        !link.url ? 'opacity-30 cursor-not-allowed' : ''
                    ]"
                ></button>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showDeleteModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] p-12 max-w-sm w-full shadow-2xl border border-white/20 font-sans">
                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <AlertTriangle class="w-10 h-10" />
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 text-center mb-4 uppercase tracking-tighter italic">Delete Brand?</h3>
                    <p class="text-xs font-medium text-slate-400 text-center mb-10 leading-relaxed px-4">Erasing this brand will detach all dynamic categories and set associated products to uncategorized brands.</p>
                    <div class="grid grid-cols-1 gap-3">
                        <button @click="deleteBrand" class="px-8 py-5 rounded-2xl bg-red-600 text-white font-black text-[10px] uppercase tracking-[0.2em] hover:bg-red-700 transition-all shadow-xl shadow-red-600/20 active:scale-95">
                            Confirm Deletion
                        </button>
                        <button @click="showDeleteModal = false" class="px-8 py-5 rounded-2xl bg-slate-50 text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-100 transition-all active:scale-95">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus, Edit2, Trash2, AlertTriangle, CheckCircle, XCircle, Award, Search, Layers } from 'lucide-vue-next';

const props = defineProps({
    brands: Object,
    searchFilter: String
});

const search = ref(props.searchFilter);
const showDeleteModal = ref(false);
const brandToDelete = ref(null);

// Custom zero-dependency inline debounce helper
const customDebounce = (fn, delay) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn(...args), delay);
    };
};

const handleSearch = customDebounce(() => {
    router.visit('/admin/brands', {
        data: { search: search.value },
        preserveState: true,
        preserveScroll: true
    });
}, 300);

const toggleActive = (id) => {
    router.put(`/admin/brands/${id}/toggle-active`, {}, {
        preserveScroll: true
    });
};

const confirmDelete = (id) => {
    brandToDelete.value = id;
    showDeleteModal.value = true;
};

const deleteBrand = () => {
    router.delete(`/admin/brands/${brandToDelete.value}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
};
</script>
