<template>
    <StoreLayout>
        <div class="bg-[#F8F9FA] min-h-screen py-10 sm:py-16 px-4 font-sans">
            <div class="max-w-[1000px] mx-auto">
                <!-- Back Link & Header -->
                <div class="mb-8">
                    <Link 
                        href="/customer/orders" 
                        class="inline-flex items-center text-[10px] font-black text-[#003366] hover:text-[#FF6600] uppercase tracking-widest transition-colors"
                    >
                        <ArrowLeft class="w-4 h-4 mr-2" /> Back to Dashboard
                    </Link>
                </div>

                <div class="bg-white rounded-[2rem] shadow-[0_15px_50px_-15px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden grid grid-cols-1 md:grid-cols-3 min-h-[600px]">
                    <!-- Left Sidebar Info (Contact Details) -->
                    <div class="p-8 bg-gradient-to-b from-[#003366] to-[#002244] text-white flex flex-col justify-between">
                        <div>
                            <span class="inline-block text-[9px] font-black uppercase tracking-widest text-[#FF6600] bg-orange-500/10 px-3 py-1 rounded-full mb-6">Support Portal</span>
                            <h2 class="text-2xl font-black italic tracking-tighter uppercase mb-2">Live Support Chat</h2>
                            <p class="text-xs font-bold text-slate-300 leading-relaxed uppercase tracking-wider mb-8">Chat with our dedicated agents in real-time for order assistance, specifications, or queries.</p>
                            
                            <div class="space-y-6 pt-6 border-t border-white/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-[#FF6600]">
                                        <Phone class="w-4 h-4" />
                                    </div>
                                    <div>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Call Helpline</p>
                                        <p class="text-xs font-bold">{{ $page.props.settings.footer_phone }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-[#FF6600]">
                                        <Mail class="w-4 h-4" />
                                    </div>
                                    <div>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Email Support</p>
                                        <p class="text-xs font-bold lowercase">{{ $page.props.settings.footer_email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-white/10 text-[9px] font-black uppercase tracking-widest text-slate-400 italic">
                            Average reply time: &lt; 5 mins
                        </div>
                    </div>

                    <!-- Right Message Panel -->
                    <div class="md:col-span-2 flex flex-col h-[600px] bg-white">
                        <!-- Top Header -->
                        <div class="px-8 py-5 border-b border-slate-50 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></div>
                                <span class="text-xs font-black text-[#003366] uppercase tracking-wider">Support Agent Connected</span>
                            </div>
                        </div>

                        <!-- Messages Area -->
                        <div 
                            ref="messageContainer"
                            class="flex-1 overflow-y-auto p-8 space-y-4 custom-scrollbar bg-slate-50/30"
                        >
                            <div v-if="messagesList.length === 0" class="text-center py-20 text-slate-400 font-bold text-xs uppercase tracking-widest">
                                <MessageSquare class="w-12 h-12 text-slate-200 mx-auto mb-4" />
                                Send a message below to start your support transmission.
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
                                        ? 'bg-white text-slate-800 border-slate-100 rounded-tl-none' 
                                        : 'bg-[#003366] text-white border-[#003366] rounded-tr-none'"
                                >
                                    <div class="flex items-center justify-between gap-4 mb-1">
                                        <span class="text-[8px] font-black uppercase tracking-widest" :class="msg.is_from_admin ? 'text-[#FF6600]' : 'text-orange-200'">
                                            {{ msg.is_from_admin ? 'Admin Agent' : 'You' }}
                                        </span>
                                        <span class="text-[7px] font-bold opacity-60">
                                            {{ formatTime(msg.created_at) }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-bold whitespace-pre-wrap leading-relaxed">{{ msg.message }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Input Box -->
                        <div class="p-6 border-t border-slate-50 bg-white">
                            <form @submit.prevent="sendMessage" class="flex gap-3 items-end">
                                <textarea 
                                    v-model="newMessage"
                                    placeholder="Type your support request..."
                                    rows="2"
                                    class="flex-1 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none focus:bg-white focus:border-[#003366]/20 transition-all resize-none shadow-inner"
                                    @keydown.enter.prevent="sendMessage"
                                ></textarea>
                                <button 
                                    type="submit"
                                    :disabled="sending"
                                    class="w-14 h-14 rounded-2xl bg-[#003366] text-white flex items-center justify-center hover:bg-[#FF6600] active:scale-95 transition-all shadow-md shadow-blue-900/10 hover:shadow-orange-500/20 disabled:opacity-50 flex-shrink-0"
                                >
                                    <Send class="w-5 h-5" />
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
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import StoreLayout from '@/Layouts/StoreLayout.vue';
import { ArrowLeft, Send, Phone, Mail, MessageSquare } from 'lucide-vue-next';

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
