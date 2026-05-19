<template>
    <AdminLayout>
        <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-bold text-[#003366] tracking-tight">Customer Reviews</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Manage testimonials shown on the storefront</p>
            </div>
            <button @click="showCreateModal = true" class="bg-[#003366] text-white px-8 py-4 rounded-xl hover:bg-slate-800 transition-all font-bold text-xs uppercase tracking-widest flex items-center shadow-lg active:scale-95">
                <Plus class="w-4 h-4 mr-3" /> Add Review
            </button>
        </div>

        <div v-if="$page.props.flash?.success" class="mb-8 bg-[#0099FF] text-white p-6 rounded-xl shadow-lg flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <CheckCircle class="w-6 h-6" />
                <span class="text-xs font-bold uppercase tracking-widest">{{ $page.props.flash.success }}</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-xs font-black text-slate-500 uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-6">Customer</th>
                        <th class="py-4 px-6">Comment</th>
                        <th class="py-4 px-6 text-center">Rating</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="review in reviews.data" :key="review.id" class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-6 font-bold text-sm text-slate-900">
                            {{ review.customer_name }}
                            <div v-if="review.product" class="text-[9px] font-black text-[#FF6600] uppercase tracking-widest mt-1">
                                Product: {{ review.product.name }}
                            </div>
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-500 max-w-xs">{{ review.comment.substring(0, 100) }}{{ review.comment.length > 100 ? '...' : '' }}</td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex justify-center text-[#FF6600]">
                                <Star v-for="i in 5" :key="i" class="w-4 h-4" :class="i <= review.rating ? 'fill-current' : 'opacity-20'" />
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button @click="toggleStatus(review.id)" :class="review.is_active ? 'text-green-700 bg-green-50 border-green-200' : 'text-slate-500 bg-slate-50 border-slate-200'" class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest border hover:scale-105 transition-transform">
                                {{ review.is_active ? 'Visible' : 'Hidden' }}
                            </button>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="openEdit(review)" class="p-2 rounded-lg bg-slate-100 text-slate-500 hover:bg-[#003366] hover:text-white transition-all">
                                    <Edit2 class="w-4 h-4" />
                                </button>
                                <button @click="confirmDelete(review.id)" class="p-2 rounded-lg bg-slate-100 text-slate-500 hover:bg-red-600 hover:text-white transition-all">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="reviews.data.length === 0">
                        <td colspan="5" class="py-20 text-center text-slate-400 font-bold uppercase text-xs tracking-widest">No reviews yet. Add your first testimonial!</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Create Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2rem] p-8 max-w-lg w-full shadow-2xl">
                    <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight italic mb-8">Add Customer Review</h3>
                    <form @submit.prevent="submitCreate" class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Customer Name</label>
                            <input v-model="createForm.customer_name" type="text" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Comment</label>
                            <textarea v-model="createForm.comment" required rows="4" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition font-bold text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Rating (1–5)</label>
                            <div class="flex gap-3">
                                <button v-for="i in 5" :key="i" type="button" @click="createForm.rating = i" :class="i <= createForm.rating ? 'text-[#FF6600]' : 'text-slate-200'" class="transition-colors hover:text-[#FF6600]">
                                    <Star class="w-7 h-7 fill-current" />
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Associate with Product (Optional)</label>
                            <select v-model="createForm.product_id" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition font-bold text-sm text-slate-700">
                                <option value="">None - General Store Review</option>
                                <option v-for="product in products" :key="product.id" :value="product.id">
                                    {{ product.name }}
                                </option>
                            </select>
                        </div>
                        <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <input v-model="createForm.is_active" type="checkbox" id="create_active" class="w-4 h-4 text-[#003366] rounded border-slate-300 focus:ring-[#003366]/20">
                            <label for="create_active" class="text-xs font-bold text-slate-700 select-none cursor-pointer">Activate immediately (Show on storefront)</label>
                        </div>
                        <div class="pt-4 flex gap-4">
                            <button type="button" @click="showCreateModal = false" class="flex-1 py-4 rounded-2xl border border-slate-100 text-slate-400 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50">Cancel</button>
                            <button type="submit" :disabled="createForm.processing" class="flex-1 py-4 rounded-2xl bg-[#003366] text-white font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all disabled:opacity-50">Save Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>

        <!-- Edit Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2rem] p-8 max-w-lg w-full shadow-2xl">
                    <h3 class="text-xl font-black text-[#003366] uppercase tracking-tight italic mb-8">Edit Review</h3>
                    <form @submit.prevent="submitEdit" class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Customer Name</label>
                            <input v-model="editForm.customer_name" type="text" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Comment</label>
                            <textarea v-model="editForm.comment" required rows="4" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition font-bold text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Rating (1–5)</label>
                            <div class="flex gap-3">
                                <button v-for="i in 5" :key="i" type="button" @click="editForm.rating = i" :class="i <= editForm.rating ? 'text-[#FF6600]' : 'text-slate-200'" class="transition-colors hover:text-[#FF6600]">
                                    <Star class="w-7 h-7 fill-current" />
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Associate with Product (Optional)</label>
                            <select v-model="editForm.product_id" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition font-bold text-sm text-slate-700">
                                <option value="">None - General Store Review</option>
                                <option v-for="product in products" :key="product.id" :value="product.id">
                                    {{ product.name }}
                                </option>
                            </select>
                        </div>
                        <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <input v-model="editForm.is_active" type="checkbox" id="edit_active" class="w-4 h-4 text-[#FF6600] rounded border-slate-300 focus:ring-[#FF6600]/20">
                            <label for="edit_active" class="text-xs font-bold text-slate-700 select-none cursor-pointer">Active / Visible on storefront</label>
                        </div>
                        <div class="pt-4 flex gap-4">
                            <button type="button" @click="showEditModal = false" class="flex-1 py-4 rounded-2xl border border-slate-100 text-slate-400 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50">Cancel</button>
                            <button type="submit" :disabled="editForm.processing" class="flex-1 py-4 rounded-2xl bg-[#FF6600] text-white font-black uppercase text-[10px] tracking-widest hover:bg-orange-600 transition-all disabled:opacity-50">Update Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>

        <!-- Delete Modal -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl">
                    <h3 class="text-xl font-bold text-slate-900 text-center mb-4 italic uppercase tracking-tight">Confirm Deletion</h3>
                    <p class="text-sm text-slate-500 text-center mb-8 font-bold">This review will be permanently removed.</p>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showDeleteModal = false" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-400 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50">Cancel</button>
                        <button @click="deleteReview" class="px-4 py-3 rounded-xl bg-red-600 text-white font-black uppercase text-[10px] tracking-widest hover:bg-red-700 shadow-lg">Delete</button>
                    </div>
                </div>
            </div>
        </transition>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus, Edit2, Trash2, Star, CheckCircle } from 'lucide-vue-next';

