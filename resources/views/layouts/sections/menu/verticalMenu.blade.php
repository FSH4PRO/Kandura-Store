@php
  use Illuminate\Support\Facades\Route;

  $isDashboard = request()->routeIs('dashboard-analytics');

  $isUsers   = request()->routeIs('users.index');
  $isAdmins  = request()->routeIs('admins.index');
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- Brand -->
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-logo demo">@include('_partials.macros')</span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">
        {{ config('variables.templateName') }}
      </span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="icon-base bx bx-chevron-left icon-sm d-flex align-items-center justify-content-center"></i>
    </a>
  </div>

  <div class="menu-divider mt-0"></div>
  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    {{-- ================= Dashboard ================= --}}
    <li class="menu-item {{ $isDashboard ? 'active' : '' }}">
      <a href="{{ route('dashboard-analytics') }}" class="menu-link">
        <i class="menu-icon icon-base bx bx-home-smile"></i>
        <div>{{ __('menu.dashboard') }}</div>
        <div class="badge rounded-pill bg-danger text-uppercase ms-auto">
          {{ __('menu.dashboard_badge') }}
        </div>
      </a>
    </li>

    {{-- ================= User Management ================= --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">{{ __('menu.headers.user_management') }}</span>
    </li>

    {{-- Admins list --}}
    @can('admins.view')
      <li class="menu-item {{ $isAdmins ? 'active' : '' }}">
        <a href="{{ route('admins.index') }}" class="menu-link">
          <i class="menu-icon icon-base bx bx-user-circle"></i>
          <div>{{ __('menu.admins') }}</div>
        </a>
      </li>
    @endcan

    {{-- Users list --}}
    @can('users.view')
      <li class="menu-item {{ $isUsers ? 'active' : '' }}">
        <a href="{{ route('users.index') }}" class="menu-link">
          <i class="menu-icon icon-base bx bx-user"></i>
          <div>{{ __('menu.users') }}</div>
        </a>
      </li>
    @endcan

    {{-- ================= Roles & Permissions ================= --}}
    @can('roles.view')
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">{{ __('menu.headers.access_control') }}</span>
      </li>

      <li class="menu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
        <a href="{{ route('roles.index') }}" class="menu-link">
          <i class="menu-icon icon-base bx bx-shield-quarter"></i>
          <div>{{ __('menu.roles') }}</div>
        </a>
      </li>
    @endcan

    {{-- ================= Design Options & Designs ================= --}}
    @if(auth('admin')->check())
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">{{ __('menu.headers.designs') }}</span>
      </li>
    @endif

    {{-- Design Options list --}}
    @can('design_options.view')
      <li class="menu-item {{ request()->routeIs('admin.design-options.*') ? 'active' : '' }}">
        <a href="{{ route('admin.design-options.index') }}" class="menu-link">
          <i class="menu-icon icon-base bx bx-palette"></i>
          <div>{{ __('menu.design_options') }}</div>
        </a>
      </li>
    @endcan

    {{-- Designs list --}}
    @can('designs.view')
      <li class="menu-item {{ request()->routeIs('admin.designs.*') ? 'active' : '' }}">
        <a href="{{ route('admin.designs.index') }}" class="menu-link">
          <i class="menu-icon icon-base bx bx-image"></i>
          <div>{{ __('menu.designs') }}</div>
        </a>
      </li>
    @endcan

    @if(auth('admin')->check())
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">{{ __('menu.headers.orders') }}</span>
      </li>
    @endif

    {{-- Orders list --}}
    @can('orders.view')
      <li class="menu-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <a href="{{ route('admin.orders.index') }}" class="menu-link">
          <i class="menu-icon icon-base bx bx-cart"></i>
          <div>{{ __('menu.orders') }}</div>
        </a>
      </li>
    @endcan

  </ul>

</aside>