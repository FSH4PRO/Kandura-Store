// src/services/api.js

const BASE_URL =
  import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api";

// Local storage token helper
export const getToken = () => localStorage.getItem("kandura_access_token");
export const setToken = (token) =>
  localStorage.setItem("kandura_access_token", token);
export const removeToken = () =>
  localStorage.removeItem("kandura_access_token");

export function parseResourceCollection(payload) {
  return Array.isArray(payload?.data) ? payload.data : [];
}

export function parseRawPaginator(payload) {
  return Array.isArray(payload?.data) ? payload.data : [];
}

export function flattenValidationErrors(errors = {}) {
  return Object.entries(errors).reduce((acc, [field, messages]) => {
    acc[field] = Array.isArray(messages) ? messages : [String(messages)];
    return acc;
  }, {});
}

function redirectToLogin() {
  if (import.meta.env.MODE === "test") {
    return;
  }

  if (typeof window !== "undefined" && window.location) {
    try {
      window.location.assign("/login");
    } catch (e) {
      // jsdom/navigation stubs may throw during tests
    }
  }
}

/**
 * Universal fetch wrapper for Kandura API
 */
export async function apiFetch(endpoint, options = {}) {
  const token = getToken();

  // Build headers (handling FormData vs JSON)
  const headers = {
    Accept: "application/json",
    ...options.headers,
  };

  if (!(options.body instanceof FormData)) {
    headers["Content-Type"] = "application/json";
  }

  if (token) {
    headers["Authorization"] = `Bearer ${token}`;
  }

  const response = await fetch(`${BASE_URL}${endpoint}`, {
    ...options,
    headers,
  });

  // Global 401 Unauthenticated handling
  if (response.status === 401) {
    removeToken();
    redirectToLogin();
    throw { type: "auth", message: "Unauthenticated", status: 401 };
  }

  // Try to parse JSON body safely
  let data = null;
  try {
    data = await response.json();
  } catch (e) {
    // no JSON
    data = null;
  }

  // Laravel FormRequest Validation Failure (422) has shape { message, errors }
  if (response.status === 422 && data?.errors) {
    throw {
      type: "validation",
      message: data.message || "Validation error",
      errors: flattenValidationErrors(data.errors),
      status: 422,
    };
  }

  // If the API uses the standard envelope { success, data, message }
  if (data && typeof data.success !== "undefined") {
    if (data.success === false) {
      throw {
        type: "api",
        message: data.message || "API error",
        code: data.code,
        status: response.status,
        payload: data,
      };
    }
    return data.data;
  }

  if (!response.ok) {
    throw {
      type: "http",
      message: data?.message || `HTTP error ${response.status}`,
      status: response.status,
      payload: data,
    };
  }

  // Fallback: return parsed JSON or raw response
  return data;
}
