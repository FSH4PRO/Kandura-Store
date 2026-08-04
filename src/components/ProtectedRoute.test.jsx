import React from 'react';
import { describe, it, expect, beforeEach } from 'vitest';
import { MemoryRouter, Routes, Route } from 'react-router-dom';
import { render, screen } from '@testing-library/react';
import ProtectedRoute from './ProtectedRoute';

function renderWithRoute(initialPath = '/profile') {
  return render(
    <MemoryRouter initialEntries={[initialPath]}>
      <Routes>
        <Route path="/login" element={<div>Login Screen</div>} />
        <Route element={<ProtectedRoute />}>
          <Route path="/profile" element={<div>Profile Screen</div>} />
        </Route>
      </Routes>
    </MemoryRouter>
  );
}

describe('ProtectedRoute', () => {
  beforeEach(() => {
    localStorage.clear();
  });

  it('redirects to /login when no token exists', () => {
    renderWithRoute('/profile');
    expect(screen.getByText('Login Screen')).toBeInTheDocument();
  });

  it('renders protected route when token exists', () => {
    localStorage.setItem('kandura_access_token', 'token');
    renderWithRoute('/profile');
    expect(screen.getByText('Profile Screen')).toBeInTheDocument();
  });
});
