<template>
    <AdminLayout>
        <div class="mb-8 flex items-center justify-between bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-[#003366]">Edit Product</h1>
                <p class="text-xs text-slate-500 mt-1">Updating details for <span class="text-[#003366] font-bold">{{ product.name }}</span></p>
            </div>
            <Link href="/admin/products" class="text-xs font-bold text-slate-500 hover:text-[#003366] flex items-center transition-all">
                <ArrowLeft class="w-4 h-4 mr-2" /> Back to List
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <form @submit.prevent="submit" class="p-8 space-y-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Product Name</label>
                            <input v-model="form.name" type="text" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                            <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-6">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Product Classification</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 ml-1">Main Category</label>
                                    <select v-model="form.category_id" class="w-full px-4 py-3 rounded-xl bg-white border-none shadow-sm focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold appearance-none">
                                        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 ml-1">Subcategory (Optional)</label>
                                    <select v-model="form.sub_category_id" :disabled="!form.category_id" class="w-full px-4 py-3 rounded-xl bg-white border-none shadow-sm focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold appearance-none disabled:opacity-50">
                                        <option :value="null">No Subcategory</option>
                                        <option v-for="sub in selectedCategorySubs" :key="sub.id" :value="sub.id">{{ sub.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Sell Price (৳)</label>
                                <input v-model="form.price" type="number" step="0.01" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                                <p v-if="form.errors.price" class="mt-1 text-xs text-red-500">{{ form.errors.price }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Buy Price (৳)</label>
                                <input v-model="form.buying_price" type="number" step="0.01" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                                <p v-if="form.errors.buying_price" class="mt-1 text-xs text-red-500">{{ form.errors.buying_price }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Pack Cost (৳)</label>
                                <input v-model="form.package_cost" type="number" step="0.01" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                                <p v-if="form.errors.package_cost" class="mt-1 text-xs text-red-500">{{ form.errors.package_cost }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Stock Amount</label>
                                <input v-model="form.stock" type="number" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                                <p v-if="form.errors.stock" class="mt-1 text-xs text-red-500">{{ form.errors.stock }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                            <textarea v-model="form.description" rows="5" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-medium"></textarea>
                            <p v-if="form.errors.description" class="mt-1 text-xs text-red-500">{{ form.errors.description }}</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Product Images</label>
                            
                            <!-- Main Image -->
                            <div class="mb-6">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Main Image</span>
                                <div class="flex items-center space-x-4">
                                    <div class="w-24 h-24 bg-slate-100 rounded-xl overflow-hidden border border-slate-200">
                                        <img v-if="mainPreview || product.image" :src="mainPreview || product.image" class="w-full h-full object-cover">
                                        <ImageIcon v-else class="w-full h-full p-6 text-slate-300" />
                                    </div>
                                    <input type="file" @input="handleMainImage" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#003366] file:text-white" />
                                </div>
                                <p v-if="form.errors.image" class="mt-1 text-xs text-red-500">{{ form.errors.image }}</p>
                            </div>

                            <!-- Additional Images -->
                            <div class="space-y-4 pt-6 border-t border-slate-100">
                                <div class="flex justify-between items-center">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Gallery Images</span>
                                    <button @click.prevent="triggerGalleryUpload" class="text-[10px] font-bold text-[#003366] uppercase hover:underline">Add More</button>
                                </div>
                                <input type="file" multiple ref="galleryInput" @input="handleGalleryImages" class="hidden" />
                                
                                <div class="grid grid-cols-4 gap-4">
                                    <!-- Existing Gallery Images -->
                                    <div v-for="(img, idx) in form.keep_gallery_images" :key="'old-'+img.id" class="relative group aspect-square rounded-lg overflow-hidden border border-slate-100">
                                        <img :src="img.image" class="w-full h-full object-cover">
                                        <button @click.prevent="removeKeepImage(idx)" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                            <X class="w-3 h-3" />
                                        </button>
                                    </div>
                                    <!-- New Gallery Previews -->
                                    <div v-for="(img, idx) in galleryPreviews" :key="'new-'+idx" class="relative group aspect-square rounded-lg overflow-hidden border border-[#FF6600]">
                                        <img :src="img" class="w-full h-full object-cover">
                                        <button @click.prevent="removeNewGalleryImage(idx)" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                            <X class="w-3 h-3" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input v-model="form.is_active" type="checkbox" class="w-5 h-5 text-[#003366] rounded border-slate-300 focus:ring-[#003366]">
                                <span class="text-sm font-bold text-slate-700">Active (Visible on Store)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 flex justify-end">
                    <button type="submit" :disabled="form.processing" class="w-full md:w-auto bg-[#003366] text-white px-12 py-4 rounded-lg font-bold text-sm uppercase tracking-widest hover:bg-slate-800 transition-all disabled:opacity-50">
                        {{ form.processing ? 'Updating...' : 'Update Product' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ArrowLeft, Image as ImageIcon, X } from 'lucide-vue-next';

const props = defineProps({
    product: Object,
    categories: Array
});

const mainPreview = ref(null);
const galleryPreviews = ref([]);
const galleryInput = ref(null);

const form = useForm({
    _method: 'PUT',
    category_id: props.product.category_id,
    sub_category_id: props.product.sub_category_id,
    name: props.product.name,
    description: props.product.description,
    price: props.product.price,
    buying_price: props.product.buying_price,
    package_cost: props.product.package_cost,
    stock: props.product.stock,
    image: null,
    gallery_images: [],
    keep_gallery_images: props.product.gallery || [], // Track which old images to keep
    is_active: props.product.is_active ? true : false,
});

const selectedCategorySubs = computed(() => {
    const cat = props.categories.find(c => c.id === form.category_id);
    return cat ? cat.sub_categories : [];
});

watch(() => form.category_id, (newVal) => {
    if (newVal !== props.product.category_id) {
        form.sub_category_id = null;
    }
});

const handleMainImage = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        mainPreview.value = URL.createObjectURL(file);
    }
};

const triggerGalleryUpload = () => {
    galleryInput.value.click();
};

const handleGalleryImages = (e) => {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        form.gallery_images.push(file);
        galleryPreviews.value.push(URL.createObjectURL(file));
    });
};

const removeKeepImage = (idx) => {
    if (confirm('Are you sure you want to remove this gallery image?')) {
        form.keep_gallery_images.splice(idx, 1);
    }
};

const removeNewGalleryImage = (idx) => {
    form.gallery_images.splice(idx, 1);
    galleryPreviews.value.splice(idx, 1);
};

const submit = () => {
    // We keep form.keep_gallery_images as objects in the UI
    // But we need to send only IDs to the backend
    const originalKeep = [...form.keep_gallery_images];
    
    // Temporarily replace with IDs for submission
    form.keep_gallery_images = originalKeep.map(img => img.id);

    form.post(`/admin/products/${props.product.id}`, {
        onSuccess: () => {
            // Restore if needed, but we are redirecting anyway
        },
        onError: () => {
            // Restore objects so UI doesn't break on error
            form.keep_gallery_images = originalKeep;
        }
    });
};
</script>
