<template>
    <AdminLayout>
        <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-[#003366]">Site Settings</h1>
                <p class="text-sm text-slate-500">Update logo, sliders, and contact details</p>
            </div>
            <button @click="saveSettings" :disabled="form.processing" class="bg-[#FF6600] text-white px-6 py-2.5 rounded-lg hover:bg-orange-600 transition-all font-bold text-sm flex items-center shadow-md disabled:opacity-50">
                <Save class="w-4 h-4 mr-2" /> {{ form.processing ? 'Saving...' : 'Save Changes' }}
            </button>
        </div>

        <div v-if="$page.props.flash?.success" class="mb-6 bg-green-500 text-white p-4 rounded-lg shadow-md flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <CheckCircle class="w-5 h-5" />
                <span class="text-sm font-bold">{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="opacity-50 hover:opacity-100"><X class="w-4 h-4" /></button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- General Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
                <div class="flex items-center space-x-3 mb-6 border-b pb-4">
                    <SettingsIcon class="w-5 h-5 text-[#003366]" />
                    <h2 class="text-lg font-bold text-slate-900">General Branding</h2>
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Site Name</label>
                        <input v-model="form.site_name" type="text" class="w-full bg-slate-50 border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#003366]/10 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Site Logo</label>
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center overflow-hidden border border-slate-200">
                                    <img v-if="logoPreview || form.existing_site_logo" :src="logoPreview || form.existing_site_logo" class="h-full object-contain" />
                                    <ImageIcon v-else class="w-6 h-6 text-slate-300" />
                                </div>
                                <input type="file" @input="handleLogoUpload" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#003366] file:text-white" />
                            </div>
                            <button @click.prevent="openGallery('logo')" class="w-full py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-black uppercase tracking-widest text-[#003366] hover:bg-[#003366] hover:text-white transition-all flex items-center justify-center gap-2">
                                <ImageIcon class="w-3 h-3" /> Select From Gallery
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Site Favicon (Browser Tab Icon)</label>
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center overflow-hidden border border-slate-200">
                                    <img v-if="faviconPreview || form.existing_site_favicon" :src="faviconPreview || form.existing_site_favicon" class="h-10 w-10 object-contain" />
                                    <ImageIcon v-else class="w-6 h-6 text-slate-300" />
                                </div>
                                <input type="file" @input="handleFaviconUpload" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#003366] file:text-white" />
                            </div>
                            <button @click.prevent="openGallery('favicon')" class="w-full py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-black uppercase tracking-widest text-[#003366] hover:bg-[#003366] hover:text-white transition-all flex items-center justify-center gap-2">
                                <ImageIcon class="w-3 h-3" /> Select From Gallery
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
                <div class="flex items-center space-x-3 mb-6 border-b pb-4">
                    <ImageIcon class="w-5 h-5 text-[#003366]" />
                    <h2 class="text-lg font-bold text-slate-900">Home Page Sliders</h2>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400 uppercase">Current Sliders</span>
                        <div class="flex items-center gap-4">
                            <button @click.prevent="openGallery('slider')" class="text-xs font-bold text-[#003366] uppercase hover:underline flex items-center gap-1">
                                <ImageIcon class="w-3 h-3" /> Select From Gallery
                            </button>
                            <button @click.prevent="triggerSliderUpload" class="text-xs font-bold text-[#FF6600] uppercase hover:underline">Add New Image</button>
                        </div>
                    </div>
                    <input type="file" multiple ref="sliderInput" @input="handleSliderUpload" class="hidden" />
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div v-for="(img, index) in sliderImages" :key="index" class="relative group aspect-video rounded-lg overflow-hidden border border-slate-100">
                            <img :src="img" class="w-full h-full object-cover">
                            <button @click.prevent="removeSlider(index)" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                <Trash2 class="w-3 h-3" />
                            </button>
                        </div>
                        <div v-if="sliderImages.length === 0" class="col-span-3 py-8 text-center border-2 border-dashed border-slate-100 rounded-xl text-slate-300 text-xs font-bold">
                            No slider images uploaded
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Details -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8 md:col-span-2">
                <div class="flex items-center space-x-3 mb-6 border-b pb-4">
                    <Info class="w-5 h-5 text-[#003366]" />
                    <h2 class="text-lg font-bold text-slate-900">Footer & Contact Details</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">About Description</label>
                        <textarea v-model="form.footer_about" rows="3" class="w-full bg-slate-50 border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#003366]/10 outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Office Address</label>
                        <input v-model="form.footer_address" type="text" class="w-full bg-slate-50 border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#003366]/10 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone Number</label>
                        <input v-model="form.footer_phone" type="text" class="w-full bg-slate-50 border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#003366]/10 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Email Address</label>
                        <input v-model="form.footer_email" type="text" class="w-full bg-slate-50 border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#003366]/10 outline-none">
                    </div>
                </div>
            </div>

            <!-- Hero Section Customization -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8 md:col-span-2">
                <div class="flex items-center space-x-3 mb-6 border-b pb-4">
                    <Layout class="w-5 h-5 text-[#003366]" />
                    <h2 class="text-lg font-bold text-slate-900">Hero Section Configuration</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Slider Text Control -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <div>
                                <h3 class="text-sm font-bold text-[#003366]">Show Slider Text</h3>
                                <p class="text-[10px] text-slate-500 font-medium">Toggle display of text over the hero slider</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.hero_slider_text_show" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#FF6600]"></div>
                            </label>
                        </div>
                        
                        <div v-if="form.hero_slider_text_show">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Slider Overlay Text (HTML allowed)</label>
                            <textarea v-model="form.hero_slider_text" rows="4" class="w-full bg-slate-50 border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#003366]/10 outline-none font-medium" placeholder="e.g. <h1>Fresh Organic</h1><p>Direct from farms</p>"></textarea>
                        </div>
                    </div>

                    <!-- Static Image Control -->
                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Right-side Static Image</label>
                        <div class="flex flex-col gap-4">
                            <div class="w-full aspect-video bg-slate-50 rounded-2xl flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-200 group">
                                <img v-if="heroStaticPreview || form.existing_hero_static_image" :src="heroStaticPreview || form.existing_hero_static_image" class="w-full h-full object-cover" />
                                <div v-else class="flex flex-col items-center gap-2 opacity-30">
                                    <ImageIcon class="w-8 h-8" />
                                    <span class="text-[10px] font-black uppercase">Upload Static Cover</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="file" @input="handleHeroStaticUpload" class="flex-grow text-xs text-slate-500 file:mr-4 file:py-2 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-[#003366] file:text-white cursor-pointer" />
                                <button @click.prevent="openGallery('hero')" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-[#003366] hover:bg-[#003366] hover:text-white transition-all flex items-center justify-center gap-2">
                                    <ImageIcon class="w-3 h-3" /> Gallery
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8 md:col-span-2">
                <div class="flex items-center space-x-3 mb-6 border-b pb-4">
                    <Truck class="w-5 h-5 text-[#003366]" />
                    <h2 class="text-lg font-bold text-slate-900">Delivery Settings</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Inside Dhaka Charge (৳)</label>
                        <input v-model="form.delivery_charge_inside_dhaka" type="number" class="w-full bg-slate-50 border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#003366]/10 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Outside Dhaka Charge (৳)</label>
                        <input v-model="form.delivery_charge_outside_dhaka" type="number" class="w-full bg-slate-50 border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#003366]/10 outline-none">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Media Gallery Modal -->
        <MediaPicker 
            :show="showGallery" 
            @close="showGallery = false" 
            @select="handleGallerySelection" 
        />
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import MediaPicker from '@/Components/MediaPicker.vue';
import { Save, Settings as SettingsIcon, Image as ImageIcon, Info, Plus, Trash2, CheckCircle, X, Truck, Layout } from 'lucide-vue-next';

const props = defineProps({
    settings: Object
});

const getVal = (key) => {
    for (const group in props.settings) {
        const found = props.settings[group].find(s => s.key === key);
        if (found) return found.value;
    }
    return '';
};

const sliderImages = ref(JSON.parse(getVal('slider_images') || '[]'));
const logoPreview = ref(null);
const faviconPreview = ref(null);
const heroStaticPreview = ref(null);
const sliderInput = ref(null);
const showGallery = ref(false);
const galleryTarget = ref(''); // 'logo', 'favicon', 'slider', 'hero'

const form = useForm({
    site_name: getVal('site_name'),
    site_logo: null,
    existing_site_logo: getVal('site_logo'),
    site_favicon: null,
    existing_site_favicon: getVal('site_favicon'),
    footer_about: getVal('footer_about'),
    footer_address: getVal('footer_address'),
    footer_phone: getVal('footer_phone'),
    footer_email: getVal('footer_email'),
    delivery_charge_inside_dhaka: getVal('delivery_charge_inside_dhaka'),
    delivery_charge_outside_dhaka: getVal('delivery_charge_outside_dhaka'),
    slider_upload: [],
    slider_images: sliderImages.value,
    hero_static_image: null,
    existing_hero_static_image: getVal('hero_static_image'),
    hero_slider_text_show: getVal('hero_slider_text_show') == '1',
    hero_slider_text: getVal('hero_slider_text'),
});

const handleLogoUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.site_logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const handleFaviconUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.site_favicon = file;
        faviconPreview.value = URL.createObjectURL(file);
    }
};

const handleHeroStaticUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.hero_static_image = file;
        heroStaticPreview.value = URL.createObjectURL(file);
    }
};

