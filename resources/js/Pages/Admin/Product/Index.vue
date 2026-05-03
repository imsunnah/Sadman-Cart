<template>
    <AdminLayout>
        <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-bold text-[#003366] tracking-tight">Inventory</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Manage product entities and stock status</p>
            </div>
            <Link href="/admin/products/create" resources\js\Pages\Admin\Product\Create.vue class="bg-[#003366] text-white px-8 py-4 rounded-xl hover:bg-slate-800 transition-all font-bold text-xs uppercase tracking-widest flex items-center shadow-lg active:scale-95">
                <Plus class="w-4 h-4 mr-3" /> Add Product
            </Link>
        </div>

        <div v-if="$page.props.flash?.success" class="mb-8 bg-[#0099FF] text-white p-6 rounded-xl shadow-lg flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <CheckCircle class="w-6 h-6" />
                <span class="text-xs font-bold uppercase tracking-widest">{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="opacity-50 hover:opacity-100 transition-opacity"><XCircle class="w-5 h-5" /></button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                            <th class="py-6 px-8 w-24">Visual</th>
                            <th class="py-6 px-8">Product Name</th>
                            <th class="py-6 px-8">Classification</th>
                            <th class="py-6 px-8">Market Value</th>
                            <th class="py-6 px-8">Stock</th>
                            <th class="py-6 px-8 text-center">Status</th>
                            <th class="py-6 px-8 text-right">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="product in products.data" :key="product.id" class="hover:bg-slate-50 transition-all">
                            <td class="py-6 px-8">
                                <img :src="product.image" class="w-12 h-12 rounded-lg object-cover border border-slate-200 shadow-sm" />
                            </td>
                            <td class="py-6 px-8">
                                <div class="text-sm font-bold text-slate-900 tracking-tight">{{ product.name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-1">{{ product.sku }}</div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ product.category?.name || 'Unclassified' }}</div>
                                <div v-if="product.sub_category" class="text-[9px] font-bold text-[#FF6600] uppercase tracking-widest mt-1">
                                    {{ product.sub_category.name }}
                                </div>
                            </td>
                            <td class="py-6 px-8 text-sm font-bold text-slate-900 italic">৳{{ parseFloat(product.price).toLocaleString() }}</td>
                            <td class="py-6 px-8">
                                <div class="text-xs font-bold" :class="product.stock <= 5 ? 'text-red-500' : 'text-slate-600'">
                                    {{ product.stock }} Units
                                </div>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <div :class="product.is_active ? 'text-green-500 bg-green-50' : 'text-slate-300 bg-slate-50'" class="inline-flex items-center px-4 py-1.5 rounded-full text-[8px] font-bold uppercase tracking-widest border border-current opacity-80">
                                    {{ product.is_active ? 'Active' : 'Archived' }}
                                </div>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex justify-end space-x-3">
                                    <Link :href="`/admin/products/${product.id}/edit`" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-[#003366] hover:text-white transition-all inline-flex items-center justify-center shadow-sm">
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button @click="confirmDelete(product.id)" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-red-600 hover:text-white transition-all inline-flex items-center justify-center shadow-sm">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div v-if="products.last_page > 1" class="p-8 bg-slate-50 border-t border-slate-100 flex justify-center space-x-2">
                <button v-for="link in products.links" :key="link.label" 
                    @click="link.url && router.visit(link.url)"
                    v-html="link.label"
                    class="px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all"
                    :class="[
                        link.active ? 'bg-[#003366] text-white shadow-lg' : 'bg-white text-slate-400 hover:bg-slate-100',
                        !link.url ? 'opacity-30 cursor-not-allowed' : ''
                    ]"
                ></button>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl">
                    <h3 class="text-xl font-bold text-slate-900 text-center mb-4">Are you sure?</h3>
                    <p class="text-sm text-slate-500 text-center mb-8">This product will be permanently removed from your inventory.</p>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showDeleteModal = false" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">Cancel</button>
                        <button @click="deleteProduct" class="px-4 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">Delete</button>
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
    products: Object
});

const showDeleteModal = ref(false);
const productToDelete = ref(null);

const confirmDelete = (id) => {
    productToDelete.value = id;
    showDeleteModal.value = true;
};

const deleteProduct = () => {
    router.delete(`/admin/products/${productToDelete.value}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
};
</script>
