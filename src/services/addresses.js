// src/services/addresses.js
import { apiFetch } from "./api";

// TEMPORARY WORKAROUND: Hardcoded cities since GET /api/cities is missing
export const FALLBACK_CITIES = [
  { id: 1, name: "Dubai" },
  { id: 2, name: "Abu Dhabi" },
  { id: 3, name: "Sharjah" },
  { id: 4, name: "Riyadh" },
];

export async function getAddresses(page = 1) {
  const response = await apiFetch(`/addresses?page=${page}`);
  // Note: apiFetch returns the envelope's `data` payload — which for this endpoint is a paginator object
  return response;
}

export async function createAddress(addressData) {
  // addressData needs: city_id, street, latitude, longitude, details
  const response = await apiFetch("/addresses", {
    method: "POST",
    body: JSON.stringify(addressData),
  });
  return response;
}

export async function deleteAddress(addressId) {
  const response = await apiFetch(`/addresses/${addressId}`, {
    method: "DELETE",
  });
  return response;
}
