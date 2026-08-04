import React from 'react';
import { describe, it, expect, vi } from 'vitest';
import { render, screen } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';
import Login from './Login';

vi.mock('../services/auth', () => ({
  loginCustomer: vi.fn(),
}));

describe('Login page', () => {
  it('renders login form fields and action', () => {
    render(
      <MemoryRouter>
        <Login />
      </MemoryRouter>
    );

    expect(screen.getByText(/sign in to kandura/i)).toBeInTheDocument();
    expect(screen.getByPlaceholderText('+971501234567')).toBeInTheDocument();
    expect(screen.getByPlaceholderText(/enter your password/i)).toBeInTheDocument();
    expect(screen.getByRole('button', { name: /sign in/i })).toBeInTheDocument();
  });
});
