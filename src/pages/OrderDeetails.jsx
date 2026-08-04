// src/pages/OrderDetail.jsx
import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { getOrderById, cancelOrder } from '../services/orders';
import { applyCoupon, removeCoupon } from '../services/coupons';
import { Ticket, XCircle, CheckCircle, Clock, ChevronLeft } from 'lucide-react';

export default function OrderDetail() {
  const { id } = useParams();
  const [order, setOrder] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  
  const [couponCode, setCouponCode] = useState('');
  const [couponLoading, setCouponLoading] = useState(false);

  const fetchOrder = async () => {
    try {
      const data = await getOrderById(id);
      setOrder(data);
    } catch (err) {
      setError('Failed to load order details.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchOrder();
  }, [id]);

  const handleApplyCoupon = async (e) => {
    e.preventDefault();
    if (!couponCode) return;
    setCouponLoading(true);
    try {
      await applyCoupon(id, couponCode);
      await fetchOrder(); // Refresh totals
      setCouponCode('');
    } catch (err) {
      alert(err.message || 'Invalid coupon code');
    } finally {
      setCouponLoading(false);
    }
  };

  const handleRemoveCoupon = async () => {
    setCouponLoading(true);
    try {
      await removeCoupon(id);
      await fetchOrder(); // Refresh totals
    } catch (err) {
      alert('Failed to remove coupon');
    } finally {
      setCouponLoading(false);
    }
  };

  const handleCancelOrder = async () => {
    if (!window.confirm('Are you sure you want to cancel this order?')) return;
    try {
      await cancelOrder(id);
      await fetchOrder(); // Refresh status
    } catch (err) {
      alert('Failed to cancel order.');
    }
  };

  if (loading) return <div className="p-8 text-center text-slate-500">Loading Order...</div>;
  if (error || !order) return <div className="p-8 text-center text-red-500">{error}</div>;

  return (
    <div className="max-w-4xl mx-auto px-4 py-8">
      <Link to="/orders" className="text-sm font-medium text-amber-600 hover:text-amber-700 mb-4 inline-flex items-center gap-1">
        <ChevronLeft className="w-4 h-4" /> Back to Orders
      </Link>
      
      <div className="flex justify-between items-end mb-6">
        <div>
          <h1 className="text-3xl font-bold text-slate-900 font-serif">Order {order.serial_number}</h1>
          <p className="text-slate-500 mt-1 flex items-center gap-2">
            {order.status === 'pending' && <Clock className="w-4 h-4 text-amber-500" />}
            {order.status === 'processing' && <CheckCircle className="w-4 h-4 text-blue-500" />}
            {order.status === 'cancelled' && <XCircle className="w-4 h-4 text-red-500" />}
            Status: <span className="font-semibold uppercase text-slate-700">{order.status}</span>
          </p>
        </div>
        
        {order.status === 'pending' && (
          <button 
            onClick={handleCancelOrder}
            className="text-red-500 hover:bg-red-50 px-4 py-2 rounded-xl text-sm font-medium transition-colors"
          >
            Cancel Order
          </button>
        )}
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {/* Left Column: Items & Delivery */}
        <div className="md:col-span-2 space-y-6">
          <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h2 className="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Order Items</h2>
            {order.items?.map((item) => (
              <div key={item.id} className="flex justify-between items-center py-3">
                <div>
                  <p className="font-medium text-slate-900">{item.design?.name?.en || 'Custom Design'}</p>
                  <p className="text-sm text-slate-500">Size: {item.size?.code} | Qty: {item.quantity}</p>
                </div>
                <p className="font-semibold text-slate-900">AED {item.price}</p>
              </div>
            ))}
          </div>

          <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h2 className="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Delivery Address</h2>
            <p className="text-slate-700">{order.address?.street}</p>
            <p className="text-slate-500 text-sm">{order.address?.details}</p>
          </div>
        </div>

        {/* Right Column: Summary & Coupon */}
        <div className="space-y-6">
          {/* Coupon Section (Only if pending) */}
          {order.status === 'pending' && (
            <div className="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
              <h3 className="font-semibold text-slate-800 mb-3 flex items-center gap-2">
                <Ticket className="w-4 h-4" /> Apply Coupon
              </h3>
              
              {order.coupon_id ? (
                <div className="flex justify-between items-center bg-green-50 text-green-700 p-3 rounded-lg border border-green-200">
                  <span className="text-sm font-medium">Coupon Applied!</span>
                  <button onClick={handleRemoveCoupon} disabled={couponLoading} className="text-xs hover:underline">
                    Remove
                  </button>
                </div>
              ) : (
                <form onSubmit={handleApplyCoupon} className="flex gap-2">
                  <input
                    type="text"
                    placeholder="Enter code"
                    value={couponCode}
                    onChange={(e) => setCouponCode(e.target.value)}
                    className="w-full p-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-amber-500 outline-none text-sm"
                  />
                  <button 
                    type="submit" 
                    disabled={couponLoading || !couponCode}
                    className="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-medium disabled:opacity-50"
                  >
                    Apply
                  </button>
                </form>
              )}
            </div>
          )}

          {/* Totals Summary */}
          <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h2 className="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Order Summary</h2>
            <div className="space-y-3 text-sm text-slate-600 mb-4">
              <div className="flex justify-between">
                <span>Subtotal</span>
                <span>AED {order.subtotal}</span>
              </div>
              {order.discount_amount > 0 && (
                <div className="flex justify-between text-green-600 font-medium">
                  <span>Discount</span>
                  <span>- AED {order.discount_amount}</span>
                </div>
              )}
            </div>
            <div className="flex justify-between text-lg font-bold text-slate-900 border-t pt-3 border-slate-100">
              <span>Total</span>
              <span>AED {order.total}</span>
            </div>

            {order.status === 'pending' && (
              <button className="w-full mt-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold transition-colors">
                Proceed to Payment
              </button>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}