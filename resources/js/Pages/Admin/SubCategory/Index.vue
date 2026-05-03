<template>
    <AdminLayout>
        <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div>
                <h1 class="text-4xl font-black text-[#FF6600] tracking-tighter uppercase italic">Sub <span class="text-slate-400">Classifications</span></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">Secondary level categorization for precision browsing</p>
            </div>
            <Link href="/admin/subcategories/create" class="bg-[#FF6600] text-white px-8 py-4 rounded-2xl hover:bg-orange-600 transition-all font-black text-xs uppercase tracking-[0.2em] flex items-center shadow-2xl shadow-orange-500/30 active:scale-95">
                <Plus class="w-4 h-4 mr-3" /> Establish Subcategory
            </Link>
        </div>

        <div v-if="$page.props.flash?.success" class="mb-8 bg-green-500 text-white p-6 rounded-2xl shadow-xl shadow-green-500/20 flex items-center justify-between">
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
                            <th class="py-6 px-8">Subcategory</th>
                            <th class="py-6 px-8">Parent Category</th>
                            <th class="py-6 px-8 text-center">Status</th>
                            <th class="py-6 px-8 text-right">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="sub in subCategories.data" :key="sub.id" class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-6 px-8 text-center">
                                <span class="text-[10px] font-black text-slate-300 font-mono tracking-tighter">#{{ String(sub.id).padStart(3, '0') }}</span>
                            </td>
                            <td class="py-6 px-8">
                                <div class="text-sm font-black text-slate-900 uppercase tracking-tight italic">{{ sub.name }}</div>
                                <div class="text-[9px] text-slate-400 font-mono tracking-tighter mt-1">{{ sub.slug }}</div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="inline-flex items-center px-3 py-1 bg-[#003366] text-white text-[10px] font-black uppercase tracking-widest rounded-md">
                                    {{ sub.category?.name }}
                                </div>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <div :class="sub.is_active ? 'text-green-500 bg-green-50' : 'text-slate-300 bg-slate-50'" class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-current opacity-80">
                                    {{ sub.is_active ? 'Active' : 'Archived' }}
                                </div>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex justify-end space-x-4">
                                    <Link :href="`/admin/subcategories/${sub.id}/edit`" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-[#FF6600] hover:text-white transition-all flex items-center justify-center">
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button @click="confirmDelete(sub.id)" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showDeleteModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-primary-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] p-12 max-w-sm w-full shadow-2xl border border-white/20">
                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <AlertTriangle class="w-10 h-10" />
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 text-center mb-4 uppercase tracking-tighter italic">Terminate Subcategory?</h3>
                    <p class="text-xs font-medium text-slate-400 text-center mb-10 leading-relaxed px-4">This action will permanently remove this sub-classification. It is <span class="text-red-500 font-bold uppercase tracking-widest">Irreversible</span>.</p>
                    <div class="grid grid-cols-1 gap-3">
                        <button @click="deleteSub" class="px-8 py-5 rounded-2xl bg-red-600 text-white font-black text-[10px] uppercase tracking-[0.2em] hover:bg-red-700 transition-all shadow-xl shadow-red-600/20 active:scale-95">
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
import { Plus, Edit2, Trash2, AlertTriangle, CheckCircle, XCircle } from 'lucide-vue-next';

defineProps({
    subCategories: Object
});

const showDeleteModal = ref(false);
const subToDelete = ref(null);

const confirmDelete = (id) => {
    subToDelete.value = id;
    showDeleteModal.value = true;
};

const deleteSub = () => {
    router.delete(`/admin/subcategories/${subToDelete.value}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
};
</script>
