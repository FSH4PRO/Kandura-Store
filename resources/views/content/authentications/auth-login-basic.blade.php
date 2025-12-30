@extends('layouts/blankLayout')

@section('title', __('auth.login.title'))

@section('page-style')
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
  {{-- Language switch --}}
  <li class="nav-item">
    <a class="nav-link" href="{{ route('switch.lang') }}"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
        fill="currentColor" class="bi bi-globe2" viewBox="0 0 16 16">
        <path
          d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-6.468 4H5.09c.194-.746.453-1.417.752-1.957.34-.62.733-1.083 1.161-1.356C7.43 1.44 7.708 1.38 8 1.38c.292 0 .57.06.998.307.428.273.821.736 1.161 1.356.299.54.558 1.211.752 1.957h3.558A7 7 0 0 0 8 1z" />
        <path
          d="M8 5c-.552 0-1 .672-1 1.5S7.448 8 8 8s1-.672 1-1.5S8.552 5 8 5zm3.5 2c0-.828-.448-1.5-1-1.5s-1 .672-1 1.5S9.948 8 10.5 8s1-.672 1-1.5zM5.5 7c0-.828-.448-1.5-1-1.5S3.5 6.172 3.5 7s.448 1.5 1 1.5S5.5 7.828 5.5 7zm2.5 3c-.552 0-1 .672-1 1.5S7.448 13 8 13s1-.672 1-1.5S8.552 10 8 10zm3.5 1c0-.828-.448-1.5-1-1.5s-1 .672-1 1.5S9.948 12 10.5 12s1-.672 1-1.5zM5.5 11c0-.828-.448-1.5-1-1.5S3.5 10.172 3.5 11s.448 1.5 1 1.5S5.5 11.828 5.5 11z" />
      </svg></a>
  </li>
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Login -->
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4">
              <a href="{{ url('/') }}" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">@include('_partials.macros')</span>
                <span class="app-brand-text demo text-heading fw-bold">
                  {{ config('variables.templateName') }}
                </span>
              </a>
            </div>
            <!-- /Logo -->

            <h4 class="mb-1">
              {{ __('auth.login.welcome', ['app' => config('variables.templateName')]) }}
            </h4>
            <p class="mb-6">
              {{ __('auth.login.subtitle') }}
            </p>

            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form id="formAuthentication" class="mb-6" action="{{ route('admin.login.post') }}" method="POST">
              @csrf

              {{-- Email --}}
              <div class="mb-6">
                <label for="email" class="form-label">
                  {{ __('auth.login.email_label') }}
                </label>
                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                  name="email" placeholder="{{ __('auth.login.email_placeholder') }}" autofocus
                  value="{{ old('email') }}" />
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Password --}}
              <div class="mb-6 form-password-toggle">
                <label class="form-label" for="password">
                  {{ __('auth.login.password_label') }}
                </label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" placeholder="••••••••••••" aria-describedby="password" />
                  <span class="input-group-text cursor-pointer">
                    <i class="icon-base bx bx-hide"></i>
                  </span>
                </div>
                @error('password')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              {{-- Remember + Forgot --}}
              <div class="mb-8">
                <div class="d-flex justify-content-between">
                  <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" value="1"
                      {{ old('remember') ? 'checked' : '' }} />
                    <label class="form-check-label" for="remember-me">
                      {{ __('auth.login.remember') }}
                    </label>
                  </div>

                  <a href="{{ url('auth/forgot-password-basic') }}">
                    <span>{{ __('auth.login.forgot') }}</span>
                  </a>
                </div>
              </div>
              {{-- Submit --}}
              <div class="mb-6">
                <button class="btn btn-primary d-grid w-100" type="submit">
                  {{ __('auth.login.button') }}
                </button>
              </div>
            </form>
          </div>
        </div>
        <!-- /Login -->
      </div>
    </div>
  </div>
@endsection
