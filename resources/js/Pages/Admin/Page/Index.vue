<template>
    <AdminLayout>
        <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div>
                <h1 class="text-4xl font-black text-primary-900 tracking-tighter uppercase italic">{{ $t('Dynamic') }} <span class="text-slate-400">{{ $t('Pages') }}</span></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">{{ $t('Manage storefront pages, terms, and footer links') }}</p>
            </div>
            <Link href="/admin/pages/create" class="bg-primary-900 text-white px-8 py-4 rounded-2xl hover:bg-slate-800 transition-all font-black text-xs uppercase tracking-[0.2em] flex items-center shadow-2xl shadow-primary-900/30 active:scale-95">
                <Plus class="w-4 h-4 mr-3" /> {{ $t('Create Custom Page') }}
            </Link>
        </div>

        <div v-if="$page.props.flash?.success" class="mb-8 bg-green-500 text-white p-6 rounded-2xl shadow-xl shadow-green-500/20 flex items-center justify-between animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="flex items-center space-x-4">
                <CheckCircle class="w-6 h-6" />
                <span class="text-xs font-black uppercase tracking-widest">{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="opacity-50 hover:opacity-100 transition-opacity"><XCircle class="w-5 h-5" /></button>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                            <th class="py-6 px-8 text-center w-24">{{ $t('ID') }}</th>
                            <th class="py-6 px-8">{{ $t('Title') }}</th>
                            <th class="py-6 px-8">{{ $t('Group / Category') }}</th>
                            <th class="py-6 px-8">{{ $t('Slug URL') }}</th>
                            <th class="py-6 px-8 text-center">{{ $t('Status') }}</th>
                            <th class="py-6 px-8 text-right">{{ $t('Operations') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="page in pages.data" :key="page.id" class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-6 px-8 text-center">
                                <span class="text-[10px] font-black text-slate-300 font-mono tracking-tighter">#{{ String(page.id).padStart(3, '0') }}</span>
                            </td>
                            <td class="py-6 px-8">
                                <div class="text-sm font-black text-slate-900 uppercase tracking-tight italic">{{ page.title }}</div>
                            </td>
                            <td class="py-6 px-8">
                                <span class="text-[10px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-xl border border-indigo-100">
                                    {{ getGroupLabel(page.group) }}
                                </span>
                            </td>
                            <td class="py-6 px-8">
                                <span class="text-[10px] text-slate-400 font-mono tracking-tighter bg-slate-50 px-3 py-1 rounded-md">/pages/{{ page.slug }}</span>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <button @click="toggleActive(page)" :class="page.is_active ? 'text-green-500 bg-green-50 hover:bg-green-100' : 'text-slate-300 bg-slate-50 hover:bg-slate-100'" class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-current opacity-80 transition-all active:scale-95">
                                    {{ page.is_active ? $t('Active') : $t('Disabled') }}
                                </button>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex justify-end space-x-4">
                                    <Link :href="`/pages/${page.slug}`" target="_blank" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-800 hover:text-white transition-all flex items-center justify-center shadow-sm">
                                        <Eye class="w-4 h-4" />
                                    </Link>
                                    <Link :href="`/admin/pages/${page.id}/edit`" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-primary-900 hover:text-white transition-all flex items-center justify-center shadow-sm hover:shadow-xl hover:shadow-primary-900/20">
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button @click="confirmDelete(page.id)" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center shadow-sm hover:shadow-xl hover:shadow-red-600/20">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="pages.data.length === 0">
                            <td colspan="6" class="py-24 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <FileText class="w-10 h-10 text-slate-200" />
                                </div>
                                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ $t('No Dynamic Pages') }}</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">{{ $t('Create custom content pages to populate your footer links.') }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div v-if="pages.last_page > 1" class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-center space-x-2">
                <button v-for="link in pages.links" :key="link.label" 
                    @click="link.url && router.visit(link.url)"
                    v-html="link.label"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                    :class="[
                        link.active ? 'bg-primary-900 text-white shadow-lg' : 'bg-white text-slate-400 hover:bg-slate-100',
                        !link.url ? 'opacity-30 cursor-not-allowed' : ''
                    ]"
                ></button>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showDeleteModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-primary-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] p-12 max-w-sm w-full shadow-2xl border border-white/20">
                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <AlertTriangle class="w-10 h-10" />
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 text-center mb-4 uppercase tracking-tighter italic">{{ $t('Erase Custom Page?') }}</h3>
                    <p class="text-xs font-medium text-slate-400 text-center mb-10 leading-relaxed px-4">{{ $t('This action will permanently delete this page. Any footer links referencing this slug will disappear.') }}</p>
                    <div class="grid grid-cols-1 gap-3">
                        <button @click="deletePage" class="px-8 py-5 rounded-2xl bg-red-600 text-white font-black text-[10px] uppercase tracking-[0.2em] hover:bg-red-700 transition-all shadow-xl shadow-red-600/20 active:scale-95">
                            {{ $t('Confirm Deletion') }}
                        </button>
                        <button @click="showDeleteModal = false" class="px-8 py-5 rounded-2xl bg-slate-50 text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-100 transition-all active:scale-95">
                            {{ $t('Abort Operation') }}
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
import { Plus, Edit2, Trash2, AlertTriangle, CheckCircle, XCircle, FileText, Eye } from 'lucide-vue-next';

defineProps({
    pages: Object
});

const showDeleteModal = ref(false);
const pageToDelete = ref(null);

const confirmDelete = (id) => {
    pageToDelete.value = id;
    showDeleteModal.value = true;
};

const deletePage = () => {
    router.delete(`/admin/pages/${pageToDelete.value}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
};

const toggleActive = (page) => {
    router.put(`/admin/pages/${page.id}/toggle-active`, {}, {
        preserveScroll: true
    });
};

const getGroupLabel = (group) => {
    switch (group) {
        case 'about_us': return 'Information (About Us, T&C)';
        case 'consumer_policy': return 'Consumer Policy (Return, Refund)';
        case 'support': return 'Support (FAQ, How to Order)';
        default: return 'General Page';
    }
};
</script>
