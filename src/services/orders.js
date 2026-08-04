import { apiFetch } from './api';

export async function getOrders(page = 1) {
    const response = await apiFetch(`/orders?page=${page}`);
    return response.data; // Paginated envelope: data.data is the array
}

export async function getOrderById(id) {
    const response = await apiFetch(`/orders/${id}`);
    return response.data;
}

export async function createOrder(orderData) {
    // Expected payload: { design_id, size_id, address_id, quantity }
    const response = await apiFetch('/orders', {
        method: 'POST',
        body: JSON.stringify(orderData),
    });
    return response.data;
}

export async function cancelOrder(id) {
    const response = await apiFetch(`/orders/${id}/cancel`, {
        method: 'POST',
    });
    return response.data;
}