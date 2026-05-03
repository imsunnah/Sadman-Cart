import { ref, computed } from 'vue';
import axios from 'axios';

const cartData = ref({ items: [] });
const isLoading = ref(false);

// Initialize cart from server
const fetchCart = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get('/api/cart');
        cartData.value = response.data;
    } catch (error) {
        console.error('Error fetching cart:', error);
    } finally {
        isLoading.value = false;
    }
};

// Initial fetch
fetchCart();

export function useCart() {
    const addToCart = async (product, quantity = 1) => {
        try {
            isLoading.value = true;
            const response = await axios.post('/api/cart/add', {
                product_id: product.id,
                quantity: quantity
            });
            cartData.value = response.data.cart;
        } catch (error) {
            console.error('Error adding to cart:', error);
        } finally {
            isLoading.value = false;
        }
    };

    const removeFromCart = async (itemId) => {
        try {
            isLoading.value = true;
            const response = await axios.delete(`/api/cart/remove/${itemId}`);
            cartData.value = response.data.cart;
        } catch (error) {
            console.error('Error removing from cart:', error);
        } finally {
            isLoading.value = false;
        }
    };

    const updateQuantity = async (itemId, quantity) => {
        if (quantity < 1) return;
        try {
            isLoading.value = true;
            const response = await axios.put(`/api/cart/update/${itemId}`, {
                quantity: quantity
            });
            cartData.value = response.data.cart;
        } catch (error) {
            console.error('Error updating cart:', error);
        } finally {
            isLoading.value = false;
        }
    };

    const clearCart = () => {
        cartData.value = { items: [] };
    };

    const cart = computed(() => cartData.value.items || []);

    const cartTotal = computed(() => {
        return cart.value.reduce((total, item) => {
            const price = item.product ? parseFloat(item.product.price) : 0;
            return total + (price * item.quantity);
        }, 0);
    });

    const cartCount = computed(() => {
        return cart.value.reduce((count, item) => count + item.quantity, 0);
    });

    return {
        cart,
        addToCart,
        removeFromCart,
        updateQuantity,
        clearCart,
        cartTotal,
        cartCount,
        isLoading,
        fetchCart
    };
}
