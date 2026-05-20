<template>
    <AdminLayout>
        <div class="mb-10 flex items-center justify-between bg-white p-8 rounded-xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-[#FF6600]">Edit Subcategory</h1>
                <p class="text-xs text-slate-500 mt-1">Updating <span class="font-bold text-[#FF6600]">{{ subcategory.name_en }}</span></p>
            </div>
            <Link href="/admin/subcategories" class="text-xs font-bold text-slate-500 hover:text-[#FF6600] flex items-center transition-all">
                <ArrowLeft class="w-4 h-4 mr-2" /> Back to List
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden max-w-2xl">
            <form @submit.prevent="submit" class="p-8 space-y-8">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Parent Category</label>
                    <select v-model="form.category_id" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#FF6600]/10 outline-none transition-all font-bold appearance-none">
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name_en }} / {{ cat.name_bn }}</option>
                    </select>
                    <p v-if="form.errors.category_id" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.category_id }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Subcategory Name (English)</label>
                        <input v-model="form.name_en" type="text" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#FF6600]/10 outline-none transition-all font-bold">
                        <p v-if="form.errors.name_en" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.name_en }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Subcategory Name (Bangla)</label>
                        <input v-model="form.name_bn" type="text" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#FF6600]/10 outline-none transition-all font-bold">
                        <p v-if="form.errors.name_bn" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.name_bn }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Subcategory Image</label>
                    <div class="mt-2 flex items-center space-x-6">
                        <div class="shrink-0 w-24 h-24 bg-slate-100 rounded-xl flex items-center justify-center overflow-hidden border border-slate-200">
                            <img v-if="previewUrl || subcategory.image" :src="previewUrl || subcategory.image" class="w-full h-full object-cover" />
                            <ImageIcon v-else class="w-8 h-8 text-slate-300" />
                        </div>
                        <input type="file" @input="handleFileUpload" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#FF6600] file:text-white" />
                    </div>
                    <p v-if="form.errors.image" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.image }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description (English)</label>
                        <textarea v-model="form.description_en" rows="4" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#FF6600]/10 outline-none transition-all font-medium"></textarea>
                        <p v-if="form.errors.description_en" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.description_en }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description (Bangla)</label>
                        <textarea v-model="form.description_bn" rows="4" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#FF6600]/10 outline-none transition-all font-medium"></textarea>
                        <p v-if="form.errors.description_bn" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.description_bn }}</p>
                    </div>
                </div>

                <div>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input v-model="form.is_active" type="checkbox" class="w-5 h-5 text-[#FF6600] rounded border-slate-300 focus:ring-[#FF6600]">
                        <span class="text-sm font-bold text-slate-700">Active (Visible on Store)</span>
                    </label>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" :disabled="form.processing" class="w-full bg-[#FF6600] text-white px-8 py-4 rounded-lg font-bold text-sm uppercase tracking-widest hover:bg-orange-600 transition-all disabled:opacity-50">
                        {{ form.processing ? 'Updating...' : 'Update Subcategory' }}
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
import { ArrowLeft, Image as ImageIcon } from 'lucide-vue-next';

const props = defineProps({
    subcategory: Object,
    categories: Array
});

const previewUrl = ref(null);

const form = useForm({
    _method: 'PUT',
    category_id: props.subcategory.category_id,
    name_en: props.subcategory.name_en || '',
    name_bn: props.subcategory.name_bn || '',
    description_en: props.subcategory.description_en || '',
    description_bn: props.subcategory.description_bn || '',
    is_active: props.subcategory.is_active ? true : false,
    image: null,
});

const handleFileUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(`/admin/subcategories/${props.subcategory.id}`);
};
</script>
