@extends('layouts/blankLayout')

@section('title', 'Payment Failed')

@section('page-style')
    <!-- Page -->
    @vite(['resources/assets/vendor/scss/pages/page-misc.scss'])
@endsection

@section('content')
    <!-- Payment Failed -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h1 class="mb-3 mx-2">✕</h1>
            <h4 class="mb-3 mx-2">Payment Failed!</h4>
            <p class="mb-3 mx-2">We were unable to process your payment. Please try again.</p>

            @if (request('error'))
                <p class="mb-6 mx-2 text-danger"><small>Error: {{ request('error') }}</small></p>
            @else
                <p class="mb-6 mx-2 text-muted"><small>If the problem persists, please contact our support team.</small></p>
            @endif

            <div class="mb-6">
                <a href=" #" class="btn btn-primary me-2">Try Again</a>
                <a href="#" class="btn btn-outline-secondary">Back to Home</a>
            </div>

            <div class="mt-6">
                <img src="{{ asset('assets/img/illustrations/page-misc-error-light.png') }}" alt="payment-failed"
                    width="500" class="img-fluid" />
            </div>
        </div>
    </div>
    <!-- /Payment Failed -->
@endsection
