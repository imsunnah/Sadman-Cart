<template>
    <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md">
        <div class="bg-white rounded-[3rem] w-full max-w-5xl h-[85vh] overflow-hidden flex flex-col shadow-2xl animate-in fade-in zoom-in duration-300">
            <!-- Header -->
            <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-[#003366] uppercase tracking-tight italic">Select Media</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Pick an existing asset or upload new ones</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative w-64">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                        <input v-model="searchQuery" type="text" placeholder="Search..." class="w-full pl-12 pr-6 py-3 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all font-bold text-xs">
                    </div>
                    <button @click="$emit('close')" class="p-4 bg-slate-50 rounded-2xl hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-all">
                        <X class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-grow overflow-y-auto p-8">
                <div v-if="loading" class="h-full flex items-center justify-center">
                    <div class="w-12 h-12 border-4 border-slate-100 border-t-[#003366] rounded-full animate-spin"></div>
                </div>
                
                <div v-else-if="media.length > 0" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-4">
                    <!-- Upload New Option -->
                    <label class="aspect-square rounded-3xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-3 cursor-pointer hover:border-[#FF6600]/30 hover:bg-orange-50 transition-all group">
                        <Upload class="w-6 h-6 text-slate-300 group-hover:text-[#FF6600]" />
                        <span class="text-[9px] font-black text-slate-300 group-hover:text-[#FF6600] uppercase tracking-widest">Upload New</span>
                        <input type="file" multiple class="hidden" @change="handleUpload" accept="image/*" />
                    </label>

                    <!-- Media Items -->
                    <div v-for="item in filteredMedia" :key="item.path" 
                        @click="selectImage(item)"
                        class="group relative aspect-square rounded-3xl overflow-hidden cursor-pointer border-2 transition-all duration-300"
                        :class="[selectedPath === item.path ? 'border-[#FF6600] scale-95 shadow-inner' : 'border-transparent hover:border-slate-200']"
                    >
                        <img :src="item.url" class="w-full h-full object-cover" />
                        <div v-if="selectedPath === item.path" class="absolute inset-0 bg-[#FF6600]/20 flex items-center justify-center">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg">
                                <Check class="w-5 h-5 text-[#FF6600]" />
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-[9px] font-black text-white uppercase tracking-widest bg-black/40 px-3 py-1.5 rounded-full backdrop-blur-md">Select</span>
                        </div>
                    </div>
                </div>

                <div v-else class="h-full flex flex-col items-center justify-center opacity-30">
                    <ImageIcon class="w-16 h-16 mb-4 text-slate-300" />
                    <p class="text-xs font-black uppercase tracking-widest">No assets found</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-8 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    {{ filteredMedia.length }} Assets Found
                </span>
                <div class="flex gap-4">
                    <button @click="$emit('close')" class="px-8 py-4 rounded-2xl text-slate-400 font-black uppercase tracking-widest text-[10px] hover:text-slate-600 transition-colors">Cancel</button>
                    <button @click="confirmSelection" :disabled="!selectedImage" class="px-10 py-4 bg-[#003366] text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-black transition-all shadow-xl shadow-blue-900/20 active:scale-95 disabled:opacity-50">
                        Confirm Selection
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { Search, X, Check, Upload, Image as ImageIcon } from 'lucide-vue-next';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
    initialSelection: String // Path or URL
});

const emit = defineEmits(['close', 'select']);

const loading = ref(true);
const media = ref([]);
const searchQuery = ref('');
const selectedPath = ref('');
const selectedImage = ref(null);

const fetchMedia = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/admin/api/media');
        media.value = response.data;
    } catch (error) {
        console.error('Error fetching media:', error);
    } finally {
        loading.value = false;
    }
};

const filteredMedia = computed(() => {
    if (!searchQuery.value) return media.value;
    return media.value.filter(item => 
        item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const selectImage = (item) => {
    selectedPath.value = item.path;
    selectedImage.value = item;
};

const confirmSelection = () => {
    if (selectedImage.value) {
        emit('select', selectedImage.value);
        emit('close');
    }
};

const handleUpload = async (e) => {
    const files = e.target.files;
    if (files.length === 0) return;

    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }

    try {
        loading.value = true;
        await axios.post('/admin/gallery', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        await fetchMedia();
    } catch (error) {
        console.error('Upload failed:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchMedia();
});
</script>
