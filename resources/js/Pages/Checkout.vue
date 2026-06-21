<template>
    <StoreLayout>
        <div class="bg-slate-50 min-h-screen py-16 font-sans relative">
            <!-- Processing Overlay -->
            <Transition name="fade">
                <div v-if="form.processing" class="fixed inset-0 z-[100] bg-white/90 backdrop-blur-xl flex flex-col items-center justify-center text-slate-900">
                    <div class="relative w-24 h-24 mb-6">
                        <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-[#003366] rounded-full animate-spin border-t-transparent shadow-lg"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <Lock class="w-8 h-8 text-[#003366]" />
                        </div>
                    </div>
                    <h2 class="text-2xl font-black uppercase tracking-tight mb-2">অর্ডার প্রসেসিং হচ্ছে</h2>
                    <p class="text-slate-500 font-bold uppercase tracking-[0.2em] text-[10px]">দয়া করে অপেক্ষা করুন, আপনার কেনাকাটা সুরক্ষিত করা হচ্ছে</p>
                </div>
            </Transition>

            <!-- Confirmation Modal -->
            <Transition name="modal">
                <div v-if="showConfirmModal" class="fixed inset-0 z-[90] flex items-center justify-center px-4">
                    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showConfirmModal = false"></div>
                    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg relative overflow-hidden border border-slate-100">
                        <div class="p-6 sm:p-10">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-[#003366]/5 rounded-2xl flex items-center justify-center mb-6">
                                <ShoppingCart class="w-6 h-6 sm:w-8 sm:h-8 text-[#003366]" />
                            </div>
                            
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase mb-3">অর্ডার সম্পন্ন করবেন?</h2>
                            <p class="text-slate-500 font-medium leading-relaxed mb-8">
                                আপনি <span class="text-[#003366] font-bold">৳{{ subtotal.toLocaleString() }}</span> টাকার একটি অর্ডার দিতে যাচ্ছেন। 
                                কনফার্ম বাটনে ক্লিক করলে আপনার অর্ডারটি প্রসেসিং এর জন্য পাঠানো হবে।
                            </p>

                            <div class="bg-slate-50 rounded-2xl p-5 mb-8 space-y-3 border border-slate-100">
                                <div class="flex items-start text-sm font-bold text-slate-700">
                                    <MapPin class="w-4 h-4 text-[#003366] mr-3 mt-1 flex-shrink-0" />
                                    {{ form.shipping_address }}
                                </div>
                                <div class="flex items-center text-sm font-bold text-slate-700">
                                    <Phone class="w-4 h-4 text-[#003366] mr-3" />
                                    {{ form.customer_phone }}
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <button @click="showConfirmModal = false" class="flex-1 py-3 sm:py-4 rounded-xl text-[11px] font-black text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-all border border-slate-200">
                                    বাতিল করুন
                                </button>
                                <button @click="confirmAndSubmit" class="flex-[2] py-3 sm:py-4 bg-[#003366] rounded-xl text-[11px] font-black text-white uppercase tracking-[0.2em] shadow-lg shadow-[#003366]/20 hover:bg-slate-800 transition-all transform active:scale-95 flex items-center justify-center">
                                    অর্ডার কনফার্ম করুন <ArrowRight class="w-4 h-4 ml-2" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>

            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-6 sm:mb-10">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center uppercase">
                        <ShoppingBag class="w-6 h-6 sm:w-8 sm:h-8 mr-2 sm:mr-3 text-[#003366]" />
                        অর্ডার সম্পন্ন করুন
                    </h1>
                    <div class="h-1 w-20 bg-[#003366] mt-4 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <!-- Checkout Form -->
                    <div class="lg:col-span-7">
                        <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100 p-5 sm:p-10">
                            <form @submit.prevent="validateAndShowModal">
                                <!-- Section 1: Billing & Shipping -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-black text-[#003366] uppercase tracking-[0.2em] mb-8 flex items-center">
                                        <span class="w-8 h-8 rounded-lg bg-[#003366]/10 flex items-center justify-center mr-3 text-xs">১</span>
                                         তথ্য
                                    </h2>
                                    <div class="grid grid-cols-1 gap-8">
                                        <div class="space-y-4">
                                            <div class="group">
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#003366] transition-colors">আপনার নাম <span class="text-rose-500">*</span></label>
                                                <div class="relative">
                                                    <input 
                                                        v-model="form.customer_name" 
                                                        type="text" 
                                                        @input="form.customer_name = form.customer_name.replace(/[0-9]/g, '')"
                                                        placeholder="আপনার সঠিক নাম লিখুন" 
                                                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-[#003366] focus:bg-white focus:ring-4 focus:ring-[#003366]/5 transition-all"
                                                        :class="{ 'border-rose-100 bg-rose-50/30': form.errors.customer_name }"
                                                    >
                                                </div>
                                                <p v-if="form.errors.customer_name" class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest ml-1">{{ form.errors.customer_name }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-3 ml-1">মোবাইল নাম্বার</label>
                                            <input 
                                                v-model="form.customer_phone" 
                                                type="tel" 
                                                required 
                                                @input="form.customer_phone = form.customer_phone.replace(/\D/g, '')"
                                                class="block w-full rounded-2xl bg-slate-50 border border-slate-100 px-6 py-5 text-sm font-bold focus:ring-4 focus:ring-[#003366]/5 focus:bg-white focus:border-[#003366]/20 outline-none transition-all placeholder:text-slate-300" 
                                                placeholder="০১৭xxxxxxxx"
                                            >
                                            <div v-if="form.errors.customer_phone" class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-widest ml-1">{{ form.errors.customer_phone }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-3 ml-1">পূর্ণ ঠিকানা</label>
                                            <textarea v-model="form.shipping_address" rows="3" required class="block w-full rounded-2xl bg-slate-50 border border-slate-100 px-6 py-5 text-sm font-bold focus:ring-4 focus:ring-[#003366]/5 focus:bg-white focus:border-[#003366]/20 outline-none transition-all placeholder:text-slate-300" placeholder="আপনার বিস্তারিত ঠিকানা লিখুন (বাসা নং, রোড নং, এলাকা)"></textarea>
                                            <div v-if="form.errors.shipping_address" class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-widest ml-1">{{ form.errors.shipping_address }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-12">
                                    <h2 class="text-sm font-black text-[#003366] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded-lg bg-[#003366]/10 flex items-center justify-center mr-3 text-xs">২</span>
                                        ডেলিভারি এরিয়া
                                    </h2>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <label 
                                            class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition-all"
                                            :class="form.delivery_location === 'Inside Dhaka' ? 'border-[#003366] bg-slate-50' : 'border-slate-100 hover:border-slate-200'"
                                        >
                                            <input type="radio" v-model="form.delivery_location" value="Inside Dhaka" class="hidden">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center" :class="form.delivery_location === 'Inside Dhaka' ? 'border-[#003366]' : 'border-slate-300'">
                                                    <div v-if="form.delivery_location === 'Inside Dhaka'" class="w-2.5 h-2.5 rounded-full bg-[#003366]"></div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-black text-slate-900 uppercase">ঢাকার ভিতরে</p>
                                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">৳{{ delivery_charges.inside_dhaka }} ডেলিভারি চার্জ</p>
                                                </div>
                                            </div>
                                        </label>

                                        <label 
                                            class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition-all"
                                            :class="form.delivery_location === 'Outside Dhaka' ? 'border-[#003366] bg-slate-50' : 'border-slate-100 hover:border-slate-200'"
                                        >
                                            <input type="radio" v-model="form.delivery_location" value="Outside Dhaka" class="hidden">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center" :class="form.delivery_location === 'Outside Dhaka' ? 'border-[#003366]' : 'border-slate-300'">
                                                    <div v-if="form.delivery_location === 'Outside Dhaka'" class="w-2.5 h-2.5 rounded-full bg-[#003366]"></div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-black text-slate-900 uppercase">ঢাকার বাইরে</p>
                                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">৳{{ delivery_charges.outside_dhaka }} ডেলিভারি চার্জ</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Section 4: Payment -->
                         
                                <!-- Section 3: Remarks -->
                                <div class="mb-12">
                                   
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">অর্ডার নোট</label>
                                        <textarea v-model="form.customer_remarks" rows="3" class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="ডেলিভারি সম্পর্কে কোনো বিশেষ নির্দেশনা থাকলে এখানে লিখুন..."></textarea>
                                    </div>
                                </div>

                                <button type="submit" :disabled="form.processing" class="w-full bg-[#003366] py-5 rounded-2xl text-white font-black uppercase tracking-[0.2em] shadow-xl shadow-[#003366]/20 hover:bg-slate-800 transition-all transform active:scale-[0.98] disabled:opacity-50 flex items-center justify-center">
                                    অর্ডার নিশ্চিত করুন <ArrowRight class="w-5 h-5 ml-3" />
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="lg:col-span-5">
                        <div class="lg:sticky lg:top-24 space-y-6">
                            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100 overflow-hidden">
                                <div class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                                    <h2 class="text-[11px] font-black text-[#003366] uppercase tracking-[0.2em]">অর্ডার সারাংশ</h2>
                                    <span class="px-3 py-1 bg-white rounded-full text-[10px] font-black text-slate-500 border border-slate-100">{{ cart.items.length }} আইটেম</span>
                                </div>
                                <div class="p-6">
                                    <ul role="list" class="divide-y divide-slate-100 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                        <li v-for="item in cart.items" :key="item.id" class="flex py-4">
                                            <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl border border-slate-50 bg-slate-100">
                                                <img v-if="item.product?.image || item.combo?.image" :src="item.product?.image || item.combo?.image" :alt="item.product?.name || item.combo?.name" class="h-full w-full object-cover object-center" />
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <div class="flex items-center gap-2 mb-1" v-if="item.combo_id">
                                                    <span class="text-[7px] font-black bg-[#003366] text-white px-1.5 py-0.5 rounded-full uppercase tracking-widest">কম্বো</span>
                                                </div>
                                                <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-tight line-clamp-1 italic">{{ item.product?.name || item.combo?.name }}</h3>
                                                <div v-if="item.combo_id && item.combo?.products" class="mt-1 flex flex-wrap gap-x-2 gap-y-1">
                                                    <span v-for="p in item.combo.products" :key="p.id" class="text-[7px] font-bold text-slate-400 uppercase tracking-widest border-r border-slate-200 pr-2 last:border-0 last:pr-0">
                                                        {{ p.name }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-[10px] font-bold text-slate-400">পরিমাণ: {{ item.quantity }}</p>
                                                    <p class="text-xs font-black text-slate-900">৳{{ (getItemPrice(item) * item.quantity).toLocaleString() }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>

                                    <div class="border-t border-slate-100 pt-6 mt-4 space-y-3">
                                        <div class="flex justify-between text-[11px] font-bold text-slate-500">
                                            <p>সাবটোটাল</p>
                                            <p>৳{{ subtotal.toLocaleString() }}</p>
                                        </div>
                                        <div class="flex justify-between text-[11px] font-bold text-slate-500">
                                            <p>ডেলিভারি চার্জ ({{ form.delivery_location === 'Inside Dhaka' ? 'ঢাকার ভিতরে' : 'ঢাকার বাইরে' }})</p>
                                            <p>৳{{ currentDeliveryCharge.toLocaleString() }}</p>
                                        </div>
                                        <div class="flex justify-between border-t border-slate-100 pt-5 mt-3">
                                            <p class="text-sm font-black text-slate-900 uppercase italic">সর্বমোট</p>
                                            <p class="text-2xl font-black text-[#003366]">৳{{ total.toLocaleString() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-[#003366] text-white rounded-3xl p-5 sm:p-6 flex items-center space-x-4 shadow-xl shadow-[#003366]/10">
                                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <Truck class="w-5 h-5 text-white" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-[11px] font-black uppercase tracking-widest">খুব দ্রুত ডেলিভারি</p>
                                    <p class="text-[9px] font-bold text-white/50 uppercase tracking-widest mt-0.5">অনুমানিত ২-৩ কার্যদিবস</p>
                                </div>
                            </div>       
                            <div class="mb-12">
                                
                                    <div class="bg-slate-50 border-2 border-[#003366] rounded-2xl p-6 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-5 h-5 rounded-full border-4 border-[#003366] mr-4 flex-shrink-0"></div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900 uppercase">ক্যাশ অন ডেলিভারি</p>
                                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">পণ্য হাতে পেয়ে টাকা পরিশোধ করুন</p>
                                            </div>
                                        </div>
                                        <Banknote class="w-8 h-8 text-[#003366]/20" />
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <transition name="fade">
            <div v-if="showConfirmModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <transition name="modal">
                    <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 max-w-md w-full shadow-2xl relative overflow-hidden">
                        <!-- Decoration -->
                        <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#003366]/5 rounded-full"></div>
                        <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-[#003366]/5 rounded-full"></div>

                        <div class="relative">
                            <div class="w-20 h-20 bg-[#003366] rounded-3xl flex items-center justify-center mb-8 mx-auto shadow-xl shadow-[#003366]/20 rotate-12">
                                <Package class="w-10 h-10 text-white -rotate-12" />
                            </div>

                            <h3 class="text-2xl font-black text-slate-900 text-center mb-4 uppercase tracking-tight italic">অর্ডার নিশ্চিত করুন</h3>
                            <p class="text-sm font-bold text-slate-500 text-center mb-8 uppercase tracking-widest leading-relaxed">
                                আপনি কি নিশ্চিত? <span class="text-[#003366]">৳{{ currentDeliveryCharge }}</span> ডেলিভারি চার্জ যোগ করা হবে <span class="text-[#003366]">{{ form.delivery_location === 'Inside Dhaka' ? 'ঢাকার ভিতরে' : 'ঢাকার বাইরে' }}</span> ডেলিভারির জন্য।
                            </p>

                            <div class="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-100">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">সাবটোটাল</span>
                                    <span class="text-sm font-bold text-slate-900">৳{{ subtotal.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-200">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ডেলিভারি চার্জ</span>
                                    <span class="text-sm font-bold text-[#003366]">৳{{ currentDeliveryCharge.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-black text-slate-900 uppercase tracking-widest italic">সর্বমোট</span>
                                    <span class="text-2xl font-black text-[#003366]">৳{{ total.toLocaleString() }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <button @click="showConfirmModal = false" class="px-6 py-4 rounded-xl border-2 border-slate-100 text-slate-500 font-black uppercase tracking-widest text-[10px] hover:bg-slate-50 transition-all">
                                    ফিরে যান
                                </button>
                                <button @click="confirmAndSubmit" class="px-6 py-4 rounded-xl bg-[#003366] text-white font-black uppercase tracking-widest text-[10px] hover:bg-slate-800 transition-all shadow-lg shadow-[#003366]/20">
                                    অর্ডার নিশ্চিত করুন
                                </button>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </StoreLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { useCart } from '@/Composables/useCart';
import { usePixel } from '@/Composables/usePixel';
import { Lock, Package, ArrowRight, Banknote, Truck, X, ShoppingCart, MapPin, Phone, ShoppingBag } from 'lucide-vue-next';

const props = defineProps({
    cart: Object,
    delivery_charges: Object,
    selectedItemIds: Array
});

const { clearCart } = useCart();
const { trackInitiateCheckout, trackPurchase } = usePixel();
const showConfirmModal = ref(false);

const form = useForm({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    shipping_address: '',
    district: '',
    upazila: '',
    village: '',
    delivery_location: 'Outside Dhaka',
    delivery_charge: 0,
    selected_items: props.selectedItemIds ? props.selectedItemIds.join(',') : '',
    customer_remarks: ''
});

const currentDeliveryCharge = computed(() => {
    return form.delivery_location === 'Inside Dhaka' 
        ? parseFloat(props.delivery_charges.inside_dhaka) 
        : parseFloat(props.delivery_charges.outside_dhaka);
});

const validateAndShowModal = () => {
    form.clearErrors();
    let hasError = false;

    if (form.customer_name.length < 3) {
        form.setError('customer_name', 'নাম অন্তত ৩ অক্ষরের হতে হবে।');
        hasError = true;
    } else if (/^\.+$/.test(form.customer_name)) {
        form.setError('customer_name', 'নাম সঠিক হতে হবে।');
        hasError = true;
    }

    if (form.customer_phone.length !== 11 || !/^\d+$/.test(form.customer_phone)) {
        form.setError('customer_phone', 'মোবাইল নাম্বার ১১ ডিজিটের হতে হবে।');
        hasError = true;
    }

    if (form.shipping_address.length < 7) {
        form.setError('shipping_address', 'ঠিকানা অন্তত ৭ অক্ষরের হতে হবে।');
        hasError = true;
    } else if (/^\.+$/.test(form.shipping_address)) {
        form.setError('shipping_address', 'ঠিকানা সঠিক হতে হবে।');
        hasError = true;
    }

    if (!hasError) {
        showConfirmModal.value = true;
    }
};

const getItemPrice = (item) => {
    if (item.product) {
        const price = parseFloat(item.product.price || 0);
        if (!item.product.discount_type) return price;
        if (item.product.discount_type === 'percentage') {
            return price * (1 - parseFloat(item.product.discount_value || 0) / 100);
        }
        return Math.max(0, price - parseFloat(item.product.discount_value || 0));
    }
    return parseFloat(item.combo?.price || 0);
};

const subtotal = computed(() => {
    return props.cart.items.reduce((total, item) => {
        return total + (getItemPrice(item) * item.quantity);
    }, 0);
});

const total = computed(() => {
    return subtotal.value + currentDeliveryCharge.value;
});

onMounted(() => {
    const page = usePage();
    if (page.props.auth?.user) {
        form.customer_name = page.props.auth.user.name || '';
        form.customer_email = page.props.auth.user.email || '';
    }
    
    // Track InitiateCheckout when user lands on checkout page
    if (props.cart?.items?.length > 0) {
        trackInitiateCheckout(props.cart.items, total.value);
    }
});

const confirmAndSubmit = () => {
    showConfirmModal.value = false;
    form.delivery_charge = currentDeliveryCharge.value;
    form.post('/checkout', {
        onSuccess: (page) => {
            // Track Purchase event on success
            // Page props might contain the newly created order
            const order = page.props.order || {
                id: 'INV-' + Date.now(),
                total_amount: total.value,
                items: props.cart.items
            };
            
            trackPurchase(order);
            clearCart();
        }
    });
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 51, 102, 0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 51, 102, 0.1);
}

/* Modal Animations */
.modal-enter-active, .modal-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.modal-enter-from, .modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
}

/* Fade Animations */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