const triggerSliderUpload = () => {
    sliderInput.value.click();
};

const handleSliderUpload = (e) => {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        form.slider_upload.push(file);
        sliderImages.value.push(URL.createObjectURL(file));
    });
};

const removeSlider = (index) => {
    sliderImages.value.splice(index, 1);
    form.slider_images = [...sliderImages.value];
};

const openGallery = (target) => {
    galleryTarget.value = target;
    showGallery.value = true;
};

const handleGallerySelection = (item) => {
    const url = item.url;
    if (galleryTarget.value === 'logo') {
        form.site_logo = url;
        form.existing_site_logo = url;
        logoPreview.value = null;
    } else if (galleryTarget.value === 'favicon') {
        form.site_favicon = url;
        form.existing_site_favicon = url;
        faviconPreview.value = null;
    } else if (galleryTarget.value === 'hero') {
        form.hero_static_image = url;
        form.existing_hero_static_image = url;
        heroStaticPreview.value = null;
    } else if (galleryTarget.value === 'slider') {
        sliderImages.value.push(url);
        form.slider_images = [...sliderImages.value];
    }
    showGallery.value = false;
};

const saveSettings = () => {
    form.post('/admin/settings', {
        onSuccess: () => {
            logoPreview.value = null;
            faviconPreview.value = null;
            form.slider_upload = [];
        }
    });
};
</script>
