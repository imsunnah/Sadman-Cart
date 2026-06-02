<template>
    <AdminLayout>
        <div class="mb-10 flex justify-between items-center bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-3xl font-bold text-[#003366] tracking-tight">{{ $t('Media Gallery') }}</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $t('Manage all your system assets in one place') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <label class="cursor-pointer bg-[#003366] text-white px-8 py-4 rounded-xl hover:bg-slate-800 transition-all font-bold text-xs uppercase tracking-widest flex items-center shadow-lg active:scale-95">
                    <Plus class="w-4 h-4 mr-3" /> {{ $t('Upload Assets') }}
                    <input type="file" multiple class="hidden" @change="handleUpload" accept="image/*" />
                </label>
            </div>
        </div>

        <div v-if="success" class="mb-8 bg-green-500 text-white p-6 rounded-xl shadow-lg flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <CheckCircle class="w-6 h-6" />
                <span class="text-xs font-bold uppercase tracking-widest">{{ success }}</span>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div class="relative w-full max-w-md">
                <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                <input v-model="searchQuery" type="text" placeholder="Search by filename..." class="w-full pl-12 pr-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold text-sm">
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $t('Total Assets') }}: {{ filteredMedia.length }}</span>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <div v-for="item in filteredMedia" :key="item.path" class="group relative aspect-square bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl hover:border-[#FF6600]/20 transition-all duration-500">
                <img :src="item.url" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                
                <!-- Hover Overlay -->
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center gap-3 p-4">
                    <button @click="copyUrl(item.url)" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-[#003366] hover:bg-[#FF6600] hover:text-white transition-all transform hover:scale-110" title="Copy URL">
                        <Copy class="w-4 h-4" />
                    </button>
                    <button @click="deleteMedia(item.path)" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition-all transform hover:scale-110" title="Delete">
                        <Trash2 class="w-4 h-4" />
                    </button>
                </div>

                <!-- Info Badge -->
                <div class="absolute bottom-0 inset-x-0 bg-white/90 backdrop-blur-md p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                    <p class="text-[9px] font-black text-[#003366] truncate uppercase tracking-tighter">{{ item.name }}</p>
                    <p class="text-[8px] font-bold text-slate-400 mt-0.5">{{ (item.size / 1024).toFixed(1) }} KB</p>
                </div>
            </div>
        </div>

        <div v-if="filteredMedia.length === 0" class="py-32 flex flex-col items-center opacity-30">
            <ImageIcon class="w-20 h-20 mb-6 text-slate-300" />
            <p class="text-sm font-black text-slate-400 uppercase tracking-[0.2em]">{{ $t('No media assets found') }}</p>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteConfirm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
            <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl border border-slate-100 transform transition-all duration-300 scale-100">
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 mb-6 mx-auto">
                    <Trash2 class="w-8 h-8" />
                </div>
                <h3 class="text-lg font-black text-[#003366] text-center mb-2 uppercase tracking-wide">Delete Asset?</h3>
                <p class="text-xs text-slate-500 text-center mb-8 font-medium leading-relaxed">Are you sure you want to permanently delete this media asset? This might break existing product displays.</p>
                <div class="flex gap-4">
                    <button @click="showDeleteConfirm = false" class="flex-1 py-3 px-4 bg-slate-50 hover:bg-slate-100 text-[#003366] font-bold text-xs uppercase tracking-widest rounded-xl transition-all border border-slate-200">
                        {{ $t('Cancel') }}
                    </button>
                    <button @click="confirmDelete" class="flex-1 py-3 px-4 bg-red-500 hover:bg-red-600 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-red-500/20 transition-all">
                        {{ $t('Delete') }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus, Search, Copy, Trash2, Image as ImageIcon, CheckCircle } from 'lucide-vue-next';

const props = defineProps({
    media: Array
});

const searchQuery = ref('');
const success = ref('');
const showDeleteConfirm = ref(false);
const pathToDelete = ref('');

const filteredMedia = computed(() => {
    if (!searchQuery.value) return props.media;
    return props.media.filter(item => 
        item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const handleUpload = (e) => {
    const files = e.target.files;
    if (files.length === 0) return;

    const form = useForm({
        files: Array.from(files)
    });

    form.post('/admin/gallery', {
        onSuccess: () => {
            success.value = 'Files uploaded successfully';
            setTimeout(() => success.value = '', 3000);
        }
    });
};

const copyUrl = (url) => {
    const fullUrl = window.location.origin + url;
    navigator.clipboard.writeText(fullUrl);
    success.value = 'URL copied to clipboard!';
    setTimeout(() => success.value = '', 3000);
};

const deleteMedia = (path) => {
    pathToDelete.value = path;
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    showDeleteConfirm.value = false;
    router.delete('/admin/gallery', {
        data: { path: pathToDelete.value },
        onSuccess: () => {
            success.value = 'File deleted successfully';
            setTimeout(() => success.value = '', 3000);
        }
    });
};
</script>
