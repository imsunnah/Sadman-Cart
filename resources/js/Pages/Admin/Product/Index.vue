<template>
    <AdminLayout>
        <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-bold text-[#003366] tracking-tight">Inventory</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Manage product entities and stock status</p>
            </div>
            <Link href="/admin/products/create" class="bg-[#003366] text-white px-8 py-4 rounded-xl hover:bg-slate-800 transition-all font-bold text-xs uppercase tracking-widest flex items-center shadow-lg active:scale-95">
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

        <!-- Category Tabs -->
        <div class="mb-8 flex flex-wrap gap-2">
            <Link 
                :href="route('admin.products.index')"
                class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border-2"
                :class="[
                    currentCategoryId === 'all' 
                    ? 'bg-[#003366] text-white border-[#003366] shadow-md' 
                    : 'bg-white text-slate-400 border-slate-200 hover:border-[#003366] hover:text-[#003366]'
                ]"
            >
                All Products
            </Link>
            <Link 
                v-for="cat in categories" 
                :key="cat.id"
                :href="route('admin.products.index', { category_id: cat.id })"
                class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border-2"
                :class="[
                    currentCategoryId == cat.id
                    ? 'bg-[#003366] text-white border-[#003366] shadow-md' 
                    : 'bg-white text-slate-400 border-slate-200 hover:border-[#003366] hover:text-[#003366]'
                ]"
            >
                {{ cat.name }}
            </Link>
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
                                <div v-if="product.discount_type" class="mt-1">
                                    <span class="text-[9px] font-black uppercase tracking-tighter bg-orange-100 text-orange-600 px-2 py-0.5 rounded">
                                        {{ product.discount_type === 'percentage' ? product.discount_value + '%' : '৳' + product.discount_value }} OFF
                                    </span>
                                </div>
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
                                <button @click="toggleStatus(product.id)" :class="product.is_active ? 'text-green-700 bg-green-50 border-green-200' : 'text-slate-500 bg-slate-50 border-slate-200'" class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest border hover:scale-105 transition-transform">
                                    {{ product.is_active ? 'Active' : 'Archived' }}
                                </button>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="openQuickEdit(product)" class="p-2 rounded-lg bg-slate-100 text-slate-500 hover:bg-[#FF6600] hover:text-white transition-all transform active:scale-95" title="Quick Edit">
                                        <Zap class="w-4 h-4" />
                                    </button>
                                    <button @click="viewProduct(product)" class="p-2 rounded-lg bg-slate-100 text-slate-500 hover:bg-blue-600 hover:text-white transition-all transform active:scale-95" title="View Product">
                                        <Eye class="w-4 h-4" />
                                    </button>
                                    <Link :href="`/admin/products/${product.id}/edit`" class="p-2 rounded-lg bg-slate-100 text-slate-500 hover:bg-[#003366] hover:text-white transition-all transform active:scale-95" title="Full Edit">
                                        <Edit2 class="w-4 h-4" />
                                    </Link>
                                    <button @click="confirmDelete(product.id)" class="p-2 rounded-lg bg-slate-100 text-slate-500 hover:bg-red-600 hover:text-white transition-all transform active:scale-95" title="Delete Product">
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
                <Link v-for="link in products.links" :key="link.label" 
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="px-4 py-2 rounded-lg text-xs font-bold transition-all border"
                    :class="[
                        link.active ? 'bg-[#003366] text-white border-[#003366]' : 'bg-white text-slate-500 border-slate-200 hover:border-[#003366]',
                        !link.url ? 'opacity-50 cursor-not-allowed bg-slate-100' : ''
                    ]"
                ></Link>
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

        <!-- View Product Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 max-w-3xl w-full shadow-2xl max-h-[90vh] overflow-y-auto relative">
                    <div class="absolute top-8 right-8">
                        <button @click="showViewModal = false" class="p-3 bg-slate-100 rounded-2xl hover:bg-slate-200 transition-colors"><X class="w-5 h-5 text-slate-600" /></button>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight italic mb-2">{{ productToView?.name }}</h3>
                        <div class="flex items-center space-x-3">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-100 px-3 py-1 rounded-full">SKU: {{ productToView?.sku }}</span>
                            <span :class="productToView?.is_active ? 'bg-green-50 text-green-600 border-green-100' : 'bg-slate-50 text-slate-400 border-slate-100'" class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border">
                                {{ productToView?.is_active ? 'Active' : 'Archived' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10">
                        <div>
                            <h4 class="text-[10px] font-black text-[#003366] uppercase tracking-[0.2em] mb-4">Visual Identity</h4>
                            <div class="aspect-square bg-slate-50 rounded-[2rem] overflow-hidden mb-4 border-2 border-slate-100 flex items-center justify-center p-4">
                                <img v-if="productToView?.image" :src="productToView.image" class="max-w-full max-h-full object-contain mix-blend-multiply" />
                                <div v-else class="flex flex-col items-center text-slate-300">
                                    <Package class="w-12 h-12 mb-2" />
                                    <span class="text-[10px] font-bold uppercase tracking-widest">No Primary Image</span>
                                </div>
                            </div>
                            <div v-if="productToView?.gallery && productToView.gallery.length > 0" class="grid grid-cols-4 gap-3">
                                <div v-for="img in productToView.gallery" :key="img.id" class="aspect-square rounded-xl bg-slate-50 overflow-hidden border border-slate-100 p-1">
                                    <img :src="img.image" class="w-full h-full object-cover rounded-lg" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <div>
                                <h4 class="text-[10px] font-black text-[#003366] uppercase tracking-[0.2em] mb-4">Economics</h4>
                                <div class="grid grid-cols-1 gap-3">
                                    <div class="flex justify-between items-center p-4 bg-green-50 rounded-2xl border border-green-100">
                                        <span class="text-[10px] font-bold text-green-600 uppercase tracking-widest">Selling Price</span>
                                        <div class="text-right">
                                            <span class="text-xl font-black text-green-700">৳{{ parseFloat(productToView?.price || 0).toLocaleString() }}</span>
                                            <div v-if="productToView?.discount_type" class="text-[10px] font-black text-orange-500 uppercase tracking-widest mt-1">
                                                -{{ productToView.discount_type === 'percentage' ? productToView.discount_value + '%' : '৳' + productToView.discount_value }} DISCOUNT
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Buying Price</span>
                                        <span class="text-sm font-black text-slate-900">৳{{ parseFloat(productToView?.buying_price || 0).toLocaleString() }}</span>
                                    </div>
                                    <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Packaging Cost</span>
                                        <span class="text-sm font-black text-slate-900">৳{{ parseFloat(productToView?.package_cost || 0).toLocaleString() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-[10px] font-black text-[#003366] uppercase tracking-[0.2em] mb-4">Logistics</h4>
                                <div class="grid grid-cols-1 gap-3">
                                    <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">In Stock</span>
                                        <span :class="productToView?.stock <= 5 ? 'text-red-600' : 'text-slate-900'" class="text-sm font-black">{{ productToView?.stock }} Units</span>
                                    </div>
                                    <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Classification</span>
                                        <span class="text-[10px] font-black text-[#003366] uppercase tracking-widest bg-[#003366]/5 px-3 py-1 rounded-lg">
                                            {{ productToView?.category?.name }}
                                            <span v-if="productToView?.sub_category" class="text-slate-300 mx-1">/</span>
                                            {{ productToView?.sub_category?.name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="productToView?.remarks" class="mb-8">
                        <h4 class="text-[10px] font-black text-[#003366] uppercase tracking-[0.2em] mb-4">Administrative Remarks</h4>
                        <div class="p-6 bg-orange-50 rounded-2xl border border-orange-100 text-sm text-orange-800 font-bold italic">
                            {{ productToView.remarks }}
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-[10px] font-black text-[#003366] uppercase tracking-[0.2em] mb-4">Description</h4>
                        <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 text-sm text-slate-600 leading-relaxed font-bold italic shadow-inner">
                            {{ productToView?.description || 'No description provided.' }}
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Quick Edit Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showQuickEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 max-w-md w-full shadow-2xl relative overflow-hidden max-h-[90vh] overflow-y-auto">
                    <div class="mb-8 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-[#FF6600]/10 rounded-xl flex items-center justify-center text-[#FF6600]">
                                <Zap class="w-5 h-5" />
                            </div>
                            <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight italic">Quick Edit</h3>
                        </div>
                        <button @click="showQuickEditModal = false" class="p-2 bg-slate-50 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all"><X class="w-4 h-4" /></button>
                    </div>

                    <form @submit.prevent="updateProductQuick" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Product Designation</label>
                            <input v-model="quickEditForm.name" type="text" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#FF6600]/10 outline-none transition-all font-bold text-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Selling Price (৳)</label>
                                <input v-model="quickEditForm.price" type="number" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#FF6600]/10 outline-none transition-all font-bold text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Stock Units</label>
                                <input v-model="quickEditForm.stock" type="number" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#FF6600]/10 outline-none transition-all font-bold text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Discount Type</label>
                                <select v-model="quickEditForm.discount_type" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#FF6600]/10 outline-none transition-all font-bold text-sm">
                                    <option :value="null">No Discount</option>
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount (৳)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Discount Value</label>
                                <input v-model="quickEditForm.discount_value" type="number" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#FF6600]/10 outline-none transition-all font-bold text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Administrative Remarks</label>
                            <textarea v-model="quickEditForm.remarks" rows="2" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#FF6600]/10 outline-none transition-all font-bold text-sm"></textarea>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <div class="relative w-12 h-6 bg-slate-200 rounded-full transition-colors" :class="quickEditForm.is_active ? 'bg-green-500' : 'bg-slate-200'">
                                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform" :class="quickEditForm.is_active ? 'translate-x-6' : 'translate-x-0'"></div>
                                    <input v-model="quickEditForm.is_active" type="checkbox" class="hidden">
                                </div>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:text-slate-900 transition-colors">Visible in Storefront</span>
                            </label>
                        </div>

                        <div class="pt-4 grid grid-cols-2 gap-4">
                            <button type="button" @click="showQuickEditModal = false" class="px-6 py-4 rounded-2xl border border-slate-100 text-slate-400 font-black uppercase tracking-[0.2em] text-[10px] hover:bg-slate-50 transition-all">Cancel</button>
                            <button type="submit" :disabled="quickEditForm.processing" class="px-6 py-4 rounded-2xl bg-[#FF6600] text-white font-black uppercase tracking-[0.2em] text-[10px] hover:bg-orange-600 transition-all shadow-xl shadow-orange-500/20 active:scale-95 disabled:opacity-50">
                                {{ quickEditForm.processing ? 'Saving...' : 'Update Product' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus, Edit2, Trash2, CheckCircle, XCircle, Package, Eye, X, Zap } from 'lucide-vue-next';

defineProps({
    products: Object,
    categories: Array,
    currentCategoryId: [String, Number]
});

const showDeleteModal = ref(false);
const productToDelete = ref(null);

const showViewModal = ref(false);
const productToView = ref(null);

const showQuickEditModal = ref(false);
const quickEditForm = useForm({
    id: null,
    name: '',
    price: '',
    stock: '',
    is_active: true,
    discount_type: null,
    discount_value: '',
    remarks: '',
    // Keep these as they are required by the update method but not edited here
    category_id: null,
    description: '',
    buying_price: 0,
    package_cost: 0,
});

const viewProduct = (product) => {
    productToView.value = product;
    showViewModal.value = true;
};

const toggleStatus = (id) => {
    router.put(`/admin/products/${id}/toggle-active`, {}, {
        preserveScroll: true
    });
};

const openQuickEdit = (product) => {
    quickEditForm.id = product.id;
    quickEditForm.name = product.name;
    quickEditForm.price = product.price;
    quickEditForm.stock = product.stock;
    quickEditForm.is_active = product.is_active ? true : false;
    quickEditForm.discount_type = product.discount_type;
    quickEditForm.discount_value = product.discount_value;
    quickEditForm.remarks = product.remarks;
    
    // Populate hidden fields needed for validation
    quickEditForm.category_id = product.category_id;
    quickEditForm.description = product.description;
    quickEditForm.buying_price = product.buying_price;
    quickEditForm.package_cost = product.package_cost;
    
    showQuickEditModal.value = true;
};

const updateProductQuick = () => {
    quickEditForm.put(`/admin/products/${quickEditForm.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showQuickEditModal.value = false;
        }
    });
};

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
