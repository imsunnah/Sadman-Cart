<template>
    <AdminLayout>
        <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-bold text-[#003366] tracking-tight">Combos & Bundles</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Manage product collections and special offers</p>
            </div>
            <Link href="/admin/combos/create" class="bg-[#003366] text-white px-8 py-4 rounded-xl hover:bg-slate-800 transition-all font-bold text-xs uppercase tracking-widest flex items-center shadow-lg active:scale-95">
                <Plus class="w-4 h-4 mr-3" /> Create Combo
            </Link>
        </div>

        <div v-if="$page.props.flash?.success" class="mb-8 bg-[#0099FF] text-white p-6 rounded-xl shadow-lg flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <CheckCircle class="w-6 h-6" />
                <span class="text-xs font-bold uppercase tracking-widest">{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="opacity-50 hover:opacity-100 transition-opacity"><XCircle class="w-5 h-5" /></button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50 text-xs font-black text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <th class="py-4 px-6 w-24">Image</th>
                            <th class="py-4 px-6">Combo Details</th>
                            <th class="py-4 px-6">Included Products</th>
                            <th class="py-4 px-6">Price</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="combo in combos.data" :key="combo.id" class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                    <img v-if="combo.image" :src="combo.image" class="w-full h-full object-cover" />
                                    <span v-else class="text-[10px] font-bold text-slate-400">No Img</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-bold text-slate-900">{{ combo.name }}</div>
                                <div class="text-[9px] text-slate-400 font-mono mt-1 px-2 py-0.5 bg-slate-100 rounded inline-block uppercase tracking-widest">SLUG: {{ combo.slug }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1">
                                    <span v-for="product in combo.products" :key="product.id" class="text-[9px] font-black text-[#003366] uppercase tracking-wider bg-[#003366]/5 px-2 py-1 rounded">
                                        {{ product.name }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-black text-slate-900">৳{{ parseFloat(combo.price).toLocaleString() }}</div>
                                <div v-if="combo.original_price" class="text-[10px] text-slate-400 line-through">৳{{ parseFloat(combo.original_price).toLocaleString() }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <button @click="toggleStatus(combo.id)" :class="combo.is_active ? 'text-green-700 bg-green-50 border-green-200' : 'text-slate-500 bg-slate-50 border-slate-200'" class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest border hover:scale-105 transition-transform">
                                    {{ combo.is_active ? 'Active' : 'Archived' }}
                                </button>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="`/admin/combos/${combo.id}/edit`" class="p-2 rounded-lg bg-slate-100 text-slate-500 hover:bg-[#003366] hover:text-white transition-all transform active:scale-95">
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button @click="confirmDelete(combo.id)" class="p-2 rounded-lg bg-slate-100 text-slate-500 hover:bg-red-600 hover:text-white transition-all transform active:scale-95">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="combos.data.length === 0">
                            <td colspan="6" class="py-20 text-center text-slate-400 font-bold uppercase text-xs tracking-widest opacity-40">
                                No combos found. Create your first bundle offer!
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl">
                    <h3 class="text-xl font-bold text-slate-900 text-center mb-4 italic uppercase tracking-tight">Confirm Deletion</h3>
                    <p class="text-sm text-slate-500 text-center mb-8 font-bold italic">This combo bundle will be permanently removed.</p>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showDeleteModal = false" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-400 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50 transition-all">Cancel</button>
                        <button @click="deleteCombo" class="px-4 py-3 rounded-xl bg-red-600 text-white font-black uppercase text-[10px] tracking-widest hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">Delete Now</button>
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
import { Plus, Edit2, Trash2, CheckCircle, XCircle } from 'lucide-vue-next';

defineProps({
    combos: Object
});

const showDeleteModal = ref(false);
const comboToDelete = ref(null);

const toggleStatus = (id) => {
    router.put(`/admin/combos/${id}/toggle-active`, {}, {
        preserveScroll: true
    });
};

const confirmDelete = (id) => {
    comboToDelete.value = id;
    showDeleteModal.value = true;
};

const deleteCombo = () => {
    router.delete(`/admin/combos/${comboToDelete.value}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
};
</script>
