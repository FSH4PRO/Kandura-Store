@extends('layouts.contentNavbarLayout')

@section('title', __('design_options.index.title'))

@section('content')
  <div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h4 class="mb-1">{{ __('design_options.index.heading') }}</h4>
        <p class="mb-0 text-muted">{{ __('design_options.index.subheading') }}</p>
      </div>
      <div>
        @can('design_options.create')
          <a href="{{ route('admin.design-options.create') }}" class="btn btn-primary btn-sm">
            {{ __('design_options.index.create_button') }}
          </a>
        @endcan
      </div>
    </div>
  </div>

  {{-- Filters --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="GET" action="{{ route('admin.design-options.index') }}" class="row g-3 align-items-end">

            <div class="col-md-4">
              <label class="form-label">{{ __('design_options.filters.search_label') }}</label>
              <input type="text" name="search" class="form-control"
                placeholder="{{ __('design_options.filters.search_placeholder') }}"
                value="{{ $filters['search'] ?? '' }}">
            </div>

            <div class="col-md-3">
              <label class="form-label">{{ __('design_options.filters.type_label') }}</label>
              <select name="type" class="form-select">
                <option value="">{{ __('design_options.filters.type_all') }}</option>
                @foreach ($types as $type)
                  <option value="{{ $type }}" {{ ($filters['type'] ?? '') === $type ? 'selected' : '' }}>
                    {{ __('design_options.types.' . $type) }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-2">
              <label class="form-label">{{ __('design_options.filters.is_active_label') }}</label>
              <select name="is_active" class="form-select">
                <option value="">{{ __('design_options.filters.any_status') }}</option>
                <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>
                  {{ __('design_options.filters.active_only') }}
                </option>
                <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>
                  {{ __('design_options.filters.inactive_only') }}
                </option>
              </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1">
                {{ __('design_options.filters.submit') }}
              </button>
              <a href="{{ route('admin.design-options.index') }}" class="btn btn-outline-secondary">
                {{ __('design_options.filters.reset') }}
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Table --}}
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="table-responsive text-nowrap">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>{{ __('design_options.table.name') }}</th>
                <th>{{ __('design_options.table.type') }}</th>
                <th>{{ __('design_options.table.status') }}</th>
                <th>{{ __('design_options.table.created_at') }}</th>
                <th class="text-center">{{ __('design_options.table.actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($options as $option)
                <tr>
                  <td>{{ $option->id }}</td>
                  <td>{{ $option->getTranslation('name', app()->getLocale()) }}</td>
                  <td>{{ __('design_options.types.' . $option->type->value) }}</td>
                  <td>
                    @if ($option->is_active)
                      <span class="badge bg-label-success">{{ __('design_options.status.active') }}</span>
                    @else
                      <span class="badge bg-label-secondary">{{ __('design_options.status.inactive') }}</span>
                    @endif
                  </td>
                  <td>{{ $option->created_at?->format('Y-m-d') }}</td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                        type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end">
                        @can('design_options.edit')
                          <a class="dropdown-item" href="{{ route('admin.design-options.edit', $option->id) }}">
                            {{ __('design_options.actions.edit') }}
                          </a>
                        @endcan
                        @can('design_options.delete')
                          <form action="{{ route('admin.design-options.destroy', $option->id) }}" method="POST"
                            onsubmit="return confirm('{{ __('design_options.actions.confirm_delete') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                              {{ __('design_options.actions.delete') }}
                            </button>
                          </form>
                        @endcan
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">
                    {{ __('design_options.table.empty') }}
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if ($options instanceof \Illuminate\Contracts\Pagination\Paginator)
          <div class="card-footer">
            {{ $options->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
