// src/pages/Checkout.jsx
import { useState, useEffect } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { getDesigns } from '../services/designs';
import { getAddresses } from '../services/addresses';
import { createOrder } from '../services/orders';
import { ShoppingBag, MapPin, AlertCircle } from 'lucide-react';

export default function Checkout() {
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  const preselectedDesignId = searchParams.get('design_id');

  const [designs, setDesigns] = useState([]);
  const [addresses, setAddresses] = useState([]);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState(null);

  // Form State
  const [selectedDesignId, setSelectedDesignId] = useState(preselectedDesignId || '');
  const [selectedSizeId, setSelectedSizeId] = useState('');
  const [selectedAddressId, setSelectedAddressId] = useState('');
  const [quantity, setQuantity] = useState(1);

  useEffect(() => {
    async function fetchData() {
      try {
        const [designsRes, addressesRes] = await Promise.all([
          getDesigns('my'),
          getAddresses(1)
        ]);
        setDesigns(designsRes.data || []);
        setAddresses(addressesRes.data || []);
      } catch (err) {
        setError('Failed to load checkout dependencies.');
      } finally {
        setLoading(false);
      }
    }
    fetchData();
  }, []);

  const selectedDesign = designs.find((d) => d.id === parseInt(selectedDesignId));

  const handlePlaceOrder = async (e) => {
    e.preventDefault();
    setError(null);

    if (!selectedDesignId || !selectedSizeId || !selectedAddressId) {
      setError('Please select a design, size, and delivery address.');
      return;
    }

    // CLIENT-SIDE VALIDATION FIX: Ensure selected size belongs to the selected design
    const sizeIsValid = selectedDesign?.sizes?.some(s => s.id === parseInt(selectedSizeId));
    if (!sizeIsValid) {
      setError('The selected size is not available for this specific design.');
      return;
    }

    setSubmitting(true);
    try {
      const order = await createOrder({
        design_id: parseInt(selectedDesignId),
        size_id: parseInt(selectedSizeId),
        address_id: parseInt(selectedAddressId),
        quantity: parseInt(quantity),
      });
      // Redirect to Order Detail for Coupon/Payment
      navigate(`/orders/${order.id}`);
    } catch (err) {
      setError(err.message || 'Failed to place order');
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) return <div className="p-8 text-center text-slate-500">Loading checkout...</div>;

  return (
    <div className="max-w-3xl mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-slate-900 font-serif mb-6 flex items-center gap-3">
        <ShoppingBag className="w-8 h-8 text-amber-500" />
        Checkout
      </h1>

      {error && (
        <div className="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 flex items-center gap-2">
          <AlertCircle className="w-5 h-5" /> {error}
        </div>
      )}

      <form onSubmit={handlePlaceOrder} className="space-y-6">
        {/* 1. Select Design */}
        <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
          <h2 className="text-lg font-semibold text-slate-800 mb-4">1. Choose Your Design</h2>
          <select
            value={selectedDesignId}
            onChange={(e) => {
              setSelectedDesignId(e.target.value);
              setSelectedSizeId(''); // Reset size on design change
            }}
            className="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500 outline-none"
            required
          >
            <option value="">-- Select a Saved Design --</option>
            {designs.map((d) => (
              <option key={d.id} value={d.id}>
                {d.name.current || d.name.en} (AED {d.price})
              </option>
            ))}
          </select>
        </div>

        {/* 2. Select Size */}
        {selectedDesign && (
          <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm animate-fade-in">
            <h2 className="text-lg font-semibold text-slate-800 mb-4">2. Select Configured Size</h2>
            <div className="flex flex-wrap gap-3">
              {selectedDesign.sizes?.map((size) => (
                <button
                  type="button"
                  key={size.id}
                  onClick={() => setSelectedSizeId(size.id)}
                  className={`px-5 py-3 rounded-xl border font-medium transition-colors ${
                    selectedSizeId === size.id
                      ? 'bg-slate-900 text-white border-slate-900'
                      : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-slate-400'
                  }`}
                >
                  {size.code}
                </button>
              ))}
              {!selectedDesign.sizes?.length && (
                <p className="text-sm text-red-500">No sizes were configured for this design.</p>
              )}
            </div>
          </div>
        )}

        {/* 3. Delivery & Quantity */}
        <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
          <h2 className="text-lg font-semibold text-slate-800 flex items-center gap-2">
            <MapPin className="w-5 h-5" /> 3. Delivery Details
          </h2>
          
          <div>
            <label className="block text-sm font-medium text-slate-700 mb-2">Shipping Address</label>
            <select
              value={selectedAddressId}
              onChange={(e) => setSelectedAddressId(e.target.value)}
              className="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500 outline-none"
              required
            >
              <option value="">-- Select an Address --</option>
              {addresses.map((a) => (
                <option key={a.id} value={a.id}>
                  {a.street} ({a.city?.name || 'Unknown City'})
                </option>
              ))}
            </select>
          </div>

          <div>
            <label className="block text-sm font-medium text-slate-700 mb-2">Quantity</label>
            <input
              type="number"
              min="1"
              max="10"
              value={quantity}
              onChange={(e) => setQuantity(e.target.value)}
              className="w-24 p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500 outline-none text-center"
              required
            />
          </div>
        </div>

        <button
          type="submit"
          disabled={submitting || !selectedDesignId || !selectedSizeId || !selectedAddressId}
          className="w-full py-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-lg shadow-sm disabled:opacity-50 transition-colors"
        >
          {submitting ? 'Creating Order...' : 'Review & Continue to Payment'}
        </button>
      </form>
    </div>
  );
}