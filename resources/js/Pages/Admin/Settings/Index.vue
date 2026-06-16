<template>
    <AdminLayout>
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60 gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-[#003366] tracking-tight">Site Settings</h1>
                <p class="text-sm text-slate-500 mt-1">Manage your branding, sliders, and contact information.</p>
            </div>
            <button @click="saveSettings" :disabled="form.processing" class="bg-[#FF6600] text-white px-6 py-2.5 rounded-xl hover:bg-[#e65c00] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 font-bold text-sm flex items-center justify-center shadow-md disabled:opacity-50 disabled:pointer-events-none disabled:transform-none">
                <Save class="w-4 h-4 mr-2" /> {{ form.processing ? 'Saving...' : 'Save Changes' }}
            </button>
        </div>

        <div v-if="$page.props.flash?.success" class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl shadow-sm flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center space-x-3">
                <CheckCircle class="w-5 h-5 text-emerald-500" />
                <span class="text-sm font-semibold">{{ $page.props.flash.success }}</span>
            </div>
            <button @click="$page.props.flash.success = null" class="text-emerald-500 hover:text-emerald-700 transition-colors p-1"><X class="w-4 h-4" /></button>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 pb-12">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="bg-slate-50/50 border-b border-slate-100 px-6 py-5 flex items-center space-x-3">
                    <div class="p-2 bg-[#003366]/10 rounded-lg text-[#003366]">
                        <SettingsIcon class="w-5 h-5" />
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">General Branding</h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-2">Site Name (English)</label>
                            <input v-model="form.site_name_en" type="text" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none placeholder:text-slate-400">
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-2">Site Name (Bangla)</label>
                            <input v-model="form.site_name_bn" type="text" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none placeholder:text-slate-400">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                            <label class="block text-[13px] font-semibold text-slate-700 mb-3">Site Logo</label>
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center overflow-hidden border border-slate-200 shrink-0">
                                        <img v-if="logoPreview || form.existing_site_logo" :src="logoPreview || form.existing_site_logo" class="h-full w-full object-contain p-1" />
                                        <ImageIcon v-else class="w-6 h-6 text-slate-300" />
                                    </div>
                                    <input type="file" @input="handleLogoUpload" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#003366] file:text-white hover:file:bg-[#002244] file:transition-colors file:cursor-pointer cursor-pointer" />
                                </div>
                                <button @click.prevent="openGallery('logo')" class="w-full py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-[#003366] hover:border-[#003366]/30 transition-all flex items-center justify-center gap-2">
                                    <ImageIcon class="w-3.5 h-3.5" /> Browse Gallery
                                </button>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                            <label class="block text-[13px] font-semibold text-slate-700 mb-3">Favicon</label>
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center overflow-hidden border border-slate-200 shrink-0">
                                        <img v-if="faviconPreview || form.existing_site_favicon" :src="faviconPreview || form.existing_site_favicon" class="h-8 w-8 object-contain" />
                                        <ImageIcon v-else class="w-6 h-6 text-slate-300" />
                                    </div>
                                    <input type="file" @input="handleFaviconUpload" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#003366] file:text-white hover:file:bg-[#002244] file:transition-colors file:cursor-pointer cursor-pointer" />
                                </div>
                                <button @click.prevent="openGallery('favicon')" class="w-full py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-[#003366] hover:border-[#003366]/30 transition-all flex items-center justify-center gap-2">
                                    <ImageIcon class="w-3.5 h-3.5" /> Browse Gallery
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden flex flex-col">
                <div class="bg-slate-50/50 border-b border-slate-100 px-6 py-5 flex items-center space-x-3">
                    <div class="p-2 bg-[#003366]/10 rounded-lg text-[#003366]">
                        <ImageIcon class="w-5 h-5" />
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Home Page Sliders</h2>
                </div>
                <div class="p-6 flex-grow flex flex-col space-y-4">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <span class="text-[13px] font-semibold text-slate-700">Active Sliders</span>
                        <div class="flex items-center gap-3">
                            <button @click.prevent="openGallery('slider')" class="px-3 py-1.5 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5">
                                <ImageIcon class="w-3.5 h-3.5" /> Gallery
                            </button>
                            <button @click.prevent="triggerSliderUpload" class="px-3 py-1.5 bg-[#FF6600]/10 text-[#FF6600] hover:bg-[#FF6600]/20 rounded-lg text-xs font-bold transition-colors flex items-center gap-1.5">
                                <Plus class="w-3.5 h-3.5" /> Upload New
                            </button>
                        </div>
                    </div>
                    <input type="file" multiple ref="sliderInput" @input="handleSliderUpload" class="hidden" />
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-2">
                        <div v-for="(img, index) in sliderImages" :key="index" class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 shadow-sm bg-slate-50">
                            <img :src="img" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button @click.prevent="removeSlider(index)" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transform scale-75 group-hover:scale-100 transition-all shadow-md">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                        <div v-if="sliderImages.length === 0" class="col-span-full py-12 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <ImageIcon class="w-8 h-8 text-slate-300 mb-2" />
                            <span class="text-slate-400 text-sm font-medium">No slider images uploaded</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden xl:col-span-2">
                <div class="bg-slate-50/50 border-b border-slate-100 px-6 py-5 flex items-center space-x-3">
                    <div class="p-2 bg-[#003366]/10 rounded-lg text-[#003366]">
                        <Layout class="w-5 h-5" />
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Hero Section Configuration</h2>
                </div>
                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-5 bg-white border border-slate-200 shadow-sm rounded-xl">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Slider Overlay Text</h3>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">Toggle text display over the hero slider</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.hero_slider_text_show" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#FF6600]"></div>
                            </label>
                        </div>
                        
                        <div v-if="form.hero_slider_text_show" class="space-y-5 animate-fade-in-down">
                            <div>
                                <label class="block text-[13px] font-semibold text-slate-700 mb-2">Text (English) <span class="text-xs text-slate-400 font-normal ml-1">HTML Allowed</span></label>
                                <textarea v-model="form.hero_slider_text_en" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none font-mono text-slate-700" placeholder="e.g. <h1>Fresh Organic</h1>"></textarea>
                            </div>
                            <div>
                                <label class="block text-[13px] font-semibold text-slate-700 mb-2">Text (Bangla) <span class="text-xs text-slate-400 font-normal ml-1">HTML Allowed</span></label>
                                <textarea v-model="form.hero_slider_text_bn" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none font-mono text-slate-700" placeholder="e.g. <h1>সতেজ অর্গানিক</h1>"></textarea>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-2">Right-side Static Image</label>
                        <div class="flex flex-col gap-4 p-5 rounded-xl border border-slate-100 bg-slate-50/50 h-[calc(100%-28px)]">
                            <div class="w-full flex-grow bg-white rounded-xl flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-200 group min-h-[160px]">
                                <img v-if="heroStaticPreview || form.existing_hero_static_image" :src="heroStaticPreview || form.existing_hero_static_image" class="w-full h-full object-cover" />
                                <div v-else class="flex flex-col items-center gap-2 text-slate-400">
                                    <ImageIcon class="w-8 h-8 opacity-50" />
                                    <span class="text-xs font-semibold">Upload Cover Image</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 pt-2">
                                <input type="file" @input="handleHeroStaticUpload" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#003366] file:text-white hover:file:bg-[#002244] file:transition-colors file:cursor-pointer cursor-pointer" />
                                <button @click.prevent="openGallery('hero')" class="shrink-0 px-4 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-[#003366] hover:border-[#003366]/30 transition-all flex items-center justify-center gap-2">
                                    <ImageIcon class="w-3.5 h-3.5" /> Gallery
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden xl:col-span-2">
                <div class="bg-slate-50/50 border-b border-slate-100 px-6 py-5 flex items-center space-x-3">
                    <div class="p-2 bg-[#003366]/10 rounded-lg text-[#003366]">
                        <Info class="w-5 h-5" />
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Footer & Contact Details</h2>
                </div>
                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-x-10 gap-y-6">
                    <div class="lg:col-span-2 grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-2">About Description (English)</label>
                            <textarea v-model="form.footer_about_en" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none placeholder:text-slate-400"></textarea>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-2">About Description (Bangla)</label>
                            <textarea v-model="form.footer_about_bn" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none placeholder:text-slate-400"></textarea>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-2">Office Address (English)</label>
                        <input v-model="form.footer_address_en" type="text" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none placeholder:text-slate-400">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-2">Office Address (Bangla)</label>
                        <input v-model="form.footer_address_bn" type="text" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none placeholder:text-slate-400">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-2">Phone Number</label>
                        <input v-model="form.footer_phone" type="text" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none placeholder:text-slate-400">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-2">Email Address</label>
                        <input v-model="form.footer_email" type="email" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none placeholder:text-slate-400">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden xl:col-span-2">
                <div class="bg-slate-50/50 border-b border-slate-100 px-6 py-5 flex items-center space-x-3">
                    <div class="p-2 bg-[#003366]/10 rounded-lg text-[#003366]">
                        <Truck class="w-5 h-5" />
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Delivery Settings</h2>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-2">Inside Dhaka Charge</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-medium select-none">৳</span>
                            <input v-model="form.delivery_charge_inside_dhaka" type="number" class="w-full bg-white border border-slate-200 rounded-xl pl-8 pr-4 py-2.5 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none placeholder:text-slate-400">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-2">Outside Dhaka Charge</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-medium select-none">৳</span>
                            <input v-model="form.delivery_charge_outside_dhaka" type="number" class="w-full bg-white border border-slate-200 rounded-xl pl-8 pr-4 py-2.5 text-sm transition-all focus:border-[#003366] focus:ring-4 focus:ring-[#003366]/10 outline-none placeholder:text-slate-400">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden xl:col-span-2">
                <div class="bg-slate-50/50 border-b border-slate-100 px-6 py-5 flex items-center space-x-3">
                    <div class="p-2 bg-[#003366]/10 rounded-lg text-[#003366]">
                        <Share2 class="w-5 h-5" />
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Social Media Links</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-for="(social, index) in form.social_media" :key="index" class="p-4 bg-slate-50/50 border border-slate-200 rounded-xl hover:bg-white hover:border-[#003366]/20 transition-colors group">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-800">{{ social.name }}</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.social_media[index].is_active" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#003366]"></div>
                                <span class="ml-2 text-xs font-semibold text-slate-500">{{ social.is_active ? 'Active' : 'Hidden' }}</span>
                            </label>
                        </div>
                        <div>
                            <input v-model="form.social_media[index].url" type="url" :disabled="!social.is_active" placeholder="https://..." class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm transition-all focus:border-[#003366] focus:ring-2 focus:ring-[#003366]/10 outline-none disabled:bg-slate-100 disabled:text-slate-400">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
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
import { Save, Settings as SettingsIcon, Image as ImageIcon, Info, Plus, Trash2, CheckCircle, X, Truck, Layout, Share2 } from 'lucide-vue-next';

const props = defineProps({
    settings: Object
});

const getVal = (key, locale = 'en') => {
    for (const group in props.settings) {
        const found = props.settings[group].find(s => s.key === key);
        if (found) {
            if (locale === 'bn') return found.value_bn || '';
            return found.value_en || found.value || '';
        }
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
    site_name_en: getVal('site_name', 'en'),
    site_name_bn: getVal('site_name', 'bn'),
    site_logo: null,
    existing_site_logo: getVal('site_logo'),
    site_favicon: null,
    existing_site_favicon: getVal('site_favicon'),
    footer_about_en: getVal('footer_about', 'en'),
    footer_about_bn: getVal('footer_about', 'bn'),
    footer_address_en: getVal('footer_address', 'en'),
    footer_address_bn: getVal('footer_address', 'bn'),
    footer_phone: getVal('footer_phone'),
    footer_email: getVal('footer_email'),
    delivery_charge_inside_dhaka: getVal('delivery_charge_inside_dhaka'),
    delivery_charge_outside_dhaka: getVal('delivery_charge_outside_dhaka'),
    slider_upload: [],
    slider_images: sliderImages.value,
    hero_static_image: null,
    existing_hero_static_image: getVal('hero_static_image'),
    hero_slider_text_show: getVal('hero_slider_text_show') == '1',
    hero_slider_text_en: getVal('hero_slider_text', 'en'),
    hero_slider_text_bn: getVal('hero_slider_text', 'bn'),
    social_media: (() => {
        const defaultSocialMedia = [
            { name: 'Facebook', icon: 'Facebook', url: '', is_active: false },
            { name: 'Instagram', icon: 'Instagram', url: '', is_active: false },
            { name: 'Twitter', icon: 'Twitter', url: '', is_active: false },
            { name: 'LinkedIn', icon: 'Linkedin', url: '', is_active: false },
            { name: 'WhatsApp', icon: 'MessageCircle', url: '', is_active: false },
            { name: 'Threads', icon: 'AtSign', url: '', is_active: false }
        ];
        
        let existingSocialMedia = [];
        if (props.settings && props.settings.social && props.settings.social.length > 0) {
            const smSetting = props.settings.social.find(s => s.key === 'social_media');
            if (smSetting && smSetting.value_en) {
                try {
                    existingSocialMedia = JSON.parse(smSetting.value_en);
                } catch (e) {}
            }
        }
        
        return defaultSocialMedia.map(defaultItem => {
            const existingItem = existingSocialMedia.find(item => item.name === defaultItem.name);
            return existingItem ? { ...defaultItem, ...existingItem } : defaultItem;
        });
    })(),
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
