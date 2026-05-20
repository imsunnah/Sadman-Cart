<template>
    <StoreLayout>
        <div class="bg-[#F8F9FA] min-h-screen py-10 sm:py-16 font-sans">
            <div class="max-w-[1550px] mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Dashboard Welcome Banner -->
                <div class="bg-gradient-to-r from-[#003366] to-[#002244] rounded-[2rem] p-8 sm:p-12 text-white shadow-xl mb-12 relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 top-0 w-1/3 opacity-10 bg-[radial-gradient(circle_at_bottom_right,white,transparent)] pointer-events-none"></div>
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <span class="text-[9px] font-black text-[#FF6600] uppercase tracking-[0.4em] mb-2 block">{{ $t('Customer Dashboard') }}</span>
                            <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-tight italic">{{ $t('Welcome back,') }} <span class="text-orange-400">{{ $page.props.auth.user.name }}</span></h1>
                            <p class="text-xs font-bold text-slate-300 mt-2 lowercase">{{ $page.props.auth.user.email }}</p>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl px-5 py-4 border border-white/10 text-center min-w-[100px]">
                                <span class="block text-2xl font-black text-[#FF6600]">{{ orders.data ? orders.total : 0 }}</span>
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-wider">{{ $t('Total Orders') }}</span>
                            </div>
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl px-5 py-4 border border-white/10 text-center min-w-[100px]">
                                <span class="block text-2xl font-black text-[#FF6600]">{{ reviews.length }}</span>
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-wider">{{ $t('Reviews Given') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Three-way Consolidated Layout Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <!-- Left & Middle: Orders & Reviews Area (Col-span 2) -->
                    <div class="lg:col-span-2 space-y-8">
                        
                        <!-- Dashboard Tabs -->
                        <div class="flex border-b border-slate-200">
                            <button 
                                @click="dashboardTab = 'activity'" 
                                class="py-3 px-6 text-xs font-black uppercase tracking-wider border-b-2 outline-none transition-colors"
                                :class="dashboardTab === 'activity' ? 'border-[#FF6600] text-[#003366]' : 'border-transparent text-slate-400 hover:text-slate-600'"
                            >
                                {{ $t('Orders & Reviews') }}
                            </button>
                            <button 
                                @click="dashboardTab = 'password'" 
                                class="py-3 px-6 text-xs font-black uppercase tracking-wider border-b-2 outline-none transition-colors"
                                :class="dashboardTab === 'password' ? 'border-[#FF6600] text-[#003366]' : 'border-transparent text-slate-400 hover:text-slate-600'"
                            >
                                {{ $t('Change Password') }}
                            </button>
                        </div>

                        <div v-if="dashboardTab === 'activity'" class="space-y-8">
                            <!-- Section: Orders History & Tracing status -->
                            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6 sm:p-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h2 class="text-lg font-black text-[#003366] uppercase tracking-wider flex items-center gap-2">
                                        <ShoppingBag class="w-5 h-5 text-[#FF6600]" />
                                        {{ $t('Active Orders & History') }}
                                    </h2>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                        {{ $t('Latest items shown first') }}
                                    </span>
                                </div>

                                <div v-if="orders.data && orders.data.length > 0" class="space-y-4">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left border-collapse">
                                            <thead>
                                                <tr class="border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                                    <th class="py-3 px-4">{{ $t('Order ID') }}</th>
                                                    <th class="py-3 px-4">{{ $t('Date') }}</th>
                                                    <th class="py-3 px-4">{{ $t('Total Amount') }}</th>
                                                    <th class="py-3 px-4">{{ $t('Status') }}</th>
                                                    <th class="py-3 px-4 text-right">{{ $t('Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-50">
                                                <tr v-for="order in orders.data" :key="order.id" class="text-xs font-bold text-slate-600 hover:bg-slate-50/50 transition-colors">
                                                    <td class="py-4 px-4">
                                                        <span class="text-[#003366] font-black">#{{ String(order.id).padStart(6, '0') }}</span>
                                                    </td>
                                                    <td class="py-4 px-4 text-slate-500">
                                                        {{ new Date(order.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) }}
                                                    </td>
                                                    <td class="py-4 px-4 text-slate-900 font-black">
                                                        ৳ {{ parseFloat(order.total_amount).toLocaleString() }}
                                                    </td>
                                                    <td class="py-4 px-4">
                                                        <span 
                                                            class="inline-block px-2.5 py-0.5 text-[8px] font-black uppercase tracking-wider rounded-full"
                                                            :class="{
                                                                'bg-amber-50 text-amber-700 border border-amber-100': order.status === 'pending',
                                                                'bg-blue-50 text-blue-700 border border-blue-100': order.status === 'processing',
                                                                'bg-emerald-50 text-emerald-700 border border-emerald-100': order.status === 'completed',
                                                                'bg-rose-50 text-rose-700 border border-rose-100': order.status === 'cancelled'
                                                            }"
                                                        >
                                                            {{ order.status }}
                                                        </span>
                                                    </td>
                                                    <td class="py-4 px-4 text-right">
                                                        <Link 
                                                            :href="`/customer/orders/${order.id}`" 
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#003366] text-white rounded-lg font-black uppercase tracking-widest text-[8px] hover:bg-[#FF6600] transition-all"
                                                        >
                                                            <Eye class="w-3 h-3" />
                                                            {{ $t('Trace Status') }}
                                                        </Link>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    <div v-if="orders.links && orders.links.length > 3" class="flex justify-center mt-6">
                                        <nav class="flex items-center gap-1">
                                            <template v-for="(link, i) in orders.links" :key="i">
                                                <div 
                                                    v-if="link.url === null" 
                                                    class="px-2.5 py-1 text-[8px] font-black text-slate-300 uppercase tracking-widest rounded border border-slate-100 cursor-not-allowed select-none"
                                                    v-html="link.label"
                                                 />
                                                <Link 
                                                    v-else 
                                                    :href="link.url" 
                                                    class="px-2.5 py-1 text-[8px] font-black uppercase tracking-widest rounded border transition-all"
                                                    :class="link.active 
                                                        ? 'bg-[#003366] text-white border-[#003366]' 
                                                        : 'bg-white text-slate-600 border-slate-100 hover:bg-slate-50'"
                                                    v-html="link.label"
                                                />
                                            </template>
                                        </nav>
                                    </div>
                                </div>

                                <!-- Empty State -->
                                <div v-else class="text-center py-10 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200 p-8">
                                    <ShoppingBag class="w-10 h-10 text-slate-300 mx-auto mb-3" />
                                    <h3 class="text-sm font-black text-[#003366] uppercase tracking-tight">{{ $t('No Orders Yet') }}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider max-w-xs mx-auto mt-1">
                                        {{ $t('You have not placed any orders yet. Visit our shop to get started!') }}
                                    </p>
                                    <div class="mt-4">
                                        <Link href="/shop" class="inline-flex items-center bg-[#FF6600] text-white px-5 py-2 rounded-xl font-black uppercase tracking-widest text-[9px] hover:bg-[#e55c00] transition-all">
                                            {{ $t('Go Shopping') }}
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Product Reviews Given -->
                            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6 sm:p-8">
                                <h2 class="text-lg font-black text-[#003366] uppercase tracking-wider flex items-center gap-2 mb-6">
                                    <Star class="w-5 h-5 text-[#FF6600]" />
                                    {{ $t('Reviews & Remarks Provided') }}
                                </h2>

                                <div v-if="reviews && reviews.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div 
                                        v-for="review in reviews" 
                                        :key="review.id"
                                        class="p-5 rounded-2xl border border-slate-100 bg-slate-50/30 flex flex-col justify-between gap-4 hover:border-slate-200 transition-colors"
                                    >
                                        <div>
                                            <div class="flex justify-between items-start gap-4">
                                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block truncate max-w-[150px]">
                                                    {{ review.product ? review.product.name : 'Unknown Product' }}
                                                </span>
                                                <!-- Stars -->
                                                <div class="flex items-center gap-0.5 flex-shrink-0">
                                                    <Star 
                                                        v-for="star in 5" 
                                                        :key="star"
                                                        class="w-3.5 h-3.5"
                                                        :class="star <= review.rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200'"
                                                    />
                                                </div>
                                            </div>
                                            <p class="text-xs font-bold text-slate-600 mt-3 italic line-clamp-3">
                                                "{{ review.comment }}"
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between text-[8px] font-black uppercase tracking-widest text-slate-400 border-t border-slate-100/80 pt-3">
                                            <span>{{ $t('Submitted as:') }} {{ review.customer_name }}</span>
                                            <span>{{ new Date(review.created_at).toLocaleDateString() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Empty State for Reviews -->
                                <div v-else class="text-center py-10 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200 p-8">
                                    <Star class="w-10 h-10 text-slate-200 mx-auto mb-3" />
                                    <h3 class="text-sm font-black text-[#003366] uppercase tracking-tight">{{ $t('No Reviews Provided') }}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider max-w-xs mx-auto mt-1">
                                        {{ $t('You have not submitted reviews for any products yet.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password Panel -->
                        <div v-if="dashboardTab === 'password'" class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6">
                            <div>
                                <h2 class="text-lg font-black text-[#003366] uppercase tracking-wider flex items-center gap-2">
                                    <Lock class="w-5 h-5 text-[#FF6600]" />
                                    {{ $t('Change Account Password') }}
                                </h2>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">
                                    {{ $t('Ensure your account remains secure by updating your access key.') }}
                                </p>
                            </div>

                            <!-- Error alerts -->
                            <div v-if="Object.keys(passwordForm.errors).length > 0" class="bg-red-50 p-4 rounded-xl text-xs font-bold border border-red-100">
                                <ul class="text-red-700 list-inside list-disc">
                                    <li v-for="(error, key) in passwordForm.errors" :key="key">{{ error }}</li>
                                </ul>
                            </div>

                            <!-- Success alert -->
                            <div v-if="$page.props.flash?.success" class="bg-emerald-50 p-4 rounded-xl text-xs font-bold border border-emerald-100 text-emerald-800">
                                {{ $page.props.flash.success }}
                            </div>

                            <form @submit.prevent="submitPasswordChange" class="space-y-5">
                                <div class="space-y-4">
                                    <div>
                                        <label for="current_password" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ $t('Current Password') }}</label>
                                        <input 
                                            id="current_password" 
                                            v-model="passwordForm.current_password" 
                                            type="password" 
                                            required
                                            class="block w-full rounded-xl bg-slate-50 border-slate-100 border px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all"
                                            placeholder="••••••••"
                                        >
                                    </div>

                                    <div>
                                        <label for="new_password" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ $t('New Password') }}</label>
                                        <input 
                                            id="new_password" 
                                            v-model="passwordForm.password" 
                                            type="password" 
                                            required
                                            class="block w-full rounded-xl bg-slate-50 border-slate-100 border px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all"
                                            placeholder="Minimum 8 characters"
                                        >
                                    </div>

                                    <div>
                                        <label for="new_password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ $t('Confirm New Password') }}</label>
                                        <input 
                                            id="new_password_confirmation" 
                                            v-model="passwordForm.password_confirmation" 
                                            type="password" 
                                            required
                                            class="block w-full rounded-xl bg-slate-50 border-slate-100 border px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-[#003366]/10 outline-none transition-all"
                                            placeholder="Re-enter new password"
                                        >
                                    </div>
                                </div>

                                <button 
                                    type="submit" 
                                    :disabled="passwordForm.processing"
                                    class="bg-[#003366] text-white hover:bg-[#FF6600] text-[10px] font-black uppercase tracking-widest px-6 py-3 rounded-xl transition-all disabled:opacity-50 active:scale-95 shadow-md shadow-[#003366]/10"
                                >
                                    {{ $t('Update Password') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column: Support Chat Widget (Col-span 1) -->
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col h-[650px] lg:sticky lg:top-8">
                        <!-- Chat Header -->
                        <div class="px-6 py-4 border-b border-slate-100 bg-[#003366] text-white flex items-center justify-between flex-shrink-0">
                            <div class="flex items-center gap-2.5">
                                <MessageSquare class="w-4.5 h-4.5 text-[#FF6600]" />
                                <div>
                                    <span class="text-xs font-black uppercase tracking-wider block">{{ $t('Live Support Portal') }}</span>
                                    <span class="text-[8px] font-bold text-slate-300 block mt-0.5">{{ $t('Average reply time: < 5 mins') }}</span>
                                </div>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        </div>

                        <!-- Chat Messages Screen -->
                        <div 
                            ref="messageContainer"
                            class="flex-1 overflow-y-auto p-5 space-y-3.5 bg-slate-50/40 custom-scrollbar"
                        >
                            <div v-if="messagesList.length === 0" class="text-center py-20 text-slate-400 font-bold text-[10px] uppercase tracking-widest px-4">
                                <MessageSquare class="w-10 h-10 text-slate-200 mx-auto mb-3" />
                                {{ $t('Send a message below to start your support transmission.') }}
                            </div>
                            <div 
                                v-else 
                                v-for="msg in messagesList" 
                                :key="msg.id"
                                class="flex"
                                :class="msg.is_from_admin ? 'justify-start' : 'justify-end'"
                            >
                                <div 
                                    class="max-w-[85%] rounded-2xl p-3 shadow-sm border"
                                    :class="msg.is_from_admin 
                                        ? 'bg-white text-slate-800 border-slate-100 rounded-tl-none' 
                                        : 'bg-[#003366] text-white border-[#003366] rounded-tr-none'"
                                >
                                    <div class="flex items-center justify-between gap-4 mb-0.5">
                                        <span class="text-[7px] font-black uppercase tracking-widest" :class="msg.is_from_admin ? 'text-[#FF6600]' : 'text-orange-200'">
                                            {{ msg.is_from_admin ? $t('Agent') : $t('You') }}
                                        </span>
                                        <span class="text-[6px] font-bold opacity-60">
                                            {{ formatTime(msg.created_at) }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-bold whitespace-pre-wrap leading-relaxed">{{ msg.message }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Input Box -->
                        <div class="p-4 border-t border-slate-100 bg-white flex-shrink-0">
                            <form @submit.prevent="sendMessage" class="flex gap-2 items-end">
                                <textarea 
                                    v-model="newMessage"
                                    :placeholder="$t('Type your support request...')"
                                    rows="2"
                                    class="flex-1 rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs font-bold outline-none focus:bg-white focus:border-[#003366]/20 transition-all resize-none shadow-inner"
                                    @keydown.enter.prevent="sendMessage"
                                ></textarea>
                                <button 
                                    type="submit"
                                    :disabled="sending"
                                    class="w-11 h-11 rounded-xl bg-[#003366] text-white flex items-center justify-center hover:bg-[#FF6600] active:scale-95 transition-all shadow-md disabled:opacity-50 flex-shrink-0"
                                >
                                    <Send class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { Eye, ShoppingBag, MessageSquare, Send, Star, Lock } from 'lucide-vue-next';

defineProps({
    orders: Object,
    reviews: Array
});

const dashboardTab = ref('activity');

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submitPasswordChange = () => {
    passwordForm.post('/change-password', {
        onSuccess: () => passwordForm.reset(),
    });
};

const messagesList = ref([]);
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
        const response = await axios.get('/api/customer/chat/messages');
        const isNewMessage = response.data.length > messagesList.value.length;
        messagesList.value = response.data;
        if (isNewMessage) {
            scrollToBottom();
        }
    } catch (error) {
        console.error("Error retrieving support messages", error);
    }
};

const sendMessage = async () => {
    if (!newMessage.value.trim() || sending.value) return;

    sending.value = true;
    const msgText = newMessage.value;
    newMessage.value = '';

    try {
        await axios.post('/api/customer/chat/messages', {
            message: msgText
        });
        
        await fetchMessages();
        scrollToBottom();
    } catch (error) {
        console.error("Error sending support message", error);
    } finally {
        sending.value = false;
    }
};

onMounted(() => {
    fetchMessages();
    scrollToBottom();
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
