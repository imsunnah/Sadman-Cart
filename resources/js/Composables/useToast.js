import { ref } from 'vue';

const toasts = ref([]);
let nextId = 0;

export function useToast() {
    const addToast = (message, type = 'success', duration = 3000) => {
        const id = ++nextId;
        toasts.value.push({ id, message, type });
        setTimeout(() => removeToast(id), duration);
    };

    const removeToast = (id) => {
        const index = toasts.value.findIndex(t => t.id === id);
        if (index !== -1) toasts.value.splice(index, 1);
    };

    const success = (message, duration = 3000) => addToast(message, 'success', duration);
    const error   = (message, duration = 4000) => addToast(message, 'error', duration);
    const info    = (message, duration = 3000) => addToast(message, 'info', duration);

    return { toasts, addToast, removeToast, success, error, info };
}
