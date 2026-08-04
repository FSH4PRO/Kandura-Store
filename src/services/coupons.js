import { apiFetch } from './api';

export async function applyCoupon(orderId, code) {
    const response = await apiFetch(`/orders/${orderId}/coupon`, {
        method: 'POST',
        body: JSON.stringify({ code }),
    });
    return response.data;
}

export async function removeCoupon(orderId) {
    const response = await apiFetch(`/orders/${orderId}/coupon`, {
        method: 'DELETE',
    });
    return response.data;
}