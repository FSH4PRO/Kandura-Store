import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { loginCustomer } from '../services/auth';
import { AlertCircle } from 'lucide-react';

export default function Login() {
  const [phone, setPhone] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      const data = await loginCustomer(phone, password);
      // backend returns user in data
      navigate('/');
    } catch (err) {
      if (err?.type === 'validation') {
        setError(Object.values(err.errors).flat().join(' '));
      } else {
        setError(err.message || 'Login failed');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-white px-4">
      <div className="w-full max-w-md bg-white shadow-card rounded-xl-2 p-8">
        <h1 className="text-2xl font-semibold text-slate-900 mb-2">Sign in to Kandura</h1>
        <p className="text-sm text-slate-600 mb-6">Securely access your designs, orders and wallet.</p>

        {error && (
          <div className="flex items-start gap-2 bg-red-50 border border-red-100 text-red-700 p-3 rounded-md mb-4">
            <AlertCircle className="w-5 h-5" />
            <div className="text-sm">{error}</div>
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm mb-1 text-slate-700">Phone</label>
            <input value={phone} onChange={e => setPhone(e.target.value)} required className="w-full p-3 border rounded-lg border-slate-200 focus:outline-none focus:ring-2 focus:ring-kandura-sand" placeholder="+971501234567" />
          </div>
          <div>
            <label className="block text-sm mb-1 text-slate-700">Password</label>
            <input type="password" value={password} onChange={e => setPassword(e.target.value)} required className="w-full p-3 border rounded-lg border-slate-200 focus:outline-none focus:ring-2 focus:ring-kandura-sand" placeholder="Enter your password" />
          </div>

          <div>
            <button disabled={loading} className="w-full py-3 bg-kandura-sand text-white rounded-lg shadow-md hover:opacity-95">
              {loading ? 'Signing in…' : 'Sign in'}
            </button>
          </div>
        </form>

        <p className="text-sm text-slate-600 mt-4">Don’t have an account? <Link to="/register" className="text-kandura-sand font-medium">Register</Link></p>
      </div>
    </div>
  );
}
