<template>
    <AdminLayout>
        <div class="mb-10 flex items-center justify-between bg-white p-8 rounded-xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-[#003366]">Create New Dynamic Page</h1>
                <p class="text-xs text-slate-500 mt-1">Publish a new informational page in your storefront footer links</p>
            </div>
            <Link href="/admin/pages" class="text-xs font-bold text-slate-500 hover:text-[#003366] flex items-center transition-all">
                <ArrowLeft class="w-4 h-4 mr-2" />
                Back to List
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden max-w-4xl">
            <form @submit.prevent="submit" class="p-8 space-y-8">
                <!-- Title & Group -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Page Title (English)</label>
                        <input v-model="form.title_en" type="text" placeholder="e.g. Return Policy" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                        <p v-if="form.errors.title_en" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.title_en }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Page Title (Bangla)</label>
                        <input v-model="form.title_bn" type="text" placeholder="e.g. রিটার্ন পলিসি" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                        <p v-if="form.errors.title_bn" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.title_bn }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Page Group / Section</label>
                        <select v-model="form.group" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold">
                            <option value="about_us">Information (About Us, Contact Us, Careers)</option>
                            <option value="support">Support (FAQ, Shipping, How to Order)</option>
                            <option value="consumer_policy">Consumer Policy (Happy Return, Refunds)</option>
                        </select>
                        <p v-if="form.errors.group" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.group }}</p>
                    </div>
                    
                    <!-- Page Banner Image -->
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Page Header/Banner Image</label>
                        <div class="mt-2 flex items-center space-x-6">
                            <div class="shrink-0 w-32 h-20 bg-slate-100 rounded-xl flex items-center justify-center overflow-hidden border border-slate-200">
                                <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-cover" />
                                <ImageIcon v-else class="w-8 h-8 text-slate-300" />
                            </div>
                            <input type="file" @input="handleFileUpload" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#003366] file:text-white hover:file:bg-slate-800" />
                        </div>
                        <p v-if="form.errors.image" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.image }}</p>
                    </div>
                </div>

                <!-- Short Summary / Meta Description -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Short Description / Summary (English)</label>
                        <textarea v-model="form.description_en" rows="3" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-medium placeholder:text-slate-400" placeholder="Brief description..."></textarea>
                        <p v-if="form.errors.description_en" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.description_en }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Short Description / Summary (Bangla)</label>
                        <textarea v-model="form.description_bn" rows="3" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-medium placeholder:text-slate-400" placeholder="সংক্ষিপ্ত বিবরণ..."></textarea>
                        <p v-if="form.errors.description_bn" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.description_bn }}</p>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Page Content (English - HTML Format Allowed)</label>
                    <textarea v-model="form.content_en" rows="8" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-medium font-mono text-sm" placeholder="<h1>Page Heading</h1><p>Write your HTML or plain text content here...</p>"></textarea>
                    <p v-if="form.errors.content_en" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.content_en }}</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Page Content (Bangla - HTML Format Allowed)</label>
                    <textarea v-model="form.content_bn" rows="8" class="w-full px-4 py-3 rounded-lg bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-medium font-mono text-sm" placeholder="<h1>শিরোনাম</h1><p>এখানে আপনার বিষয়বস্তু লিখুন...</p>"></textarea>
                    <p v-if="form.errors.content_bn" class="mt-2 text-xs text-red-500 font-bold">{{ form.errors.content_bn }}</p>
                </div>

                <div>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input v-model="form.is_active" type="checkbox" class="w-5 h-5 text-[#003366] rounded border-slate-300 focus:ring-[#003366]">
                        <span class="text-sm font-bold text-slate-700">Active (Visible in Storefront Footer)</span>
                    </label>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" :disabled="form.processing" class="w-full bg-[#003366] text-white px-8 py-4 rounded-lg font-bold text-sm uppercase tracking-widest hover:bg-slate-800 transition-all disabled:opacity-50">
                        {{ form.processing ? 'Publishing...' : 'Publish Page' }}
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

const previewUrl = ref(null);

const form = useForm({
    title_en: '',
    title_bn: '',
    group: 'about_us',
    description_en: '',
    description_bn: '',
    image: null,
    content_en: '',
    content_bn: '',
    is_active: true,
});

const handleFileUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post('/admin/pages');
};
</script>
