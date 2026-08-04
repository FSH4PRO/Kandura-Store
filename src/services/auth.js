// src/services/auth.js
import { apiFetch, setToken, removeToken } from "./api";

/**
 * Register new customer
 * @param {Object} payload - { name: { en, ar }, phone, password, password_confirmation }
 */
export async function registerCustomer(payload) {
  const response = await apiFetch("/auth/register", {
    method: "POST",
    body: JSON.stringify(payload),
  });

  if (response?.access_token) {
    setToken(response.access_token);
    if (response.name) {
      sessionStorage.setItem("kandura_user_name", response.name);
    }
  }
  return response;
}

/**
 * Login customer (Throttled 5 req/min)
 */
export async function loginCustomer(phone, password) {
  const response = await apiFetch("/auth/login", {
    method: "POST",
    body: JSON.stringify({ phone, password }),
  });

  if (response?.access_token) {
    setToken(response.access_token);
    if (response.name) {
      sessionStorage.setItem("kandura_user_name", response.name);
    }
  }
  return response;
}

/**
 * Logout customer & clear tokens
 */
export async function logoutCustomer() {
  try {
    await apiFetch("/auth/logout", { method: "POST" });
  } finally {
    sessionStorage.removeItem("kandura_user_name");
    removeToken();
  }
}

/**
 * Get profile details
 */
export async function getProfile() {
  const response = await apiFetch("/user/profile");
  return response;
}