defineProps({ reviews: Object, products: Array });

const showCreateModal = ref(false);
const showEditModal   = ref(false);
const showDeleteModal = ref(false);
const reviewToDelete  = ref(null);

const createForm = useForm({ customer_name: '', comment: '', rating: 5, is_active: true, product_id: '' });
const editForm   = useForm({ id: null, customer_name: '', comment: '', rating: 5, is_active: true, product_id: '' });

const submitCreate = () => {
    createForm.post('/admin/reviews', {
        onSuccess: () => { showCreateModal.value = false; createForm.reset(); }
    });
};

const openEdit = (review) => {
    editForm.id = review.id;
    editForm.customer_name = review.customer_name;
    editForm.comment = review.comment;
    editForm.rating = review.rating;
    editForm.is_active = !!review.is_active;
    editForm.product_id = review.product_id || '';
    showEditModal.value = true;
};

const submitEdit = () => {
    editForm.put(`/admin/reviews/${editForm.id}`, {
        onSuccess: () => { showEditModal.value = false; }
    });
};

const toggleStatus = (id) => router.put(`/admin/reviews/${id}/toggle-active`, {}, { preserveScroll: true });
const confirmDelete = (id) => { reviewToDelete.value = id; showDeleteModal.value = true; };
const deleteReview  = () => {
    router.delete(`/admin/reviews/${reviewToDelete.value}`, {
        onSuccess: () => { showDeleteModal.value = false; }
    });
};
</script>
