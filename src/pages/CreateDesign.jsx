// src/pages/CreateDesign.jsx
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { createDesign, FALLBACK_SIZES, FALLBACK_OPTIONS } from '../services/designs';
import { Upload, X, Check, Image as ImageIcon } from 'lucide-react';

export default function CreateDesign() {
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  // Form State
  const [nameEn, setNameEn] = useState('');
  const [nameAr, setNameAr] = useState('');
  const [descriptionEn, setDescriptionEn] = useState('');
  const [price, setPrice] = useState('350');
  const [selectedSizes, setSelectedSizes] = useState([2, 3]); // Defaults: S, M
  const [optionValues, setOptionValues] = useState({});
  const [imageFiles, setImageFiles] = useState([]);
  const [imagePreviews, setImagePreviews] = useState([]);

  // Size Checkbox Toggle
  const handleSizeToggle = (sizeId) => {
    setSelectedSizes((prev) =>
      prev.includes(sizeId) ? prev.filter((id) => id !== sizeId) : [...prev, sizeId]
    );
  };

  // Option Value Handler
  const handleOptionChange = (optId, value) => {
    setOptionValues((prev) => ({ ...prev, [optId]: value }));
  };

  // File Selector Handler
  const handleFileChange = (e) => {
    const files = Array.from(e.target.files);
    if (files.length === 0) return;

    setImageFiles((prev) => [...prev, ...files]);
    
    // Generate object URLs for immediate UI preview
    const newPreviews = files.map((file) => URL.createObjectURL(file));
    setImagePreviews((prev) => [...prev, ...newPreviews]);
  };

  // Remove Selected Image
  const removeImage = (index) => {
    setImageFiles((prev) => prev.filter((_, i) => i !== index));
    setImagePreviews((prev) => prev.filter((_, i) => i !== index));
  };

  // Form Submission
  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);

    if (selectedSizes.length === 0) {
      setError('Please select at least one size option.');
      return;
    }

    if (imageFiles.length === 0) {
      setError('Please upload at least one image of your design.');
      return;
    }

    setLoading(true);

    try {
      // Map option values to the payload shape
      const formattedOptions = Object.entries(optionValues)
        .filter(([_, val]) => val.trim() !== '')
        .map(([id, val]) => ({ id: parseInt(id), valueEn: val }));

      await createDesign(
        {
          nameEn,
          nameAr,
          descriptionEn,
          price: parseFloat(price),
          sizeIds: selectedSizes,
          options: formattedOptions,
        },
        imageFiles
      );

      navigate('/designs');
    } catch (err) {
      if (err.type === 'validation') {
        const errorMsgs = Object.values(err.errors).flat().join(' | ');
        setError(errorMsgs);
      } else {
        setError(err.message || 'Failed to save design. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-4xl mx-auto px-4 py-8">
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-slate-900 font-serif">Kandura Design Studio</h1>
        <p className="text-slate-600 mt-1">Configure your custom robe specifications and visuals.</p>
      </div>

      {error && (
        <div className="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-8">
        {/* Section 1: Basic Information */}
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
          <h2 className="text-lg font-semibold text-slate-800 border-b border-slate-100 pb-3">
            1. Basic Information
          </h2>
          
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-slate-700 mb-1">Design Name (English) *</label>
              <input
                type="text"
                required
                placeholder="e.g. Royal Emirati White Kandura"
                value={nameEn}
                onChange={(e) => setNameEn(e.target.value)}
                className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-slate-700 mb-1">Design Name (Arabic)</label>
              <input
                type="text"
                dir="rtl"
                placeholder="قندورة بيضاء كلاسيكية"
                value={nameAr}
                onChange={(e) => setNameAr(e.target.value)}
                className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none"
              />
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div className="md:col-span-2">
              <label className="block text-sm font-medium text-slate-700 mb-1">Description</label>
              <input
                type="text"
                placeholder="Brief details regarding fit, occasion, or lining..."
                value={descriptionEn}
                onChange={(e) => setDescriptionEn(e.target.value)}
                className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-slate-700 mb-1">Estimated Price (AED) *</label>
              <input
                type="number"
                required
                min="0"
                step="0.01"
                value={price}
                onChange={(e) => setPrice(e.target.value)}
                className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none font-semibold"
              />
            </div>
          </div>
        </div>

        {/* Section 2: Compatible Sizes */}
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
          <h2 className="text-lg font-semibold text-slate-800 border-b border-slate-100 pb-3">
            2. Target Sizing Options *
          </h2>
          <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
            {FALLBACK_SIZES.map((size) => {
              const isSelected = selectedSizes.includes(size.id);
              return (
                <button
                  type="button"
                  key={size.id}
                  onClick={() => handleSizeToggle(size.id)}
                  className={`p-3 rounded-xl border text-center transition-all flex flex-col items-center justify-center ${
                    isSelected
                      ? 'border-amber-500 bg-amber-50/50 text-amber-900 font-medium shadow-sm'
                      : 'border-slate-200 hover:border-slate-300 text-slate-600'
                  }`}
                >
                  <span className="text-lg font-bold">{size.code}</span>
                  <span className="text-xs text-slate-400">{size.name.en}</span>
                </button>
              );
            })}
          </div>
        </div>

        {/* Section 3: Custom Garment Specifications */}
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
          <h2 className="text-lg font-semibold text-slate-800 border-b border-slate-100 pb-3">
            3. Garment Customizations
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {FALLBACK_OPTIONS.map((option) => (
              <div key={option.id}>
                <label className="block text-sm font-medium text-slate-700 mb-1">
                  {option.name.en}
                </label>
                <input
                  type="text"
                  placeholder={`e.g. ${
                    option.type === 'color' ? 'Ivory White / #FFFFF0' : 'Japanese Cotton'
                  }`}
                  value={optionValues[option.id] || ''}
                  onChange={(e) => handleOptionChange(option.id, e.target.value)}
                  className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none"
                />
              </div>
            ))}
          </div>
        </div>

        {/* Section 4: Image Uploads */}
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
          <h2 className="text-lg font-semibold text-slate-800 border-b border-slate-100 pb-3">
            4. Photos & Reference Imagery *
          </h2>
          
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
            {imagePreviews.map((preview, index) => (
              <div key={index} className="relative aspect-square rounded-xl overflow-hidden border border-slate-200 group">
                <img src={preview} alt="Upload Preview" className="w-full h-full object-cover" />
                <button
                  type="button"
                  onClick={() => removeImage(index)}
                  className="absolute top-2 right-2 bg-slate-900/70 hover:bg-red-600 text-white p-1 rounded-full transition-colors"
                >
                  <X className="w-4 h-4" />
                </button>
              </div>
            ))}

            <label className="aspect-square rounded-xl border-2 border-dashed border-slate-200 hover:border-amber-500 flex flex-col items-center justify-center cursor-pointer transition-colors bg-slate-50 hover:bg-amber-50/30">
              <Upload className="w-6 h-6 text-slate-400 mb-1" />
              <span className="text-xs font-medium text-slate-600">Upload Image</span>
              <span className="text-[10px] text-slate-400 mt-0.5">JPG, PNG, WEBP (Max 5MB)</span>
              <input
                type="file"
                multiple
                accept="image/jpeg,image/png,image/webp"
                onChange={handleFileChange}
                className="hidden"
              />
            </label>
          </div>
        </div>

        {/* Actions */}
        <div className="flex justify-end gap-3 pt-4">
          <button
            type="button"
            onClick={() => navigate('/designs')}
            className="px-6 py-3 rounded-xl border border-slate-200 font-medium text-slate-600 hover:bg-slate-50 transition-colors"
          >
            Cancel
          </button>
          <button
            type="submit"
            disabled={loading}
            className="px-8 py-3 rounded-xl bg-slate-900 hover:bg-amber-600 text-white font-medium transition-colors shadow-sm disabled:opacity-50"
          >
            {loading ? 'Creating Design...' : 'Save & Build Design'}
          </button>
        </div>
      </form>
    </div>
  );
}