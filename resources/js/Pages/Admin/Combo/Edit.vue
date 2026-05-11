<template>
    <AdminLayout>
        <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-bold text-[#003366] tracking-tight">Edit Combo Bundle</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Refine your special offer bundle details</p>
            </div>
            <Link href="/admin/combos" class="text-slate-400 hover:text-[#003366] transition-colors flex items-center font-bold text-xs uppercase tracking-widest">
                <ArrowLeft class="w-4 h-4 mr-2" /> Back to List
            </Link>
        </div>

        <div class="max-w-4xl mx-auto">
            <form @submit.prevent="submit" class="space-y-8">
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-12 shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-[#003366] mb-8 uppercase tracking-[0.2em] italic border-b border-slate-50 pb-4">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Combo Name</label>
                            <input v-model="form.name" type="text" required placeholder="e.g. Organic Breakfast Bundle" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-50 focus:bg-white focus:border-[#003366]/10 focus:ring-0 outline-none transition-all font-bold text-sm">
                            <div v-if="form.errors.name" class="text-red-500 text-[10px] mt-2 ml-2 font-bold">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Bundle Description</label>
                            <textarea v-model="form.description" rows="4" placeholder="Explain what's included in this premium offer..." class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-50 focus:bg-white focus:border-[#003366]/10 focus:ring-0 outline-none transition-all font-bold text-sm italic"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 sm:p-12 shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-[#003366] mb-8 uppercase tracking-[0.2em] italic border-b border-slate-50 pb-4">Pricing & Media</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Combo Price (৳)</label>
                            <input v-model="form.price" type="number" step="0.01" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-50 focus:bg-white focus:border-[#003366]/10 focus:ring-0 outline-none transition-all font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Original Price (৳)</label>
                            <input v-model="form.original_price" type="number" step="0.01" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-50 focus:bg-white focus:border-[#003366]/10 focus:ring-0 outline-none transition-all font-bold text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Bundle Showcase Image</label>
                        <div class="mt-2 flex items-center gap-8">
                            <div class="w-32 h-32 rounded-[1.5rem] bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden">
                                <img v-if="imagePreview || combo.image" :src="imagePreview || combo.image" class="w-full h-full object-cover" />
                                <ImagePlus v-else class="w-8 h-8 text-slate-300" />
                            </div>
                            <label class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold text-[10px] uppercase tracking-widest cursor-pointer hover:bg-black transition-all">
                                Update Image
                                <input type="file" @change="handleImageUpload" class="hidden" accept="image/*">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 sm:p-12 shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-[#003366] mb-8 uppercase tracking-[0.2em] italic border-b border-slate-50 pb-4">Selected Products</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div v-for="product in products" :key="product.id" 
                            @click="toggleProduct(product.id)"
                            :class="[
                                form.product_ids.includes(product.id) 
                                ? 'bg-[#003366] text-white border-[#003366] shadow-lg scale-[1.02]' 
                                : 'bg-slate-50 text-slate-500 border-slate-100 hover:border-[#003366]/30'
                            ]"
                            class="p-4 rounded-2xl border-2 transition-all cursor-pointer group"
                        >
                            <div class="flex flex-col items-center text-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-white/10 overflow-hidden flex items-center justify-center p-1">
                                    <img v-if="product.image" :src="product.image" class="w-full h-full object-cover rounded-lg" />
                                    <Package v-else class="w-6 h-6" />
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest leading-tight">{{ product.name }}</span>
                                <span class="text-[9px] font-bold opacity-60">৳{{ parseFloat(product.price).toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="form.errors.product_ids" class="text-red-500 text-[10px] mt-4 ml-2 font-bold">{{ form.errors.product_ids }}</div>
                </div>

                <div class="flex justify-end gap-4 pb-20">
                    <Link href="/admin/combos" class="px-10 py-5 rounded-2xl border-2 border-slate-200 text-slate-400 font-black uppercase tracking-widest text-xs hover:bg-slate-50 transition-all">Cancel</Link>
                    <button type="submit" :disabled="form.processing" class="px-12 py-5 rounded-2xl bg-[#003366] text-white font-black uppercase tracking-widest text-xs hover:bg-black transition-all shadow-2xl shadow-blue-900/20 active:scale-95 disabled:opacity-50">
                        {{ form.processing ? 'Updating...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ArrowLeft, ImagePlus, Package } from 'lucide-vue-next';

const props = defineProps({
    combo: Object,
    products: Array
});

const form = useForm({
    _method: 'PUT',
    name: props.combo.name,
    description: props.combo.description,
    price: props.combo.price,
    original_price: props.combo.original_price,
    image: null,
    product_ids: props.combo.products.map(p => p.id),
    is_active: props.combo.is_active ? true : false
});

const imagePreview = ref(null);

const handleImageUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const toggleProduct = (id) => {
    const index = form.product_ids.indexOf(id);
    if (index === -1) {
        form.product_ids.push(id);
    } else {
        form.product_ids.splice(index, 1);
    }
};

const submit = () => {
    // We use POST with _method PUT because of Laravel file upload limitations with PUT
    form.post(`/admin/combos/${props.combo.id}`);
};
</script>
