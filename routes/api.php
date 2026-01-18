<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\DesignController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\CouponController;
use App\Http\Controllers\Webhook\WebhookController;
use App\Http\Controllers\User\OrderCouponController;
use App\Http\Controllers\User\OrderPaymentController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware('auth:customer')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});




Route::middleware('auth:customer')->group(function () {

    Route::put('/user/profile', [UserController::class, 'update']);
    Route::get('/user/profile', [UserController::class, 'profile']);
});


Route::middleware('auth:customer')->group(function () {
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);
});




Route::middleware('auth:customer')->group(function () {

    Route::get('/designs',         [DesignController::class, 'index']);
    Route::post('/designs',        [DesignController::class, 'store']);
    Route::get('/designs/{design}',   [DesignController::class, 'show']);
    Route::put('/designs/{design}',   [DesignController::class, 'update']);
    Route::delete('/designs/{design}', [DesignController::class, 'destroy']);
});


Route::middleware('auth:customer')->group(function () {
    Route::get('/orders',         [OrderController::class, 'index']);
    Route::post('/orders',        [OrderController::class, 'store']);
    Route::get('/orders/{order}',   [OrderController::class, 'show']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
});

Route::middleware('auth:customer')->group(function () {
    Route::post('/orders/{order}/pay', [OrderPaymentController::class, 'pay']);
});
Route::post('/stripe/webhook', [WebhookController::class, 'handle']);

Route::get('/payment/success/{order}', function (Order $order) {
    return response()->json(['message' => 'Payment success', 'order_id' => $order->id]);
})->name('stripe.success');

Route::get('/payment/cancel/{order}', function (Order $order) {
    return response()->json(['message' => 'Payment canceled', 'order_id' => $order->id]);
})->name('stripe.cancel');

Route::middleware('auth:customer')->group(function () {
    Route::post('/orders/{order}/coupon', [CouponController::class, 'apply']);
});



Route::get('/lang/{locale}', function ($locale) {
    if (! in_array($locale, ['ar', 'en'])) {
        abort(400, 'Unsupported language');
    }

    session(['app_locale' => $locale]);

    return back();
})->name('lang.switch');
