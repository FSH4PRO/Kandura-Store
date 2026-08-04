// src/services/designs.js
import { apiFetch } from './api';

// Fallback Size Catalog (Bypasses missing GET /api/sizes)
export const FALLBACK_SIZES = [
    { id: 1, code: 'XS', name: { en: 'Extra Small', ar: 'صغير جداً' } },
    { id: 2, code: 'S',  name: { en: 'Small', ar: 'صغير' } },
    { id: 3, code: 'M',  name: { en: 'Medium', ar: 'متوسط' } },
    { id: 4, code: 'L',  name: { en: 'Large', ar: 'كبير' } },
    { id: 5, code: 'XL', name: { en: 'Extra Large', ar: 'كبير جداً' } },
    { id: 6, code: 'XXL', name: { en: 'Double Extra Large', ar: 'كبير جداً مضاعف' } },
];

// Fallback Design Options Catalog (Bypasses missing GET /api/design-options)
export const FALLBACK_OPTIONS = [
    { id: 1, type: 'fabric_type', name: { en: 'Fabric Material', ar: 'نوع القماش' } },
    { id: 2, type: 'color',       name: { en: 'Garment Color', ar: 'اللون' } },
    { id: 3, type: 'dome_type',   name: { en: 'Collar / Dome Style', ar: 'نوع الياقة' } },
    { id: 4, type: 'sleeve_type', name: { en: 'Sleeve Cut', ar: 'نوع الكم' } },
];

/**
 * GET /api/designs
 * @param {string} mode - 'my' (own designs) or 'browse' (community marketplace)
 * @param {number} page - Page number
 */
export async function getDesigns(mode = 'my', page = 1) {
    const response = await apiFetch(`/designs?mode=${mode}&page=${page}`);
    return response.data; // Standard paginated envelope: data.data array
}

/**
 * GET /api/designs/:id
 */
export async function getDesignById(id) {
    const response = await apiFetch(`/designs/${id}`);
    return response.data;
}

/**
 * POST /api/designs (Multipart Form Data)
 */
export async function createDesign(designData, fileList) {
    const formData = new FormData();

    // Required & Translatable Text Fields
    formData.append('name[en]', designData.nameEn);
    if (designData.nameAr) formData.append('name[ar]', designData.nameAr);
    if (designData.descriptionEn) formData.append('description[en]', designData.descriptionEn);
    if (designData.descriptionAr) formData.append('description[ar]', designData.descriptionAr);
    formData.append('price', designData.price);

    // Associated Sizes Array (size_ids[])
    designData.sizeIds.forEach((sizeId) => {
        formData.append('size_ids[]', sizeId);
    });

    // Custom Design Option Selections (design_options[][id], design_options[][value][en])
    designData.options.forEach((opt, index) => {
        if (opt.valueEn) {
            formData.append(`design_options[${index}][id]`, opt.id);
            formData.append(`design_options[${index}][value][en]`, opt.valueEn);
            if (opt.valueAr) {
                formData.append(`design_options[${index}][value][ar]`, opt.valueAr);
            }
        }
    });

    // Image Uploads Array (images[])
    Array.from(fileList).forEach((file) => {
        formData.append('images[]', file);
    });

    const response = await apiFetch('/designs', {
        method: 'POST',
        body: formData, // Automatic multipart/form-data handling in apiFetch
    });

    return response.data;
}

/**
 * DELETE /api/designs/:id
 */
export async function deleteDesign(id) {
    const response = await apiFetch(`/designs/${id}`, {
        method: 'DELETE',
    });
    return response.data;
}