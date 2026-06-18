<template>
    <AdminLayout>
        <div class="mb-8 flex items-center justify-between bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-[#003366]">Add New Product</h1>
                <p class="text-xs text-slate-500 mt-1">Upload a new item to your shop</p>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Product Name (English)</label>
                                <input v-model="form.name_en" type="text" placeholder="e.g. Pure Organic Honey" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                                <p v-if="form.errors.name_en" class="mt-1 text-xs text-red-500">{{ form.errors.name_en }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Product Name (Bangla)</label>
                                <input v-model="form.name_bn" type="text" placeholder="e.g. খাঁটি অর্গানিক মধু" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                                <p v-if="form.errors.name_bn" class="mt-1 text-xs text-red-500">{{ form.errors.name_bn }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Brand (Optional)</label>
                            <select v-model="form.brand_id" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold font-sans">
                                <option :value="null">No Brand</option>
                                <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name_en }}</option>
                            </select>
                            <p v-if="form.errors.brand_id" class="mt-1 text-xs text-red-500">{{ form.errors.brand_id }}</p>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-6">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Product Classification</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 ml-1">Main Category</label>
                                    <select v-model="form.category_id" class="w-full px-4 py-3 rounded-xl bg-white border-none shadow-sm focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold appearance-none">
                                        <option value="" disabled>Select Category</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name_en }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 ml-1">Subcategory (Optional)</label>
                                    <select v-model="form.sub_category_id" :disabled="!form.category_id" class="w-full px-4 py-3 rounded-xl bg-white border-none shadow-sm focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold appearance-none disabled:opacity-50">
                                        <option :value="null">No Subcategory</option>
                                        <option v-for="sub in selectedCategorySubs" :key="sub.id" :value="sub.id">{{ sub.name_en }}</option>
                                    </select>
                                </div>
                            </div>
                            <p v-if="form.errors.category_id" class="text-xs text-red-500 font-bold ml-1">{{ form.errors.category_id }}</p>
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
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Stock Amount</label>
                            <input v-model="form.stock" type="number" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                            <p v-if="form.errors.stock" class="mt-1 text-xs text-red-500">{{ form.errors.stock }}</p>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description (English)</label>
                                <textarea v-model="form.description_en" rows="5" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-medium" placeholder="Describe your product..."></textarea>
                                <p v-if="form.errors.description_en" class="mt-1 text-xs text-red-500">{{ form.errors.description_en }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description (Bangla)</label>
                                <textarea v-model="form.description_bn" rows="5" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-medium" placeholder="পণ্যের বিবরণ লিখুন..."></textarea>
                                <p v-if="form.errors.description_bn" class="mt-1 text-xs text-red-500">{{ form.errors.description_bn }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Premium Image Upload Section -->
                        <div class="bg-slate-50 p-8 rounded-[2rem] border border-slate-100 space-y-8">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-200 pb-4">Media Assets</h3>
                            
                            <!-- Main Product Image -->
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Primary Display Image</label>
                                    <button @click.prevent="showMainPicker = true" class="text-[9px] font-black text-[#FF6600] uppercase tracking-widest hover:underline flex items-center gap-1">
                                        <Library class="w-3 h-3" /> Choose from Gallery
                                    </button>
                                </div>
                                <div class="relative group">
                                    <div class="w-full aspect-video rounded-3xl bg-white border-2 border-dashed border-slate-200 flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-[#003366]/30">
                                        <img v-if="mainPreview" :src="mainPreview" class="w-full h-full object-cover" />
                                        <div v-else class="flex flex-col items-center gap-3 opacity-40 group-hover:opacity-60 transition-opacity">
                                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center">
                                                <ImageIcon class="w-8 h-8 text-[#003366]" />
                                            </div>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Click or Drop Main Image</span>
                                        </div>
                                        <input type="file" @input="handleMainImage" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" />
                                    </div>
                                    <div v-if="mainPreview" class="absolute top-4 right-4 flex gap-2">
                                        <button @click.prevent="mainPreview = null; form.image = null" class="p-2 bg-red-500 text-white rounded-xl shadow-lg hover:bg-red-600 transition-colors">
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                                <p v-if="form.errors.image" class="text-[10px] text-red-500 font-bold uppercase mt-1 ml-1">{{ form.errors.image }}</p>
                            </div>

                            <!-- Gallery Section -->
                            <div class="space-y-4 pt-6 border-t border-slate-200">
                                <div class="flex justify-between items-center px-1">
                                    <div class="flex items-center gap-3">
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Product Gallery</label>
                                        <button @click.prevent="showGalleryPicker = true" class="text-[9px] font-black text-[#FF6600] uppercase tracking-widest hover:underline flex items-center gap-1">
                                            <Library class="w-3 h-3" /> Add from Gallery
                                        </button>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{{ galleryPreviews.length }}/3 Images Added</span>
                                </div>
                                
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    <div v-for="(img, idx) in galleryPreviews" :key="idx" class="relative aspect-square rounded-2xl overflow-hidden group border border-slate-200">
                                        <img :src="img" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <button @click.prevent="removeGalleryImage(idx)" class="p-2 bg-red-500 text-white rounded-lg">
                                                <X class="w-3 h-3" />
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <button 
                                        v-if="galleryPreviews.length < 3"
                                        @click.prevent="triggerGalleryUpload" 
                                        class="aspect-square rounded-2xl bg-white border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-2 hover:border-[#FF6600]/30 hover:bg-orange-50 transition-all group"
                                    >
                                        <Plus class="w-5 h-5 text-slate-300 group-hover:text-[#FF6600]" />
                                        <span class="text-[8px] font-black text-slate-300 group-hover:text-[#FF6600] uppercase tracking-widest">Add More</span>
                                    </button>
                                </div>
                                <input type="file" multiple ref="galleryInput" @input="handleGalleryImages" class="hidden" accept="image/*" />
                                <div v-if="form.errors.gallery_images" class="text-[10px] text-red-500 font-bold uppercase mt-1 ml-1">{{ form.errors.gallery_images }}</div>
                            </div>
                        </div>

        <!-- Media Picker Modals -->
        <MediaPicker :show="showMainPicker" @close="showMainPicker = false" @select="handleMainGallerySelect" />
        <MediaPicker :show="showGalleryPicker" @close="showGalleryPicker = false" @select="handleGalleryItemsSelect" />

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
                        {{ form.processing ? 'Saving...' : 'Save Product' }}
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
import MediaPicker from '@/Components/MediaPicker.vue';
import { ArrowLeft, Image as ImageIcon, X, Trash2, Plus, Library } from 'lucide-vue-next';

const props = defineProps({
    categories: Array,
    brands: Array
});

const form = useForm({
    category_id: '',
    sub_category_id: '',
    name_en: '',
    name_bn: '',
    brand_id: null,
    description_en: '',
    description_bn: '',
    price: '',
    buying_price: '',
    package_cost: '',
    stock: '',
    image: null,
    gallery_images: [],
    is_active: true,
    discount_type: null,
    discount_value: '',
    remarks: '',
});

const mainPreview = ref(null);
const galleryPreviews = ref([]);
const galleryInput = ref(null);

const showMainPicker = ref(false);
const showGalleryPicker = ref(false);

const selectedCategorySubs = computed(() => {
    const cat = props.categories.find(c => c.id === form.category_id);
    return cat ? cat.sub_categories : [];
});

watch(() => form.category_id, () => {
    form.sub_category_id = null;
});

const handleMainImage = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        mainPreview.value = URL.createObjectURL(file);
    }
};

const handleMainGallerySelect = (item) => {
    // When selecting from gallery, we send the path
    form.image = item.url; 
    mainPreview.value = item.url;
};

const handleGalleryImages = (e) => {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        if (form.gallery_images.length < 3) {
            form.gallery_images.push(file);
            galleryPreviews.value.push(URL.createObjectURL(file));
        }
    });
};

const handleGalleryItemsSelect = (item) => {
    if (form.gallery_images.length < 3) {
        form.gallery_images.push(item.url);
        galleryPreviews.value.push(item.url);
    } else {
        alert('You can only add up to 3 gallery images.');
    }
};

const triggerGalleryUpload = () => {
    galleryInput.value.click();
};

const removeGalleryImage = (idx) => {
    form.gallery_images.splice(idx, 1);
    galleryPreviews.value.splice(idx, 1);
};

const submit = () => {
    form.post('/admin/products');
};
</script>
