@extends('layouts.contentNavbarLayout')

@section('title', 'إضافة أدمن - Kandura Store')

@section('content')
  <div class="row">
    <div class="col-12 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="mb-1">إضافة أدمن جديد</h4>
          <p class="mb-0 text-muted">إنشاء حساب مسؤول جديد للنظام.</p>
        </div>
        <div>
          <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
            رجوع لقائمة المستخدمين
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 col-lg-8 col-md-10">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('users.store-admin') }}" method="POST">
            @csrf

            {{-- Name EN --}}
            <div class="mb-3">
              <label class="form-label">الاسم (بالإنكليزية) *</label>
              <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror"
                value="{{ old('name_en') }}" placeholder="مثال: Admin User" required>
              @error('name_en')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Name AR --}}
            <div class="mb-3">
              <label class="form-label">الاسم (بالعربية)</label>
              <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror"
                value="{{ old('name_ar') }}" placeholder="مثال: مشرف النظام">
              @error('name_ar')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
              <label class="form-label">البريد الإلكتروني *</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="admin@example.com" required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Phone --}}
            <div class="mb-3">
              <label class="form-label">رقم الهاتف</label>
              <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone') }}" placeholder="مثال: 0501234567">
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
              <label class="form-label">كلمة المرور *</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                required>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Password Confirmation --}}
            <div class="mb-4">
              <label class="form-label">تأكيد كلمة المرور *</label>
              <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">
              إنشاء أدمن
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
