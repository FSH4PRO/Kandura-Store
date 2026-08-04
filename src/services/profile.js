// src/services/profile.js
import { apiFetch } from "./api";

export async function getProfile() {
  if (import.meta.env.VITE_USE_MOCKS === "true") {
    const name = sessionStorage.getItem("kandura_user_name") || "Demo User";
    return { id: 999, name, phone: "+971000000000" };
  }
  const response = await apiFetch("/user/profile");
  return response;
}

export async function updateProfile(profileData, imageFile = null) {
  let options = { method: "PUT" };

  // If there's an image, we MUST send as multipart/form-data
  if (imageFile) {
    const formData = new FormData();
    if (profileData.nameEn) formData.append("name[en]", profileData.nameEn);
    if (profileData.nameAr) formData.append("name[ar]", profileData.nameAr);
    if (profileData.phone) formData.append("phone", profileData.phone);
    if (profileData.password) {
      formData.append("password", profileData.password);
      formData.append(
        "password_confirmation",
        profileData.password_confirmation,
      );
    }
    formData.append("profile_image", imageFile);

    options.body = formData;
  } else {
    // Otherwise, standard JSON is fine
    options.body = JSON.stringify({
      name: { en: profileData.nameEn, ar: profileData.nameAr },
      phone: profileData.phone,
      password: profileData.password,
      password_confirmation: profileData.password_confirmation,
    });
  }

  const response = await apiFetch("/user/profile", options);
  return response;
}
