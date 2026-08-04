import { Link, NavLink, useNavigate } from 'react-router-dom';
import { LogOut, User, Menu } from 'lucide-react';
import { useState } from 'react';
import { logoutCustomer } from '../services/auth';
import { removeToken } from '../services/api';

function navClass({ isActive }) {
  return `text-sm transition-colors ${isActive ? 'text-slate-900 font-medium' : 'text-slate-600 hover:text-slate-900'}`;
}

export default function Header() {
  const navigate = useNavigate();
  const [mobileOpen, setMobileOpen] = useState(false);
  const displayName = sessionStorage.getItem('kandura_user_name') || 'Profile';

  const handleLogout = async () => {
    try {
      await logoutCustomer();
    } finally {
      removeToken();
      sessionStorage.removeItem('kandura_user_name');
      navigate('/login', { replace: true });
    }
  };

  return (
    <header className="border-b border-slate-200 bg-white/80 backdrop-blur">
      <div className="max-w-6xl mx-auto px-4 md:px-6 py-4 flex items-center justify-between">
        <Link to="/" className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-kandura-sand text-slate-900 font-semibold flex items-center justify-center shadow-card">K</div>
          <div>
            <p className="text-lg font-semibold text-slate-900">Kandura Store</p>
            <p className="text-xs text-slate-500">Custom Emirati Robes</p>
          </div>
        </Link>

        <nav className="hidden md:flex items-center gap-6">
          <NavLink to="/designs" className={navClass}>My Designs</NavLink>
          <NavLink to="/orders" className={navClass}>Orders</NavLink>
          <NavLink to="/wallet" className={navClass}>Wallet</NavLink>
          <NavLink to="/profile" className={navClass}>Profile</NavLink>
        </nav>

        <div className="hidden md:flex items-center gap-3">
          <div className="flex items-center gap-2 text-sm text-slate-700">
            <User className="w-4 h-4" />
            <span>{displayName}</span>
          </div>
          <button onClick={handleLogout} className="px-3 py-2 rounded-lg border border-slate-300 text-sm text-slate-700 hover:bg-slate-50">
            <span className="inline-flex items-center gap-2"><LogOut className="w-4 h-4" />Logout</span>
          </button>
        </div>

        <button className="md:hidden p-2 rounded-lg border border-slate-300" onClick={() => setMobileOpen((v) => !v)} aria-label="Toggle navigation menu">
          <Menu className="w-5 h-5" />
        </button>
      </div>

      {mobileOpen && (
        <div className="md:hidden border-t border-slate-200 px-4 py-3 space-y-3 bg-white">
          <NavLink to="/designs" className={navClass} onClick={() => setMobileOpen(false)}>My Designs</NavLink>
          <NavLink to="/orders" className={navClass} onClick={() => setMobileOpen(false)}>Orders</NavLink>
          <NavLink to="/wallet" className={navClass} onClick={() => setMobileOpen(false)}>Wallet</NavLink>
          <NavLink to="/profile" className={navClass} onClick={() => setMobileOpen(false)}>Profile</NavLink>
          <button onClick={handleLogout} className="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm text-slate-700 hover:bg-slate-50 text-left">
            Logout
          </button>
        </div>
      )}
    </header>
  );
}
