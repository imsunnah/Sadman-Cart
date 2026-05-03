<template>
    <AdminLayout>
        <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div>
                <h1 class="text-4xl font-black text-primary-900 tracking-tighter uppercase italic">Classification <span class="text-slate-400">Archives</span></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">Organize your product entities with logical categories</p>
            </div>
            <Link href="/admin/categories/create" class="bg-primary-900 text-white px-8 py-4 rounded-2xl hover:bg-slate-800 transition-all font-black text-xs uppercase tracking-[0.2em] flex items-center shadow-2xl shadow-primary-900/30 active:scale-95">
                <Plus class="w-4 h-4 mr-3" /> Establish Category
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
                            <th class="py-6 px-8 text-center w-24">ID</th>
                            <th class="py-6 px-8">Designation</th>
                            <th class="py-6 px-8">Identifier Slug</th>
                            <th class="py-6 px-8 text-center">Operational Status</th>
                            <th class="py-6 px-8 text-right">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="category in categories.data" :key="category.id" class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-6 px-8 text-center">
                                <span class="text-[10px] font-black text-slate-300 font-mono tracking-tighter">#{{ String(category.id).padStart(3, '0') }}</span>
                            </td>
                            <td class="py-6 px-8">
                                <div class="text-sm font-black text-slate-900 uppercase tracking-tight italic">{{ category.name }}</div>
                            </td>
                            <td class="py-6 px-8">
                                <span class="text-[10px] text-slate-400 font-mono tracking-tighter bg-slate-50 px-3 py-1 rounded-md">{{ category.slug }}</span>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <div :class="category.is_active ? 'text-green-500 bg-green-50' : 'text-slate-300 bg-slate-50'" class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-current opacity-80">
                                    {{ category.is_active ? 'Active' : 'Archived' }}
                                </div>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex justify-end space-x-4">
                                    <Link :href="`/admin/categories/${category.id}/edit`" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-primary-900 hover:text-white transition-all flex items-center justify-center shadow-sm hover:shadow-xl hover:shadow-primary-900/20">
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button @click="confirmDelete(category.id)" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center shadow-sm hover:shadow-xl hover:shadow-red-600/20">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="categories.data.length === 0">
                            <td colspan="5" class="py-24 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <Tags class="w-10 h-10 text-slate-200" />
                                </div>
                                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Archives Empty</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">No category classifications have been established yet.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div v-if="categories.last_page > 1" class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-center space-x-2">
                <button v-for="link in categories.links" :key="link.label" 
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
                    <h3 class="text-2xl font-black text-slate-900 text-center mb-4 uppercase tracking-tighter italic">Terminate Category?</h3>
                    <p class="text-xs font-medium text-slate-400 text-center mb-10 leading-relaxed px-4">This operation will permanently erase this classification. Associated product visibility may be affected.</p>
                    <div class="grid grid-cols-1 gap-3">
                        <button @click="deleteCategory" class="px-8 py-5 rounded-2xl bg-red-600 text-white font-black text-[10px] uppercase tracking-[0.2em] hover:bg-red-700 transition-all shadow-xl shadow-red-600/20 active:scale-95">
                            Confirm Termination
                        </button>
                        <button @click="showDeleteModal = false" class="px-8 py-5 rounded-2xl bg-slate-50 text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-100 transition-all active:scale-95">
                            Abort Mission
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
import { Plus, Edit2, Trash2, AlertTriangle, CheckCircle, XCircle, Tags } from 'lucide-vue-next';

defineProps({
    categories: Object
});

const showDeleteModal = ref(false);
const categoryToDelete = ref(null);

const confirmDelete = (id) => {
    categoryToDelete.value = id;
    showDeleteModal.value = true;
};

const deleteCategory = () => {
    router.delete(`/admin/categories/${categoryToDelete.value}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
};
</script>
