<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 font-sans">
            <div class="mb-10 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight italic uppercase">Create <span class="text-[#003366]">Manual Order</span></h1>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">Direct logistics initialization protocol</p>
                </div>
                <Link href="/admin/orders" class="text-[10px] font-black text-[#003366] hover:text-[#FF6600] uppercase tracking-widest transition-colors flex items-center gap-2">
                    <ArrowLeft class="w-4 h-4" /> Back to orders
                </Link>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Customer Details -->
                <div class="lg:col-span-7 space-y-8">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                        <h2 class="text-xs font-black text-[#003366] uppercase tracking-[0.2em] mb-8 flex items-center gap-2 italic">
                            <User class="w-4 h-4" /> Customer Protocol
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                                <input v-model="form.customer_name" type="text" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-[#003366]/5 text-sm font-bold transition-all" placeholder="Enter customer name" />
                                <div v-if="form.errors.customer_name" class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ form.errors.customer_name }}</div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                                <input v-model="form.customer_phone" type="text" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-[#003366]/5 text-sm font-bold transition-all" placeholder="01XXXXXXXXX" />
                                <div v-if="form.errors.customer_phone" class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ form.errors.customer_phone }}</div>
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address (Optional)</label>
                                <input v-model="form.customer_email" type="email" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-[#003366]/5 text-sm font-bold transition-all" placeholder="customer@example.com" />
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Shipping Address</label>
                                <textarea v-model="form.shipping_address" rows="3" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-[#003366]/5 text-sm font-bold transition-all" placeholder="House, Road, Block..."></textarea>
                                <div v-if="form.errors.shipping_address" class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ form.errors.shipping_address }}</div>
                            </div>
                            <div class="space-y-2 relative">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">District</label>
                                <div class="relative">
                                    <select v-model="form.district" @change="handleDistrictChange" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-[#003366]/5 text-sm font-bold transition-all appearance-none pr-10">
                                        <option value="" disabled>Select District</option>
                                        <option v-for="d in districts" :key="d.district" :value="d.district">{{ d.district }}</option>
                                    </select>
                                    <ChevronDown class="absolute right-4 top-3.5 w-4 h-4 text-slate-400 pointer-events-none" />
                                </div>
                                <div v-if="form.errors.district" class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ form.errors.district }}</div>
                            </div>
                            <div class="space-y-2 relative">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Upazila / Thana</label>
                                <div class="relative">
                                    <select v-if="upazilas.length > 0" v-model="form.upazila" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-[#003366]/5 text-sm font-bold transition-all appearance-none pr-10">
                                        <option value="" disabled>Select Area</option>
                                        <option v-for="u in upazilas" :key="u" :value="u">{{ u }}</option>
                                    </select>
                                    <input 
                                        v-else 
                                        v-model="form.upazila" 
                                        type="text" 
                                        :placeholder="loadingUpazilas ? 'Loading areas...' : 'Enter Thana / Upazila'"
                                        class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-[#003366]/5 text-sm font-bold transition-all" 
                                    />
                                    <ChevronDown v-if="upazilas.length > 0" class="absolute right-4 top-3.5 w-4 h-4 text-slate-400 pointer-events-none" />
                                </div>
                                <div v-if="form.errors.upazila" class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ form.errors.upazila }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                        <h2 class="text-xs font-black text-[#003366] uppercase tracking-[0.2em] mb-8 flex items-center gap-2 italic">
                            <ShoppingCart class="w-4 h-4" /> Item Selection
                        </h2>

                        <div class="space-y-6">
                            <!-- Selected Items -->
                            <div v-for="(item, index) in form.items" :key="index" class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 group">
                                <div class="w-12 h-12 bg-white rounded-xl flex-shrink-0 flex items-center justify-center p-2">
                                    <img :src="getItemImage(item)" class="w-full h-full object-contain" />
                                </div>
                                <div class="flex-grow">
                                    <p class="text-[11px] font-black text-[#003366] uppercase tracking-tight italic">{{ getItemName(item) }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">৳{{ getItemPrice(item).toLocaleString() }}</p>
                                </div>
                                <div class="flex items-center bg-white rounded-lg border border-slate-200">
                                    <button @click.prevent="updateItemQty(index, -1)" class="px-3 py-1 hover:text-[#FF6600]">-</button>
                                    <span class="px-3 py-1 text-xs font-black text-[#003366]">{{ item.quantity }}</span>
                                    <button @click.prevent="updateItemQty(index, 1)" class="px-3 py-1 hover:text-[#FF6600]">+</button>
                                </div>
                                <button @click.prevent="removeItem(index)" class="p-2 text-slate-300 hover:text-red-500 transition-colors">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>

                            <!-- Add New Item Section -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                                <!-- Searchable Product Selection -->
                                <div class="space-y-2 relative">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Add Product</label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            v-model="productSearch"
                                            @focus="showProductDropdown = true"
                                            @blur="closeProductDropdown"
                                            placeholder="Type to search products..." 
                                            class="w-full px-5 py-3 rounded-2xl bg-[#003366]/5 border-none text-xs font-bold focus:ring-4 focus:ring-[#003366]/5 pr-10"
                                        />
                                        <Search class="w-4 h-4 text-slate-400 absolute right-4 top-3.5 pointer-events-none" />
                                    </div>
                                    
                                    <div v-show="showProductDropdown && filteredProducts.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-2xl shadow-lg max-h-60 overflow-y-auto divide-y divide-slate-50">
                                        <button 
                                            v-for="p in filteredProducts" 
                                            :key="p.id" 
                                            @mousedown.prevent="selectProduct(p)"
                                            class="w-full text-left px-4 py-2.5 text-xs hover:bg-[#003366]/5 transition-colors flex items-center justify-between"
                                        >
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-8 h-8 rounded bg-slate-50 flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-100 p-0.5">
                                                    <img 
                                                        v-if="p.image" 
                                                        :src="p.image.startsWith('http') ? p.image : `/storage/${p.image}`" 
                                                        class="w-full h-full object-contain" 
                                                    />
                                                    <Package v-else class="w-4 h-4 text-slate-300" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-slate-700 truncate italic">{{ p.name }}</p>
                                                    <p class="text-[9px] font-bold text-slate-400">৳{{ parseFloat(p.price).toLocaleString() }}</p>
                                                </div>
                                            </div>
                                            <span class="font-black shrink-0 text-slate-400 bg-slate-100 px-2 py-0.5 rounded text-[9px] ml-2">Stock: {{ p.stock }}</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Searchable Combo Selection -->
                                <div class="space-y-2 relative">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Add Combo Bundle</label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            v-model="comboSearch"
                                            @focus="showComboDropdown = true"
                                            @blur="closeComboDropdown"
                                            placeholder="Type to search combos..." 
                                            class="w-full px-5 py-3 rounded-2xl bg-[#FF6600]/5 border-none text-xs font-bold focus:ring-4 focus:ring-[#FF6600]/5 pr-10"
                                        />
                                        <Search class="w-4 h-4 text-slate-400 absolute right-4 top-3.5 pointer-events-none" />
                                    </div>

                                    <div v-show="showComboDropdown && filteredCombos.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-2xl shadow-lg max-h-60 overflow-y-auto divide-y divide-slate-50">
                                        <button 
                                            v-for="c in filteredCombos" 
                                            :key="c.id" 
                                            @mousedown.prevent="selectCombo(c)"
                                            class="w-full text-left px-4 py-2.5 text-xs hover:bg-[#FF6600]/5 transition-colors flex items-center justify-between"
                                        >
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-8 h-8 rounded bg-slate-50 flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-100 p-0.5">
                                                    <img 
                                                        v-if="c.image" 
                                                        :src="c.image.startsWith('http') ? c.image : `/storage/${c.image}`" 
                                                        class="w-full h-full object-contain" 
                                                    />
                                                    <Package v-else class="w-4 h-4 text-slate-300" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-slate-700 truncate italic">{{ c.name }}</p>
                                                    <p class="text-[9px] font-bold text-slate-400">৳{{ parseFloat(c.price).toLocaleString() }}</p>
                                                </div>
                                            </div>
                                            <span class="font-black shrink-0 text-[#FF6600] bg-orange-50 px-2 py-0.5 rounded text-[9px] ml-2">Bundle</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logistics & Totals -->
                <div class="lg:col-span-5 space-y-8">
                    <div class="bg-[#003366] text-white p-8 rounded-3xl shadow-xl shadow-[#003366]/20 sticky top-24">
                        <h2 class="text-xs font-black mb-10 border-b border-white/10 pb-6 uppercase tracking-[0.3em] italic">Logistics Summary</h2>
                        
                        <div class="space-y-6">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Delivery Location</label>
                                <select v-model="form.delivery_location" @change="updateDeliveryCharge" class="w-full px-5 py-3 rounded-2xl bg-white/10 border-none text-sm font-bold focus:ring-4 focus:ring-white/10">
                                    <option value="Inside Dhaka" class="text-slate-900">Inside Dhaka</option>
                                    <option value="Outside Dhaka" class="text-slate-900">Outside Dhaka</option>
                                </select>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Delivery Charge</label>
                                <input v-model="form.delivery_charge" type="number" class="w-full px-5 py-3 rounded-2xl bg-white/10 border-none text-sm font-bold focus:ring-4 focus:ring-white/10" />
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Order Status</label>
                                <select v-model="form.status" class="w-full px-5 py-3 rounded-2xl bg-white/10 border-none text-sm font-bold focus:ring-4 focus:ring-white/10">
                                    <option value="pending" class="text-slate-900">Pending</option>
                                    <option value="processing" class="text-slate-900">Processing</option>
                                    <option value="completed" class="text-slate-900">Completed</option>
                                    <option value="cancelled" class="text-slate-900">Cancelled</option>
                                </select>
                            </div>

                            <div class="pt-10 border-t border-white/10 space-y-4">
                                <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    <span>Items Subtotal</span>
                                    <span>৳{{ subtotal.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    <span>Delivery Protocol</span>
                                    <span>৳{{ parseFloat(form.delivery_charge || 0).toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between border-t border-white/20 pt-6 mt-4">
                                    <span class="text-xl font-black italic uppercase tracking-tighter">Grand Total</span>
                                    <span class="text-3xl font-black text-[#FF6600]">৳{{ total.toLocaleString() }}</span>
                                </div>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing || form.items.length === 0"
                                class="w-full mt-10 py-5 bg-[#FF6600] text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl shadow-orange-500/20 hover:bg-white hover:text-[#003366] transition-all disabled:opacity-50"
                            >
                                <span v-if="form.processing">Processing...</span>
                                <span v-else>Confirm Manual Order</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ArrowLeft, User, ShoppingCart, Trash2, Package, Search, ChevronDown } from 'lucide-vue-next';

const props = defineProps({
    products: Array,
    combos: Array
});

const form = useForm({
    customer_name: '',
    customer_phone: '',
    customer_email: '',
    shipping_address: '',
    district: '',
    upazila: '',
    delivery_location: 'Inside Dhaka',
    delivery_charge: 60,
    status: 'pending',
    items: []
});

const staticDistricts = [
    { district: "Bagerhat" }, { district: "Bandarban" }, { district: "Barguna" }, 
    { district: "Barishal" }, { district: "Bhola" }, { district: "Bogra" }, 
    { district: "Brahmanbaria" }, { district: "Chandpur" }, { district: "Chattogram" }, 
    { district: "Chuadanga" }, { district: "Comilla" }, { district: "Cox's Bazar" }, 
    { district: "Dhaka" }, { district: "Dinajpur" }, { district: "Faridpur" }, 
    { district: "Feni" }, { district: "Gaibandha" }, { district: "Gazipur" }, 
    { district: "Gopalganj" }, { district: "Habiganj" }, { district: "Jamalpur" }, 
    { district: "Jashore" }, { district: "Jhalokati" }, { district: "Jhenaidah" }, 
    { district: "Joypurhat" }, { district: "Khagrachhari" }, { district: "Khulna" }, 
    { district: "Kishoreganj" }, { district: "Kurigram" }, { district: "Kushtia" }, 
    { district: "Lakshmipur" }, { district: "Lalmonirhat" }, { district: "Madaripur" }, 
    { district: "Magura" }, { district: "Manikganj" }, { district: "Meherpur" }, 
    { district: "Moulvibazar" }, { district: "Munshiganj" }, { district: "Mymensingh" }, 
    { district: "Naogaon" }, { district: "Narail" }, { district: "Narayanganj" }, 
    { district: "Narsingdi" }, { district: "Natore" }, { district: "Netrokona" }, 
    { district: "Nilphamari" }, { district: "Noakhali" }, { district: "Pabna" }, 
    { district: "Panchagarh" }, { district: "Patuakhali" }, { district: "Pirojpur" }, 
    { district: "Rajbari" }, { district: "Rajshahi" }, { district: "Rangamati" }, 
    { district: "Rangpur" }, { district: "Satkhira" }, { district: "Shariatpur" }, 
    { district: "Sherpur" }, { district: "Sirajganj" }, { district: "Sunamganj" }, 
    { district: "Sylhet" }, { district: "Tangail" }, { district: "Thakurgaon" }
];

const districts = ref(staticDistricts);
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
        if (result && result.data) {
            districts.value = result.data.sort((a, b) => a.district.localeCompare(b.district));
        }
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
    updateDeliveryCharge();

    if (form.district) {
        loadingUpazilas.value = true;
        try {
            const response = await fetch(`https://bdapis.com/api/v1.2/district/${form.district.toLowerCase()}`);
            const result = await response.json();
            
            let apiUpazilas = [];
            if (result.data && result.data[0]) {
                apiUpazilas = result.data[0].upazillas || [];
            }

            const extraThanas = metropolitanThanas[form.district] || [];
            const combined = [...new Set([...apiUpazilas, ...extraThanas])];
            
            upazilas.value = combined.sort();
        } catch (error) {
            console.error('Error fetching upazilas:', error);
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

const productSearch = ref('');
const comboSearch = ref('');
const showProductDropdown = ref(false);
const showComboDropdown = ref(false);

const closeProductDropdown = () => {
    setTimeout(() => {
        showProductDropdown.value = false;
    }, 250);
};

const closeComboDropdown = () => {
    setTimeout(() => {
        showComboDropdown.value = false;
    }, 250);
};

const filteredProducts = computed(() => {
    if (!productSearch.value) return props.products;
    const query = productSearch.value.toLowerCase();
    return props.products.filter(p => p.name.toLowerCase().includes(query));
});

const filteredCombos = computed(() => {
    if (!comboSearch.value) return props.combos;
    const query = comboSearch.value.toLowerCase();
    return props.combos.filter(c => c.name.toLowerCase().includes(query));
});

const selectProduct = (product) => {
    const id = product.id;
    const exists = form.items.find(i => i.id === id && i.type === 'product');
    if (exists) {
        exists.quantity++;
    } else {
        form.items.push({
            type: 'product',
            id,
            quantity: 1
        });
    }
    productSearch.value = '';
    showProductDropdown.value = false;
};

const selectCombo = (combo) => {
    const id = combo.id;
    const exists = form.items.find(i => i.id === id && i.type === 'combo');
    if (exists) {
        exists.quantity++;
    } else {
        form.items.push({
            type: 'combo',
            id,
            quantity: 1
        });
    }
    comboSearch.value = '';
    showComboDropdown.value = false;
};

const updateItemQty = (index, delta) => {
    const newQty = form.items[index].quantity + delta;
    if (newQty >= 1) {
        form.items[index].quantity = newQty;
    }
};

const removeItem = (index) => {
    form.items.splice(index, 1);
};

const getItemName = (item) => {
    if (item.type === 'product') {
        return props.products.find(p => p.id === item.id)?.name || 'Unknown Product';
    }
    return props.combos.find(c => c.id === item.id)?.name || 'Unknown Combo';
};

const getItemPrice = (item) => {
    if (item.type === 'product') {
        const product = props.products.find(p => p.id === item.id);
        if (!product) return 0;
        const price = parseFloat(product.price || 0);
        if (!product.discount_type) return price;
        if (product.discount_type === 'percentage') {
            return price * (1 - parseFloat(product.discount_value || 0) / 100);
        }
        return Math.max(0, price - parseFloat(product.discount_value || 0));
    }
    return parseFloat(props.combos.find(c => c.id === item.id)?.price || 0);
};

const getItemImage = (item) => {
    if (item.type === 'product') {
        return props.products.find(p => p.id === item.id)?.image;
    }
    return props.combos.find(c => c.id === item.id)?.image;
};

const updateDeliveryCharge = () => {
    form.delivery_charge = form.delivery_location === 'Inside Dhaka' ? 60 : 120;
};

const subtotal = computed(() => {
    return form.items.reduce((total, item) => {
        return total + (getItemPrice(item) * item.quantity);
    }, 0);
});

const total = computed(() => {
    return subtotal.value + parseFloat(form.delivery_charge || 0);
});

const submit = () => {
    form.post(route('admin.orders.store'));
};
</script>
