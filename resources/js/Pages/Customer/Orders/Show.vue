<template>
    <StoreLayout>
        <div class="bg-[#F8F9FA] min-h-screen py-10 sm:py-16 px-4 font-sans">
            <div class="max-w-[1400px] mx-auto">
                
                <!-- Back Link & Header -->
                <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
                    <Link 
                        href="/customer/orders" 
                        class="inline-flex items-center text-[10px] font-black text-[#003366] hover:text-[#FF6600] uppercase tracking-widest transition-colors"
                    >
                        <ArrowLeft class="w-4 h-4 mr-2" /> Back to History
                    </Link>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-slate-400 uppercase">Order ID:</span>
                        <span class="text-lg font-black text-[#003366] italic bg-white border border-slate-100 px-4 py-1.5 rounded-xl shadow-sm">
                            #{{ String(order.id).padStart(6, '0') }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <!-- Left: Tracing Timeline & Chat with Admin (Takes 2 cols) -->
                    <div class="lg:col-span-2 space-y-8">
                        
                        <!-- Order Tracing Timeline -->
                        <div class="bg-white rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] border border-slate-100 p-6 sm:p-8">
                            <h3 class="font-black text-xs text-[#003366] uppercase tracking-widest mb-8 flex items-center italic">
                                <Activity class="w-4 h-4 mr-2 text-[#FF6600]" /> Live Order Tracing
                            </h3>

                            <!-- Visual Status Progression Steps -->
                            <div class="grid grid-cols-4 gap-2 mb-10 text-center relative">
                                <div class="absolute top-4 left-[12.5%] right-[12.5%] h-1 bg-slate-100 -z-0">
                                    <div 
                                        class="h-full bg-[#FF6600] transition-all duration-500"
                                        :style="{ width: timelineProgressPercent + '%' }"
                                    ></div>
                                </div>

                                <div 
                                    v-for="(step, idx) in steps" 
                                    :key="step.status" 
                                    class="relative z-10 flex flex-col items-center"
                                >
                                    <div 
                                        class="w-9 h-9 rounded-full flex items-center justify-center border-4 transition-all"
                                        :class="isStepActive(step.status) 
                                            ? 'bg-[#FF6600] text-white border-orange-100' 
                                            : 'bg-white text-slate-300 border-slate-100'"
                                    >
                                        <component :is="step.icon" class="w-4 h-4" />
                                    </div>
                                    <span 
                                        class="text-[9px] font-black uppercase tracking-wider mt-3 block"
                                        :class="isStepActive(step.status) ? 'text-[#003366]' : 'text-slate-400'"
                                    >
                                        {{ step.label }}
                                    </span>
                                </div>
                            </div>

                            <!-- Detailed Status Log -->
                            <div class="space-y-6 border-l-2 border-slate-100 pl-6 ml-4">
                                <div 
                                    v-for="log in order.status_histories" 
                                    :key="log.id" 
                                    class="relative group"
                                >
                                    <!-- Indicator Dot -->
                                    <div class="absolute -left-[31px] top-1 w-2.5 h-2.5 rounded-full bg-[#FF6600] border-2 border-white ring-4 ring-orange-50 group-first:ring-orange-100"></div>
                                    
                                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 transition-colors hover:border-[#003366]/10">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-[10px] font-black uppercase tracking-wider text-[#003366]">
                                                {{ log.status }}
                                            </span>
                                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                                                {{ new Date(log.created_at).toLocaleString() }}
                                            </span>
                                        </div>
                                        <p class="text-xs font-bold text-slate-600 leading-relaxed">{{ log.note }}</p>
                                        <p v-if="log.changer" class="text-[8px] font-bold text-[#FF6600] uppercase tracking-widest mt-2">
                                            Logged by: {{ log.changer.name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chat with Support Admin -->
                        <div class="bg-white rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] border border-slate-100 p-6 sm:p-8 flex flex-col h-[550px]">
                            <div class="flex justify-between items-center pb-4 border-b border-slate-50 mb-4">
                                <h3 class="font-black text-xs text-[#003366] uppercase tracking-widest flex items-center italic">
                                    <MessageSquare class="w-4 h-4 mr-2 text-[#FF6600]" /> Support Dispatch Chat
                                </h3>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Support Agent Connected</span>
                                </span>
                            </div>

                            <!-- Messages Area -->
                            <div 
                                ref="messageContainer"
                                class="flex-1 overflow-y-auto space-y-4 pr-2 mb-4 custom-scrollbar"
                            >
                                <div v-if="messagesList.length === 0" class="text-center py-12 text-slate-400 font-bold text-xs uppercase tracking-widest">
                                    <Send class="w-8 h-8 text-slate-200 mx-auto mb-3" />
                                    No protocol communication initiated. Send a transmission below.
                                </div>
                                <div 
                                    v-else 
                                    v-for="msg in messagesList" 
                                    :key="msg.id"
                                    class="flex"
                                    :class="msg.is_from_admin ? 'justify-start' : 'justify-end'"
                                >
                                    <div 
                                        class="max-w-[80%] rounded-2xl p-4 shadow-sm border"
                                        :class="msg.is_from_admin 
                                            ? 'bg-slate-50 text-slate-800 border-slate-100 rounded-tl-none' 
                                            : 'bg-[#003366] text-white border-[#003366] rounded-tr-none'"
                                    >
                                        <div class="flex items-center justify-between gap-4 mb-1">
                                            <span class="text-[8px] font-black uppercase tracking-widest" :class="msg.is_from_admin ? 'text-[#FF6600]' : 'text-orange-200'">
                                                {{ msg.is_from_admin ? 'Admin Support' : 'You' }}
                                            </span>
                                            <span class="text-[7px] font-bold opacity-60">
                                                {{ formatTime(msg.created_at) }}
                                            </span>
                                        </div>
                                        <p class="text-xs font-bold whitespace-pre-wrap leading-relaxed">{{ msg.message }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Chat Form -->
                            <form @submit.prevent="sendMessage" class="flex gap-3 items-end pt-4 border-t border-slate-50">
                                <textarea 
                                    v-model="newMessage"
                                    placeholder="Enter your message protocol..."
                                    rows="2"
                                    class="flex-1 rounded-xl border border-slate-100 bg-slate-50 p-3 text-xs font-bold outline-none focus:bg-white focus:border-[#003366]/20 transition-all resize-none shadow-inner"
                                    @keydown.enter.prevent="sendMessage"
                                ></textarea>
                                <button 
                                    type="submit"
                                    :disabled="sending"
                                    class="w-12 h-12 rounded-xl bg-[#003366] text-white flex items-center justify-center hover:bg-[#FF6600] active:scale-95 transition-all shadow-md shadow-blue-900/10 hover:shadow-orange-500/20 disabled:opacity-50 flex-shrink-0"
                                >
                                    <Send class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right: Order Summary & Info (Takes 1 col) -->
                    <div class="space-y-8">
                        
                        <!-- Pricing Details -->
                        <div class="bg-white rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden">
                            <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                                <h3 class="font-black text-xs text-[#003366] uppercase tracking-widest flex items-center italic">
                                    <ShoppingBag class="w-4 h-4 mr-2 text-slate-400" /> Dispatch Details
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4 max-h-72 overflow-y-auto pr-2 custom-scrollbar">
                                    <div 
                                        v-for="item in order.items" 
                                        :key="item.id" 
                                        class="flex items-center justify-between text-xs py-3 border-b border-slate-50 last:border-0"
                                    >
                                        <div class="flex flex-1 items-start min-w-0 pr-3">
                                            <span class="font-black text-white bg-[#003366] px-2 py-0.5 rounded text-[8px] mr-2 mt-0.5 flex-shrink-0">
                                                {{ item.quantity }}x
                                            </span>
                                            <span class="text-slate-700 font-bold uppercase tracking-tight italic truncate">
                                                {{ item.product_name }}
                                            </span>
                                        </div>
                                        <span class="font-black text-slate-900 flex-shrink-0">
                                            ৳ {{ parseFloat(item.price * item.quantity).toLocaleString() }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-slate-100 space-y-3">
                                    <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        <p>Subtotal</p>
                                        <p class="text-slate-900">৳ {{ (order.total_amount - order.delivery_charge).toLocaleString() }}</p>
                                    </div>
                                    <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        <p>Delivery Charge</p>
                                        <p class="text-[#FF6600]">৳ {{ parseFloat(order.delivery_charge).toLocaleString() }}</p>
                                    </div>
                                    <div class="flex justify-between border-t border-dashed border-slate-200 pt-6 mt-4">
                                        <p class="text-xs font-black text-slate-900 italic uppercase tracking-tighter">Total Payable</p>
                                        <p class="text-xl font-black text-[#003366]">৳ {{ parseFloat(order.total_amount).toLocaleString() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Logistics Profile -->
                        <div class="bg-white rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] border border-slate-100 p-6 sm:p-8">
                            <h3 class="font-black text-xs text-[#003366] uppercase tracking-widest mb-6 flex items-center italic">
                                <Truck class="w-4 h-4 mr-2 text-[#FF6600]" /> Logistics Destination
                            </h3>
                            <div class="space-y-4 text-xs font-bold text-slate-600">
                                <div class="border-b border-slate-50 pb-3">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Customer Name</p>
                                    <p class="text-slate-900 uppercase font-black tracking-tight">{{ order.customer_name }}</p>
                                </div>
                                <div class="border-b border-slate-50 pb-3">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Phone Number</p>
                                    <p class="text-[#003366]">{{ order.customer_phone }}</p>
                                </div>
                                <div v-if="order.customer_email" class="border-b border-slate-50 pb-3">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Email Protocol</p>
                                    <p>{{ order.customer_email }}</p>
                                </div>
                                <div class="border-b border-slate-50 pb-3">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Shipping Destination</p>
                                    <p class="text-slate-900 leading-relaxed uppercase tracking-tight italic">
                                        {{ order.village }}, {{ order.upazila }}, {{ order.district }}
                                    </p>
                                    <p class="text-[10px] text-slate-500 mt-1 leading-relaxed">{{ order.shipping_address }}</p>
                                </div>
                                <div v-if="order.customer_remarks" class="pb-2">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Remarks</p>
                                    <p class="text-slate-500 font-medium italic">"{{ order.customer_remarks }}"</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { 
    ArrowLeft, 
    Activity, 
    MessageSquare, 
    ShoppingBag, 
    Truck, 
    Send,
    Clock,
    Settings,
    Package,
    CheckCircle2
} from 'lucide-vue-next';

const props = defineProps({
    order: Object
});

const steps = [
    { status: 'pending', label: 'Pending', icon: Clock },
    { status: 'processing', label: 'Processing', icon: Settings },
    { status: 'shipped', label: 'Shipped', icon: Package },
    { status: 'completed', label: 'Completed', icon: CheckCircle2 }
];

const timelineProgressPercent = computed(() => {
    const statusMap = {
        'pending': 0,
        'processing': 33,
        'shipped': 66,
        'completed': 100,
        'cancelled': 100
    };
    return statusMap[props.order.status] ?? 0;
});

const isStepActive = (stepStatus) => {
    const orderStatus = props.order.status;
    if (orderStatus === 'cancelled') return false;
    
    const statuses = ['pending', 'processing', 'shipped', 'completed'];
    return statuses.indexOf(stepStatus) <= statuses.indexOf(orderStatus);
};

const messagesList = ref(props.order.messages || []);
const newMessage = ref('');
const sending = ref(false);
const messageContainer = ref(null);
let pollingInterval = null;

const formatTime = (dateStr) => {
    return new Date(dateStr).toLocaleTimeString(undefined, { 
        hour: 'numeric', 
        minute: '2-digit',
        hour12: true 
    });
};

const scrollToBottom = () => {
    nextTick(() => {
        if (messageContainer.value) {
            messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
        }
    });
};

const fetchMessages = async () => {
    try {
        const response = await axios.get(`/api/customer/orders/${props.order.id}/messages`);
        const isNewMessage = response.data.length > messagesList.value.length;
        messagesList.value = response.data;
        if (isNewMessage) {
            scrollToBottom();
        }
    } catch (error) {
        console.error("Error checking communications protocol", error);
    }
};

const sendMessage = async () => {
    if (!newMessage.value.trim() || sending.value) return;

    sending.value = true;
    const msgText = newMessage.value;
    newMessage.value = '';

    try {
        await axios.post(`/customer/orders/${props.order.id}/messages`, {
            message: msgText
        });
        
        // Fetch immediately after sending
        await fetchMessages();
        scrollToBottom();
    } catch (error) {
        console.error("Failed to transmit message protocol", error);
    } finally {
        sending.value = false;
    }
};

onMounted(() => {
    scrollToBottom();
    
    // Set up polling for messages every 5 seconds
    pollingInterval = setInterval(fetchMessages, 5000);
});

onBeforeUnmount(() => {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent; 
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #e5e7eb; 
  border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #d1d5db; 
}
</style>
