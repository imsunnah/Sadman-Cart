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
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">District</label>
                                <input v-model="form.district" type="text" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-[#003366]/5 text-sm font-bold transition-all" placeholder="Enter district" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Upazila / Thana</label>
                                <input v-model="form.upazila" type="text" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-[#003366]/5 text-sm font-bold transition-all" placeholder="Enter upazila" />
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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Add Product</label>
                                    <select @change="addItem($event, 'product')" class="w-full px-5 py-3 rounded-2xl bg-[#003366]/5 border-none text-xs font-bold focus:ring-4 focus:ring-[#003366]/5">
                                        <option value="">Select a Product</option>
                                        <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }} (Stock: {{ p.stock }})</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Add Combo Bundle</label>
                                    <select @change="addItem($event, 'combo')" class="w-full px-5 py-3 rounded-2xl bg-[#FF6600]/5 border-none text-xs font-bold focus:ring-4 focus:ring-[#FF6600]/5">
                                        <option value="">Select a Combo</option>
                                        <option v-for="c in combos" :key="c.id" :value="c.id">{{ c.name }} (৳{{ c.price }})</option>
                                    </select>
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
import { computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ArrowLeft, User, ShoppingCart, Trash2, Package } from 'lucide-vue-next';

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

const addItem = (event, type) => {
    const id = parseInt(event.target.value);
    if (!id) return;

    // Check if already added
    const exists = form.items.find(i => i.id === id && i.type === type);
    if (exists) {
        exists.quantity++;
    } else {
        form.items.push({
            type,
            id,
            quantity: 1
        });
    }
    event.target.value = ''; // Reset select
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
        return parseFloat(props.products.find(p => p.id === item.id)?.price || 0);
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
