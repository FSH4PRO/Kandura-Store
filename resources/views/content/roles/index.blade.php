@extends('layouts.contentNavbarLayout')

@section('title', __('roles.index.title'))

@section('content')
  @php
    /** @var \Spatie\Permission\Models\Role[]|\Illuminate\Pagination\LengthAwarePaginator $roles */
    $admin = auth('admin')->user();
  @endphp

  <div class="row">
    <div class="col-12 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="mb-1">{{ __('roles.index.heading') }}</h4>
          <p class="mb-0 text-muted">{{ __('roles.index.subheading') }}</p>
        </div>

        {{-- زر إنشاء رول جديد --}}
        @can('roles.create')
          <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
            {{ __('roles.index.create_button') }}
          </a>
        @endcan
      </div>
  </div>
  </div>
  {{-- messages --}}
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- searching --}}
  <div class="row mb-4">
    <div class="col-12 col-md-6">
      <form action="{{ route('roles.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="{{ __('roles.index.search_placeholder') }}"
          value="{{ request('search') }}">
        <button type="submit" class="btn btn-outline-primary">
          {{ __('roles.index.search_button') }}
        </button>
      </form>
    </div>
   {{-- reset searching --}}
    <div class="col-3 col-md-6">
      <form action="{{ route('roles.index') }}" method="GET" class="d-flex justify-content-end">
        <button type="submit" class="btn btn-outline-primary">
          {{ __('roles.index.reset_button') }}
        </button>
      </form>
    </div>
   


  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="table-responsive text-nowrap">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>{{ __('roles.index.table.name') }}</th>
                <th>{{ __('roles.index.table.permissions_count') }}</th>
                <th>{{ __('roles.index.table.created_at') }}</th>
                <th class="text-center">{{ __('roles.index.table.actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($roles as $role)
                <tr>
                  {{-- Role name --}}
                  <td>{{ $role->name }}</td>

                  {{-- Permissions count --}}
                  <td>{{ $role->permissions->count() }}</td>

                  {{-- Created at --}}
                  <td>{{ $role->created_at?->format('Y-m-d') }}</td>

                  {{-- Actions --}}
                  <td class="text-center">
                    <div class="d-inline-flex align-items-center gap-1">

                      {{-- Edit --}}
                      @if ($admin && $admin->can('roles.edit'))
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary">
                          {{ __('roles.index.actions.edit') }}
                        </a>
                      @endif

                      {{-- Delete --}}
                      @if ($admin && $admin->can('roles.delete'))
                        <form action="{{ route('roles.destroy', $role) }}" method="POST"
                          onsubmit="return confirm('{{ __('roles.index.actions.confirm_delete') }}');" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger">
                            {{ __('roles.index.actions.delete') }}
                          </button>
                        </form>
                      @endif

                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted py-4">
                    {{ __('roles.index.table.empty') }}
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if ($roles instanceof \Illuminate\Contracts\Pagination\Paginator)
          <div class="card-footer">
            {{ $roles->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
