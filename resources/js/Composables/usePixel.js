/**
 * Composable for Meta Pixel (Facebook Pixel) tracking
 */

export function usePixel() {
    /**
     * Track a standard Meta Pixel event
     * @param {string} eventName 
     * @param {Object} params 
     */
    const trackEvent = (eventName, params = {}) => {
        if (typeof window.fbq === 'function') {
            window.fbq('track', eventName, params);
        } else {
            console.warn(`Meta Pixel fbq not found. Could not track event: ${eventName}`, params);
        }
    };

    /**
     * Standard AddToCart event
     * @param {Object} item - Product or Combo object
     * @param {number} quantity 
     */
    const trackAddToCart = (item, quantity = 1) => {
        trackEvent('AddToCart', {
            content_name: item.name,
            content_ids: [item.id],
            content_type: 'product',
            value: parseFloat(item.price) * quantity,
            currency: 'BDT'
        });
    };

    /**
     * Standard InitiateCheckout event
     * @param {Array} items - List of cart items
     * @param {number} totalValue 
     */
    const trackInitiateCheckout = (items, totalValue) => {
        trackEvent('InitiateCheckout', {
            content_ids: items.map(i => i.product_id || i.combo_id),
            content_type: 'product',
            value: totalValue,
            currency: 'BDT',
            num_items: items.reduce((sum, i) => sum + i.quantity, 0)
        });
    };

    /**
     * Standard Purchase event
     * @param {Object} order - The created order object
     */
    const trackPurchase = (order) => {
        trackEvent('Purchase', {
            content_ids: order.items?.map(i => i.product_id || i.combo_id) || [],
            content_type: 'product',
            value: parseFloat(order.total_amount),
            currency: 'BDT',
            transaction_id: order.id
        });
    };

    return {
        trackEvent,
        trackAddToCart,
        trackInitiateCheckout,
        trackPurchase
    };
}
