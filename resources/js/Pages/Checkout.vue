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
                    <h2 class="text-2xl font-black uppercase tracking-tight mb-2">Processing Order</h2>
                    <p class="text-slate-500 font-bold uppercase tracking-[0.2em] text-[10px]">Please wait while we secure your purchase</p>
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
                            
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase mb-3">Place Your Order?</h2>
                            <p class="text-slate-500 font-medium leading-relaxed mb-8">
                                You are placing an order for <span class="text-[#003366] font-bold">৳{{ subtotal.toLocaleString() }}</span>. 
                                By clicking confirm, your order will be sent for processing.
                            </p>

                            <div class="bg-slate-50 rounded-2xl p-5 mb-8 space-y-3 border border-slate-100">
                                <div class="flex items-center text-sm font-bold text-slate-700">
                                    <MapPin class="w-4 h-4 text-[#003366] mr-3" />
                                    {{ form.district }}, {{ form.upazila }}
                                </div>
                                <div class="flex items-center text-sm font-bold text-slate-700">
                                    <Phone class="w-4 h-4 text-[#003366] mr-3" />
                                    {{ form.customer_phone }}
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <button @click="showConfirmModal = false" class="flex-1 py-3 sm:py-4 rounded-xl text-[11px] font-black text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-all border border-slate-200">
                                    Cancel
                                </button>
                                <button @click="confirmAndSubmit" class="flex-[2] py-3 sm:py-4 bg-[#003366] rounded-xl text-[11px] font-black text-white uppercase tracking-[0.2em] shadow-lg shadow-[#003366]/20 hover:bg-slate-800 transition-all transform active:scale-95 flex items-center justify-center">
                                    Confirm Order <ArrowRight class="w-4 h-4 ml-2" />
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
                        Checkout
                    </h1>
                    <div class="h-1 w-20 bg-[#003366] mt-4 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <!-- Checkout Form -->
                    <div class="lg:col-span-7">
                        <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100 p-5 sm:p-10">
                            <form @submit.prevent="showConfirmModal = true">
                                <!-- Section 1: Contact -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-black text-[#003366] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded-lg bg-[#003366]/10 flex items-center justify-center mr-3 text-xs">1</span>
                                        Contact Information
                                    </h2>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                                            <input v-model="form.customer_name" type="text" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="e.g. John Doe">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Phone Number</label>
                                            <input v-model="form.customer_phone" type="text" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="01XXXXXXXXX">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Email (Optional)</label>
                                            <input v-model="form.customer_email" type="email" class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="john@example.com">
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 2: Shipping -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-black text-[#003366] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded-lg bg-[#003366]/10 flex items-center justify-center mr-3 text-xs">2</span>
                                        Shipping Address
                                    </h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div class="relative">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">District</label>
                                            <select v-model="form.district" @change="handleDistrictChange" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all appearance-none pr-10">
                                                <option value="" disabled>Select District</option>
                                                <option v-for="d in districts" :key="d.district" :value="d.district">{{ d.district }}</option>
                                            </select>
                                            <ChevronDown class="absolute right-4 bottom-4 w-4 h-4 text-slate-400 pointer-events-none" />
                                        </div>
                                        <div class="relative">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Area / Thana / Upazila</label>
                                            <select v-model="form.upazila" :disabled="!form.district || loadingUpazilas" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all appearance-none pr-10 disabled:opacity-50">
                                                <option value="" disabled>{{ loadingUpazilas ? 'Loading...' : 'Select Area' }}</option>
                                                <option v-for="u in upazilas" :key="u" :value="u">{{ u }}</option>
                                            </select>
                                            <ChevronDown class="absolute right-4 bottom-4 w-4 h-4 text-slate-400 pointer-events-none" />
                                        </div>
                                    </div>
                                    <div class="mb-6">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Village / Area / House</label>
                                        <input v-model="form.village" type="text" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="Detailed address details">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Full Address (Summary)</label>
                                        <textarea v-model="form.shipping_address" rows="2" required class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="Enter your full searchable address"></textarea>
                                    </div>
                                </div>

                                <!-- Section 3: Delivery Location -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-black text-[#003366] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded-lg bg-[#003366]/10 flex items-center justify-center mr-3 text-xs">3</span>
                                        Delivery Location
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
                                                    <p class="text-sm font-black text-slate-900 uppercase">Inside Dhaka</p>
                                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">৳{{ delivery_charges.inside_dhaka }} Delivery Charge</p>
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
                                                    <p class="text-sm font-black text-slate-900 uppercase">Outside Dhaka</p>
                                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">৳{{ delivery_charges.outside_dhaka }} Delivery Charge</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Section 4: Payment -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-black text-[#003366] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded-lg bg-[#003366]/10 flex items-center justify-center mr-3 text-xs">4</span>
                                        Payment Method
                                    </h2>
                                    <div class="bg-slate-50 border-2 border-[#003366] rounded-2xl p-6 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-5 h-5 rounded-full border-4 border-[#003366] mr-4 flex-shrink-0"></div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900 uppercase">Cash on Delivery</p>
                                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pay when you receive your order</p>
                                            </div>
                                        </div>
                                        <Banknote class="w-8 h-8 text-[#003366]/20" />
                                    </div>
                                </div>

                                <!-- Section 5: Customer Remarks -->
                                <div class="mb-12">
                                    <h2 class="text-sm font-black text-[#003366] uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-8 h-8 rounded-lg bg-[#003366]/10 flex items-center justify-center mr-3 text-xs">5</span>
                                        Special Instructions / Remarks
                                    </h2>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Order Note / Customer Remarks</label>
                                        <textarea v-model="form.customer_remarks" rows="3" class="block w-full rounded-xl bg-slate-50 border border-slate-100 px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-[#003366]/10 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="e.g. Any special instructions regarding delivery, product requests, packaging, etc."></textarea>
                                    </div>
                                </div>

                                <button type="submit" :disabled="form.processing" class="w-full bg-[#003366] py-5 rounded-2xl text-white font-black uppercase tracking-[0.2em] shadow-xl shadow-[#003366]/20 hover:bg-slate-800 transition-all transform active:scale-[0.98] disabled:opacity-50 flex items-center justify-center">
                                    Continue to Confirmation <ArrowRight class="w-5 h-5 ml-3" />
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="lg:col-span-5">
                        <div class="lg:sticky lg:top-24 space-y-6">
                            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100 overflow-hidden">
                                <div class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                                    <h2 class="text-[11px] font-black text-[#003366] uppercase tracking-[0.2em]">Order Summary</h2>
                                    <span class="px-3 py-1 bg-white rounded-full text-[10px] font-black text-slate-500 border border-slate-100">{{ cart.items.length }} Items</span>
                                </div>
                                <div class="p-6">
                                    <ul role="list" class="divide-y divide-slate-100 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                        <li v-for="item in cart.items" :key="item.id" class="flex py-4">
                                            <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl border border-slate-50 bg-slate-100">
                                                <img v-if="item.product?.image || item.combo?.image" :src="item.product?.image || item.combo?.image" :alt="item.product?.name || item.combo?.name" class="h-full w-full object-cover object-center" />
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <div class="flex items-center gap-2 mb-1" v-if="item.combo_id">
                                                    <span class="text-[7px] font-black bg-[#003366] text-white px-1.5 py-0.5 rounded-full uppercase tracking-widest">Combo</span>
                                                </div>
                                                <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-tight line-clamp-1 italic">{{ item.product?.name || item.combo?.name }}</h3>
                                                <div v-if="item.combo_id && item.combo?.products" class="mt-1 flex flex-wrap gap-x-2 gap-y-1">
                                                    <span v-for="p in item.combo.products" :key="p.id" class="text-[7px] font-bold text-slate-400 uppercase tracking-widest border-r border-slate-200 pr-2 last:border-0 last:pr-0">
                                                        {{ p.name }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-[10px] font-bold text-slate-400">Qty {{ item.quantity }}</p>
                                                    <p class="text-xs font-black text-slate-900">৳{{ (getItemPrice(item) * item.quantity).toLocaleString() }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>

                                    <div class="border-t border-slate-100 pt-6 mt-4 space-y-3">
                                        <div class="flex justify-between text-[11px] font-bold text-slate-500">
                                            <p>Subtotal</p>
                                            <p>৳{{ subtotal.toLocaleString() }}</p>
                                        </div>
                                        <div class="flex justify-between text-[11px] font-bold text-slate-500">
                                            <p>Shipping ({{ form.delivery_location }})</p>
                                            <p>৳{{ currentDeliveryCharge.toLocaleString() }}</p>
                                        </div>
                                        <div class="flex justify-between border-t border-slate-100 pt-5 mt-3">
                                            <p class="text-sm font-black text-slate-900 uppercase italic">Total to pay</p>
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
                                    <p class="text-[11px] font-black uppercase tracking-widest">Steadfast Delivery</p>
                                    <p class="text-[9px] font-bold text-white/50 uppercase tracking-widest mt-0.5">Estimated 2-3 business days</p>
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

                            <h3 class="text-2xl font-black text-slate-900 text-center mb-4 uppercase tracking-tight italic">Confirm Your Order</h3>
                            <p class="text-sm font-bold text-slate-500 text-center mb-8 uppercase tracking-widest leading-relaxed">
                                Are you sure? <span class="text-[#003366]">৳{{ currentDeliveryCharge }}</span> delivery charge will be added for <span class="text-[#003366]">{{ form.delivery_location }}</span> delivery.
                            </p>

                            <div class="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-100">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Subtotal</span>
                                    <span class="text-sm font-bold text-slate-900">৳{{ subtotal.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-200">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Shipping</span>
                                    <span class="text-sm font-bold text-[#003366]">৳{{ currentDeliveryCharge.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-black text-slate-900 uppercase tracking-widest italic">Total to pay</span>
                                    <span class="text-2xl font-black text-[#003366]">৳{{ total.toLocaleString() }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <button @click="showConfirmModal = false" class="px-6 py-4 rounded-xl border-2 border-slate-100 text-slate-500 font-black uppercase tracking-widest text-[10px] hover:bg-slate-50 transition-all">
                                    Go Back
                                </button>
                                <button @click="confirmAndSubmit" class="px-6 py-4 rounded-xl bg-[#003366] text-white font-black uppercase tracking-widest text-[10px] hover:bg-slate-800 transition-all shadow-lg shadow-[#003366]/20">
                                    Confirm Order
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
import { useForm } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { useCart } from '@/Composables/useCart';
import { Lock, Package, ArrowRight, Banknote, Truck, X, ShoppingCart, MapPin, Phone, ShoppingBag, ChevronDown } from 'lucide-vue-next';

const props = defineProps({
    cart: Object,
    delivery_charges: Object,
    selectedItemIds: Array
});

const { clearCart } = useCart();
const showConfirmModal = ref(false);

const form = useForm({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    shipping_address: '',
    district: '',
    upazila: '',
    village: '',
    delivery_location: 'Inside Dhaka',
    delivery_charge: 0,
    selected_items: props.selectedItemIds ? props.selectedItemIds.join(',') : '',
    customer_remarks: ''
});

const currentDeliveryCharge = computed(() => {
    return form.delivery_location === 'Inside Dhaka' 
        ? parseFloat(props.delivery_charges.inside_dhaka) 
        : parseFloat(props.delivery_charges.outside_dhaka);
});

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

const districts = ref([]);
const upazilas = ref([]);
const loadingUpazilas = ref(false);

const metropolitanThanas = {
    'Dhaka': [
        "Adabor", "Airport", "Badda", "Banani", "Bangshal", "Bhashantek", "Cantonment", 
        "Chackbazar", "Dakshin Khan", "Darus-Salam", "Demra", "Dhanmondi", "Gandaria", 
        "Gulshan", "Hatirjheel", "Hazaribagh", "Jattrabari", "Kadamtoli", "Kafrul", 
        "Kalabagan", "Kamrangirchar", "Khilgaon", "Khilkhet", "Kotwali", "Lalbagh", 
        "Mirpur Model", "Mohammadpur", "Motijheel", "Mugda", "New Market", "Pallabi", 
        "Paltan Model", "Ramna Model", "Rampura", "Rupnagar", "Sabujbag", "Shah Ali", 
        "Shahbag", "Shahjahanpur", "Sher e Bangla Nagar", "Shyampur", "Sutrapur", 
        "Tejgaon", "Tejgaon Industrial", "Turag", "Uttar Khan", "Uttara East", 
        "Uttara West", "Vatara", "Wari", "Dhamrai", "Dohar", "Keraniganj", "Nawabganj", "Savar"
    ],
    'Chattogram': [
        "Kotwali", "Chandgaon", "Panchlaish", "Doublemooring", "Pahartali", "Bandar", 
        "Baijid Bostami", "Hali Shohor", "Kornafuli", "Potenga", "Bakolia", "Akborsha",
        "Anwara", "Banshkhali", "Boalkhali", "Chandanaish", "Fatikchhari", "Hathazari", 
        "Lohagara", "Mirsharai", "Patiya", "Rangunia", "Raozan", "Sandwip", "Satkania", "Sitakunda"
    ]
};

const fetchDistricts = async () => {
    try {
        const response = await fetch('https://bdapis.com/api/v1.2/districts');
        const result = await response.json();
        districts.value = result.data.sort((a, b) => a.district.localeCompare(b.district));
    } catch (error) {
        console.error('Error fetching districts:', error);
    }
};

const handleDistrictChange = async () => {
    form.upazila = '';
    upazilas.value = [];
    
    // Auto-set delivery location
    if (form.district === 'Dhaka') {
        form.delivery_location = 'Inside Dhaka';
    } else {
        form.delivery_location = 'Outside Dhaka';
    }

    if (form.district) {
        loadingUpazilas.value = true;
        try {
            const response = await fetch(`https://bdapis.com/api/v1.2/district/${form.district.toLowerCase()}`);
            const result = await response.json();
            
            let apiUpazilas = [];
            if (result.data && result.data[0]) {
                apiUpazilas = result.data[0].upazillas || [];
            }

            // Merge with metropolitan thanas if available
            const extraThanas = metropolitanThanas[form.district] || [];
            const combined = [...new Set([...apiUpazilas, ...extraThanas])];
            
            upazilas.value = combined.sort();
        } catch (error) {
            console.error('Error fetching upazilas:', error);
            // Fallback to only metropolitan if API fails
            if (metropolitanThanas[form.district]) {
                upazilas.value = metropolitanThanas[form.district].sort();
            }
        } finally {
            loadingUpazilas.value = false;
        }
    }
};

onMounted(() => {
    fetchDistricts();
});

const confirmAndSubmit = () => {
    showConfirmModal.value = false;
    form.delivery_charge = currentDeliveryCharge.value;
    form.post('/checkout', {
        onSuccess: () => {
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
