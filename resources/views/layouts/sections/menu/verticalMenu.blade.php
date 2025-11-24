@php
  use Illuminate\Support\Facades\Route;

  // اسم الراوتات الأساسية اللي عندك
  $isDashboard = request()->routeIs('dashboard-analytics');

  $isUsers = request()->routeIs('users.index');
  $isAdmins = request()->routeIs('admins.index');
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
        <div>Dashboard</div>
        <div class="badge rounded-pill bg-danger text-uppercase ms-auto">5</div>
      </a>
    </li>

    {{-- ================= User Management ================= --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">User Management</span>
    </li>

    {{-- Admins list --}}
      <li class="menu-item {{ $isAdmins ? 'active' : '' }}">
        <a href="{{ route('admins.index') }}" class="menu-link">
          <i class="menu-icon icon-base bx bx-user-circle"></i>
          <div>Admins</div>
        </a>
      </li> 

    {{-- Users list --}}
    <li class="menu-item {{ $isUsers ? 'active' : '' }}">
      <a href="{{ route('users.index') }}" class="menu-link">
        <i class="menu-icon icon-base bx bx-user"></i>
        <div>Users</div>
      </a>
    </li>

    {{-- ================= Apps & Pages ================= --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Apps &amp; Pages</span>
    </li>

    {{-- Account Settings (submenu) --}}
    <li class="menu-item {{ request()->is('pages/account-settings-*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base bx bx-dock-top"></i>
        <div>Account Settings</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('pages-account-settings-account') ? 'active' : '' }}">
          <a href="{{ url('pages/account-settings-account') }}" class="menu-link">
            <div>Account</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('pages-account-settings-notifications') ? 'active' : '' }}">
          <a href="{{ url('pages/account-settings-notifications') }}" class="menu-link">
            <div>Notifications</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('pages-account-settings-connections') ? 'active' : '' }}">
          <a href="{{ url('pages/account-settings-connections') }}" class="menu-link">
            <div>Connections</div>
          </a>
        </li>
      </ul>
    </li>

    {{-- Auth --}}
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base bx bx-lock-open-alt"></i>
        <div>Authentications</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ url('auth/login-basic') }}" class="menu-link" target="_blank">
            <div>Login</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ url('auth/register-basic') }}" class="menu-link" target="_blank">
            <div>Register</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ url('auth/forgot-password-basic') }}" class="menu-link" target="_blank">
            <div>Forgot Password</div>
          </a>
        </li>
      </ul>
    </li>

    {{-- Misc --}}
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base bx bx-cube-alt"></i>
        <div>Misc</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ url('pages/misc-error') }}" class="menu-link" target="_blank">
            <div>Error</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ url('pages/misc-under-maintenance') }}" class="menu-link" target="_blank">
            <div>Under Maintenance</div>
          </a>
        </li>
      </ul>
    </li>

    {{-- ================= Components ================= --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Components</span>
    </li>

    <li class="menu-item {{ request()->routeIs('cards-basic') ? 'active' : '' }}">
      <a href="{{ url('cards/basic') }}" class="menu-link">
        <i class="menu-icon icon-base bx bx-collection"></i>
        <div>Cards</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('ui/*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base bx bx-box"></i>
        <div>User interface</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item"><a href="{{ url('ui/accordion') }}" class="menu-link">
            <div>Accordion</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/alerts') }}" class="menu-link">
            <div>Alerts</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/badges') }}" class="menu-link">
            <div>Badges</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/buttons') }}" class="menu-link">
            <div>Buttons</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/carousel') }}" class="menu-link">
            <div>Carousel</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/collapse') }}" class="menu-link">
            <div>Collapse</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/dropdowns') }}" class="menu-link">
            <div>Dropdowns</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/footer') }}" class="menu-link">
            <div>Footer</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/list-groups') }}" class="menu-link">
            <div>List groups</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/modals') }}" class="menu-link">
            <div>Modals</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/navbar') }}" class="menu-link">
            <div>Navbar</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/offcanvas') }}" class="menu-link">
            <div>Offcanvas</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/pagination-breadcrumbs') }}" class="menu-link">
            <div>Pagination & Breadcrumbs</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/progress') }}" class="menu-link">
            <div>Progress</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/spinners') }}" class="menu-link">
            <div>Spinners</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/tabs-pills') }}" class="menu-link">
            <div>Tabs & Pills</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/toasts') }}" class="menu-link">
            <div>Toasts</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/tooltips-popovers') }}" class="menu-link">
            <div>Tooltips & Popovers</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('ui/typography') }}" class="menu-link">
            <div>Typography</div>
          </a></li>
      </ul>
    </li>

    <li class="menu-item {{ request()->is('extended/*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base bx bx-copy"></i>
        <div>Extended UI</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item"><a href="{{ url('extended/ui-perfect-scrollbar') }}" class="menu-link">
            <div>Perfect Scrollbar</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('extended/ui-text-divider') }}" class="menu-link">
            <div>Text Divider</div>
          </a></li>
      </ul>
    </li>

    <li class="menu-item {{ request()->routeIs('icons-boxicons') ? 'active' : '' }}">
      <a href="{{ url('icons/boxicons') }}" class="menu-link">
        <i class="menu-icon icon-base bx bx-crown"></i>
        <div>Boxicons</div>
      </a>
    </li>

    {{-- ================= Forms & Tables ================= --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Forms &amp; Tables</span>
    </li>

    <li class="menu-item {{ request()->is('forms/*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base bx bx-detail"></i>
        <div>Form Elements</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item"><a href="{{ url('forms/basic-inputs') }}" class="menu-link">
            <div>Basic Inputs</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('forms/input-groups') }}" class="menu-link">
            <div>Input groups</div>
          </a></li>
      </ul>
    </li>

    <li class="menu-item {{ request()->is('form/layouts-*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base bx bx-detail"></i>
        <div>Form Layouts</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item"><a href="{{ url('form/layouts-vertical') }}" class="menu-link">
            <div>Vertical Form</div>
          </a></li>
        <li class="menu-item"><a href="{{ url('form/layouts-horizontal') }}" class="menu-link">
            <div>Horizontal Form</div>
          </a></li>
      </ul>
    </li>

    <li class="menu-item {{ request()->routeIs('tables-basic') ? 'active' : '' }}">
      <a href="{{ url('tables/basic') }}" class="menu-link">
        <i class="menu-icon icon-base bx bx-table"></i>
        <div>Tables</div>
      </a>
    </li>

    {{-- ================= Misc ================= --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Misc</span>
    </li>

    <li class="menu-item">
      <a href="https://github.com/themeselection/sneat-bootstrap-html-laravel-admin-template-free/issues"
        class="menu-link" target="_blank">
        <i class="menu-icon icon-base bx bx-support"></i>
        <div>Support</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/laravel-introduction.html"
        class="menu-link" target="_blank">
        <i class="menu-icon icon-base bx bx-file"></i>
        <div>Documentation</div>
      </a>
    </li>

  </ul>

</aside>
