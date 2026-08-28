@extends('layouts.contentNavbarLayout')

@section('title', __('coupons.coupons_management'))

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">{{ __('coupons.coupons_management') }}</h5>
          @can('coupons.create')
            <a href="{{ route('coupons.create') }}" class="btn btn-primary">
              <i class="bx bx-plus me-1"></i>{{ __('coupons.create_coupon') }}
            </a>
          @endcan
        </div>

        {{-- messages   --}}
        @if (session('success'))
          <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <div class="card-datatable table-responsive">
          <table class="datatables-coupons table">
            <thead class="border-top">
              <tr>
                <th>{{ __('coupons.code') }}</th>
                <th>{{ __('coupons.type') }}</th>
                <th>{{ __('coupons.amount') }}</th>
                <th>{{ __('coupons.uses') }}</th>
                <th>{{ __('coupons.status') }}</th>
                <th>{{ __('coupons.valid_until') }}</th>
                <th>{{ __('coupons.actions') }}</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($coupons as $coupon)
                <tr>
                  <td><strong>{{ $coupon->code }}</strong></td>

                  <td>
                    <span class="badge bg-label-{{ $coupon->type->value === 'percent' ? 'info' : 'success' }}">
                      {{ $coupon->type->value === 'percent' ? __('coupons.percent') : __('coupons.fixed') }}
                    </span>
                  </td>

                  <td>
                    {{ $coupon->type->value === 'percent' ? $coupon->amount . '%' : number_format($coupon->amount, 2) }}
                  </td>

                  <td>
                    {{ $coupon->redemptions_count ?? 0 }}
                    @if ($coupon->max_uses)
                      / {{ $coupon->max_uses }}
                    @endif
                  </td>

                  <td>
                    @if ($coupon->is_active)
                      <span class="badge bg-success">{{ __('coupons.active') }}</span>
                    @else
                      <span class="badge bg-danger">{{ __('coupons.inactive') }}</span>
                    @endif
                  </td>

                  <td>
                    @if ($coupon->ends_at)
                      {{ $coupon->ends_at->format('Y-m-d') }}
                    @else
                      {{ __('coupons.no_limit') }}
                    @endif
                  </td>

                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
                        data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>

                      <div class="dropdown-menu">
                        @can('coupons.view')
                          <a class="dropdown-item" href="{{ route('coupons.show', $coupon) }}">
                            <i class="bx bx-show-alt me-1"></i> {{ __('coupons.view') }}
                          </a>
                        @endcan

                        @can('coupons.edit')
                          <a class="dropdown-item" href="{{ route('coupons.edit', $coupon) }}">
                            <i class="bx bx-edit-alt me-1"></i> {{ __('coupons.edit') }}
                          </a>

                          <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="dropdown-item">
                              <i class="bx bx-{{ $coupon->is_active ? 'pause' : 'play' }} me-1"></i>
                              {{ $coupon->is_active ? __('coupons.deactivate') : __('coupons.activate') }}
                            </button>
                          </form>
                        @endcan

                        @if (($coupon->redemptions_count ?? 0) == 0)
                          @can('coupons.delete')
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('{{ __('coupons.confirm_delete') }}')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="dropdown-item text-danger">
                                <i class="bx bx-trash me-1"></i> {{ __('coupons.delete') }}
                              </button>
                            </form>
                          @endcan
                        @endif
                      </div>
                    </div>
                  </td>

                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="card-footer">
          {{ $coupons->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      $('.datatables-coupons').DataTable({
        responsive: true,
        order: [
          [0, 'desc']
        ],
        columnDefs: [{
          orderable: false,
          targets: 6
        }]
      });
    });
  </script>
@endsection
