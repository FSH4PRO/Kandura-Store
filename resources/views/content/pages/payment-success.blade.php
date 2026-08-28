@extends('layouts/blankLayout')

@section('title', 'Payment Successful')

@section('page-style')
    <!-- Page -->
    @vite(['resources/assets/vendor/scss/pages/page-misc.scss'])
@endsection

@section('content')
    <!-- Payment Success -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">  
            <h1 class="mb-3 mx-2">✓</h1>
            <h4 class="mb-3 mx-2">Payment Successful!</h4>
            <p class="mb-3 mx-2">Your payment has been processed successfully.</p>
         

            <div class="mb-6">
                <a href="#" class="btn btn-primary me-2">View My Orders</a>
                <a href="#" class="btn btn-outline-secondary">Back to Home</a>
            </div>

            <div class="mt-6">
                <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.png') }}" alt="payment-success"
                    width="500" class="img-fluid" />
            </div>
        </div>
    </div>
    <!-- /Payment Success -->
@endsection
