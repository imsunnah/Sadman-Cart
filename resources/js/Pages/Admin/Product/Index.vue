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

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50 text-xs font-black text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <th class="py-4 px-6 w-24">Image</th>
                            <th class="py-4 px-6">Product Details</th>
                            <th class="py-4 px-6">Category / Subcategory</th>
                            <th class="py-4 px-6">Price</th>
                            <th class="py-4 px-6">Stock</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="product in products.data" :key="product.id" class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                    <img v-if="product.image" :src="product.image" class="w-full h-full object-cover" />
                                    <span v-else class="text-[10px] font-bold text-slate-400">No Img</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-bold text-slate-900">{{ product.name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-1 px-2 py-0.5 bg-slate-100 rounded inline-block">{{ product.sku }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs font-black text-[#003366] uppercase tracking-wider bg-[#003366]/5 px-2 py-1 rounded w-fit">
                                        {{ product.category?.name || 'Uncategorized' }}
                                    </span>
                                    <span v-if="product.sub_category" class="text-[10px] font-bold text-[#FF6600] uppercase tracking-wider ml-2 flex items-center">
                                        <span class="w-2 h-[1px] bg-slate-300 mr-1"></span>
                                        {{ product.sub_category.name }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm font-black text-slate-900">৳{{ parseFloat(product.price).toLocaleString() }}</td>
                            <td class="py-4 px-6">
                                <div class="text-xs font-bold px-2 py-1 rounded inline-block" :class="product.stock <= 5 ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-slate-100 text-slate-600'">
                                    {{ product.stock }} {{ product.stock === 1 ? 'Unit' : 'Units' }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div :class="product.is_active ? 'text-green-700 bg-green-50 border-green-200' : 'text-slate-500 bg-slate-50 border-slate-200'" class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest border">
                                    {{ product.is_active ? 'Active' : 'Archived' }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="`/admin/products/${product.id}/edit`" class="p-2 rounded-lg bg-slate-100 text-slate-500 hover:bg-[#003366] hover:text-white transition-colors" title="Edit Product">
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button @click="confirmDelete(product.id)" class="p-2 rounded-lg bg-slate-100 text-slate-500 hover:bg-red-600 hover:text-white transition-colors" title="Delete Product">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="products.data.length === 0">
                            <td colspan="7" class="py-20 text-center">
                                <div class="flex flex-col items-center opacity-40">
                                    <Package class="w-12 h-12 mb-3 text-slate-400" />
                                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">No products found</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div v-if="products.last_page > 1" class="p-6 bg-slate-50 border-t border-slate-200 flex justify-center space-x-2">
                <button v-for="link in products.links" :key="link.label" 
                    @click="link.url && router.visit(link.url)"
                    v-html="link.label"
                    class="px-4 py-2 rounded-lg text-xs font-bold transition-all border"
                    :class="[
                        link.active ? 'bg-[#003366] text-white border-[#003366]' : 'bg-white text-slate-500 border-slate-200 hover:border-[#003366]',
                        !link.url ? 'opacity-50 cursor-not-allowed bg-slate-100' : ''
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
import { Plus, Edit2, Trash2, CheckCircle, XCircle, Package } from 'lucide-vue-next';

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
