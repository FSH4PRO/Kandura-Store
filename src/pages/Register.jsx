import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { registerCustomer } from '../services/auth';
import { AlertCircle } from 'lucide-react';

export default function Register() {
  const [name, setName] = useState('');
  const [phone, setPhone] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      const payload = { name: { en: name }, phone, password, password_confirmation: passwordConfirmation };
      await registerCustomer(payload);
      navigate('/');
    } catch (err) {
      if (err?.type === 'validation') {
        setError(Object.values(err.errors).flat().join(' '));
      } else {
        setError(err.message || 'Registration failed');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-white px-4">
      <div className="w-full max-w-md bg-white shadow-card rounded-xl-2 p-8">
        <h1 className="text-2xl font-semibold text-slate-900 mb-2">Create an account</h1>
        <p className="text-sm text-slate-600 mb-6">Register to save designs and place orders.</p>

        {error && (
          <div className="flex items-start gap-2 bg-red-50 border border-red-100 text-red-700 p-3 rounded-md mb-4">
            <AlertCircle className="w-5 h-5" />
            <div className="text-sm">{error}</div>
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm mb-1 text-slate-700">Full name</label>
            <input value={name} onChange={e => setName(e.target.value)} required className="w-full p-3 border rounded-lg border-slate-200 focus:outline-none focus:ring-2 focus:ring-kandura-sand" placeholder="Ahmed Ali" />
          </div>
          <div>
            <label className="block text-sm mb-1 text-slate-700">Phone</label>
            <input value={phone} onChange={e => setPhone(e.target.value)} required className="w-full p-3 border rounded-lg border-slate-200 focus:outline-none focus:ring-2 focus:ring-kandura-sand" placeholder="+971501234567" />
          </div>
          <div>
            <label className="block text-sm mb-1 text-slate-700">Password</label>
            <input type="password" value={password} onChange={e => setPassword(e.target.value)} required className="w-full p-3 border rounded-lg border-slate-200 focus:outline-none focus:ring-2 focus:ring-kandura-sand" placeholder="At least 8 characters" />
          </div>
          <div>
            <label className="block text-sm mb-1 text-slate-700">Confirm password</label>
            <input type="password" value={passwordConfirmation} onChange={e => setPasswordConfirmation(e.target.value)} required className="w-full p-3 border rounded-lg border-slate-200 focus:outline-none focus:ring-2 focus:ring-kandura-sand" placeholder="Confirm password" />
          </div>

          <div>
            <button disabled={loading} className="w-full py-3 bg-kandura-sand text-white rounded-lg shadow-md hover:opacity-95">
              {loading ? 'Creating…' : 'Create account'}
            </button>
          </div>
        </form>

        <p className="text-sm text-slate-600 mt-4">Already have an account? <Link to="/login" className="text-kandura-sand font-medium">Sign in</Link></p>
      </div>
    </div>
  );
}
