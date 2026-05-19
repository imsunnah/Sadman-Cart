<template>
    <AdminLayout>
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase">Support <span class="text-[#003366]">Inbox</span></h1>
            <p class="text-sm font-bold text-slate-500 mt-1">Communicate with registered customers in real-time</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex h-[650px]">
            <!-- Left Pane: Customer Threads List -->
            <div class="w-80 border-r border-slate-200 flex flex-col h-full bg-slate-50/50">
                <div class="p-4 border-b border-slate-200 bg-white">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Active Channels</span>
                </div>
                <div class="flex-1 overflow-y-auto divide-y divide-slate-100 custom-scrollbar">
                    <div v-if="threads.length === 0" class="p-8 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">
                        No conversations yet
                    </div>
                    <button 
                        v-else
                        v-for="thread in threads" 
                        :key="thread.id"
                        @click="selectThread(thread)"
                        class="w-full text-left p-4 hover:bg-slate-50 transition-colors flex flex-col gap-1.5 focus:outline-none"
                        :class="activeUser?.id === thread.id ? 'bg-[#003366]/5 border-l-4 border-[#003366]' : 'border-l-4 border-transparent'"
                    >
                        <div class="flex justify-between items-center w-full">
                            <span class="text-xs font-black text-slate-900 uppercase tracking-tight truncate max-w-[150px]">{{ thread.name }}</span>
                            <span class="text-[8px] font-bold text-slate-400">{{ formatThreadTime(thread.latest_message_time) }}</span>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 lowercase truncate w-full">{{ thread.email }}</span>
                        <p class="text-[10px] font-bold text-slate-500 truncate w-full mt-1">{{ thread.latest_message }}</p>
                    </button>
                </div>
            </div>

            <!-- Right Pane: Active Thread Chat View -->
            <div class="flex-1 flex flex-col h-full bg-white">
                <!-- If no user is selected -->
                <div v-if="!activeUser" class="flex-1 flex flex-col items-center justify-center text-slate-400 p-8">
                    <MessageSquare class="w-16 h-16 text-slate-200 mb-4" />
                    <p class="text-xs font-black uppercase tracking-widest">Select a channel to start dispatching updates</p>
                </div>

                <!-- Active Conversation -->
                <template v-else>
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-black text-[#003366] uppercase tracking-wider">{{ activeUser.name }}</span>
                            <span class="text-[9px] font-bold text-slate-400 lowercase block">{{ activeUser.email }}</span>
                        </div>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Customer Online</span>
                        </span>
                    </div>

                    <!-- Messages Container -->
                    <div 
                        ref="messageContainer"
                        class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/20 custom-scrollbar"
                    >
                        <div 
                            v-for="msg in messages" 
                            :key="msg.id"
                            class="flex animate-fade-in"
                            :class="msg.is_from_admin ? 'justify-end' : 'justify-start'"
                        >
                            <div 
                                class="max-w-[70%] rounded-xl p-3 border shadow-sm"
                                :class="msg.is_from_admin 
                                    ? 'bg-[#003366] text-white border-[#003366] rounded-tr-none' 
                                    : 'bg-white text-slate-800 border-slate-100 rounded-tl-none'"
                            >
                                <div class="flex items-center justify-between gap-6 mb-1">
                                    <span class="text-[8px] font-black uppercase tracking-widest" :class="msg.is_from_admin ? 'text-orange-200' : 'text-[#FF6600]'">
                                        {{ msg.is_from_admin ? 'You (Support)' : activeUser.name }}
                                    </span>
                                    <span class="text-[7px] font-bold opacity-60">
                                        {{ formatTime(msg.created_at) }}
                                    </span>
                                </div>
                                <p class="text-xs font-bold whitespace-pre-wrap leading-relaxed">{{ msg.message }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Input -->
                    <div class="p-4 border-t border-slate-200 bg-white">
                        <form @submit.prevent="sendMessage" class="flex gap-3 items-end">
                            <textarea 
                                v-model="newMessage"
                                placeholder="Type your response transmission..."
                                rows="2"
                                class="flex-1 rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs font-bold outline-none focus:bg-white focus:border-[#003366]/20 transition-all resize-none shadow-inner"
                                @keydown.enter.prevent="sendMessage"
                            ></textarea>
                            <button 
                                type="submit"
                                :disabled="sending"
                                class="w-12 h-12 rounded-xl bg-[#003366] text-white flex items-center justify-center hover:bg-[#FF6600] active:scale-95 transition-all shadow-md disabled:opacity-50 flex-shrink-0"
                            >
                                <Send class="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                </template>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { MessageSquare, Send } from 'lucide-vue-next';
import axios from 'axios';

const threads = ref([]);
const activeUser = ref(null);
const messages = ref([]);
const newMessage = ref('');
const sending = ref(false);
const messageContainer = ref(null);
let threadsPolling = null;
let messagesPolling = null;

const formatThreadTime = (timeStr) => {
    if (!timeStr) return '';
    const date = new Date(timeStr);
    return date.toLocaleDateString(undefined, { day: 'numeric', month: 'short' });
};

const formatTime = (timeStr) => {
    return new Date(timeStr).toLocaleTimeString(undefined, { 
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

const selectThread = async (thread) => {
    activeUser.value = thread;
    messages.value = [];
    
    // Stop any existing messages polling
    if (messagesPolling) clearInterval(messagesPolling);
    
    await fetchMessages();
    scrollToBottom();
    
    // Start polling messages for the selected user
    messagesPolling = setInterval(fetchMessages, 5000);
};

const fetchThreads = async () => {
    try {
        const response = await axios.get('/admin/api/chat/threads');
        threads.value = response.data;
    } catch (error) {
        console.error("Error loading chat channels", error);
    }
};

const fetchMessages = async () => {
    if (!activeUser.value) return;
    try {
        const response = await axios.get(`/admin/api/chat/threads/${activeUser.value.id}/messages`);
        const isNewMessage = response.data.length > messages.value.length;
        messages.value = response.data;
        if (isNewMessage) {
            scrollToBottom();
        }
    } catch (error) {
        console.error("Error loading messages for channel", error);
    }
};

const sendMessage = async () => {
    if (!newMessage.value.trim() || sending.value || !activeUser.value) return;
    
    sending.value = true;
    const msgText = newMessage.value;
    newMessage.value = '';
    
    try {
        await axios.post(`/admin/api/chat/threads/${activeUser.value.id}/messages`, {
            message: msgText
        });
        
        await fetchMessages();
        scrollToBottom();
        
        // Refresh threads to update latest message status
        fetchThreads();
    } catch (error) {
        console.error("Error sending response", error);
    } finally {
        sending.value = false;
    }
};

onMounted(() => {
    fetchThreads();
    threadsPolling = setInterval(fetchThreads, 5000);
});

onBeforeUnmount(() => {
    if (threadsPolling) clearInterval(threadsPolling);
    if (messagesPolling) clearInterval(messagesPolling);
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
