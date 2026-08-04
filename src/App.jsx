import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import Login from './pages/Login';
import Register from './pages/Register';
import Layout from './components/Layout';
import ProtectedRoute from './components/ProtectedRoute';
import Designs from './pages/Designs';
import CreateDesign from './pages/CreateDesign';
import Orders from './pages/Orders';
import Wallet from './pages/Wallet';
import Profile from './pages/Profile';

function DashboardHome() {
  return (
    <section className="bg-white border border-slate-200 rounded-xl p-6 shadow-card">
      <h1 className="text-2xl md:text-3xl font-semibold text-slate-900">Welcome to Kandura Store</h1>
      <p className="mt-2 text-slate-600 max-w-2xl">
        Manage your custom kandura designs, orders, wallet, and profile from one place.
      </p>
    </section>
  );
}

export default function App() {
  return (
    <Routes>
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Register />} />

      <Route element={<ProtectedRoute />}>
        <Route element={<Layout />}>
          <Route path="/" element={<DashboardHome />} />
          <Route path="/designs" element={<Designs />} />
          <Route path="/designs/create" element={<CreateDesign />} />
          <Route path="/orders" element={<Orders />} />
          <Route path="/wallet" element={<Wallet />} />
          <Route path="/profile" element={<Profile />} />
          <Route path="/checkout" element={<Navigate to="/orders" replace />} />
          <Route path="/orders/:id" element={<Navigate to="/orders" replace />} />
        </Route>
      </Route>

      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}