<?php

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserController;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\CouponController;
use App\Http\Controllers\User\DesignController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\Webhook\WebhookController;
use App\Http\Controllers\User\OrderCouponController;
use App\Http\Controllers\User\OrderReviewController;
use NotificationChannels\Fcm\Resources\Notification;
use App\Http\Controllers\User\OrderPaymentController;
use App\Http\Controllers\Admin\AdminDeviceTokenController;

// ========================
// Authentication Routes
// ========================
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware('auth:customer')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// ========================
// Customer Protected Routes
// ========================
Route::middleware('auth:customer')->group(function () {

    // User Profile
    Route::prefix('user')->group(function () {
        Route::put('/profile', [UserController::class, 'update']);
        Route::get('/profile', [UserController::class, 'profile']);
    });

    // Addresses
    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index']);
        Route::post('/', [AddressController::class, 'store']);
        Route::put('/{address}', [AddressController::class, 'update']);
        Route::delete('/{address}', [AddressController::class, 'destroy']);
    });

    // Designs
    Route::prefix('designs')->group(function () {
        Route::get('/', [DesignController::class, 'index']);
        Route::post('/', [DesignController::class, 'store']);
        Route::get('/{design}', [DesignController::class, 'show']);
        Route::put('/{design}', [DesignController::class, 'update']);
        Route::delete('/{design}', [DesignController::class, 'destroy']);
    });

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/{order}/cancel', [OrderController::class, 'cancel']);
        Route::post('/{order}/pay', [OrderPaymentController::class, 'pay']);
        Route::post('/{order}/coupon', [CouponController::class, 'apply']);
        Route::post('/{order}/coupon/remove', [CouponController::class, 'remove']);
        Route::post('/{order}/review', [OrderReviewController::class, 'store']);
    });

    Route::prefix('reviews')->group(function () {
        Route::put('/{review}', [OrderReviewController::class, 'update']);
        Route::delete('/{review}', [OrderReviewController::class, 'destroy']);
    });
});

// ======================== 
// Payment Webhooks and Callbacks
// ========================
Route::post('/stripe/webhook', [WebhookController::class, 'handle']);

Route::get('/payment/success/{order}', function (Order $order) {
    return view('content.pages.payment-success', ['order' => $order]);
})->name('stripe.success');

Route::get('/payment/cancel/{order}', function (Order $order) {
    return view('content.pages.payment-failed', ['order' => $order]);
})->name('stripe.cancel');


//wallets
Route::middleware('auth:customer')->prefix('wallet')->group(function () {
    Route::get('/', [WalletController::class, 'show']);
    Route::get('/transactions', [WalletController::class, 'transactions']);
});

// ========================
// Language Switch
// ========================
Route::get('/lang/{locale}', function ($locale) {
    if (! in_array($locale, ['ar', 'en'])) {
        abort(400, 'Unsupported language');
    }

    session(['app_locale' => $locale]);

    return back();
})->name('lang.switch');


Route::get('send-notification', function (Request $request) {

    $messaging = Firebase::messaging();


    $notification = Notification::create("title", "body");

    $message = CloudMessage::fromArray([
        'token' => $request->fcm_token,
        'notification' => $notification,
        'data' => []
    ]);
    $messaging->send($message);

    return response()->json([
        'message' => true
    ]);
});
