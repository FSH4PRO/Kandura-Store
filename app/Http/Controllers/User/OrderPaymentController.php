<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Enums\PaymentMethod;
use App\DTO\Payments\PayOrderData;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Payments\PaymentService;
use App\Http\Requests\Order\ConfirmOrderRequest;

class OrderPaymentController extends Controller
{
    public function __construct(
        protected PaymentService $service
    ) {}

    public function pay(ConfirmOrderRequest $request, Order $order)
    {
        try {
            $customer = auth('customer')->user();

            // Authorize payment using policy
            $this->authorize('pay', $order);

            $data = new PayOrderData(
                method: PaymentMethod::from($request->validated('payment_method')),
                successUrl: $request->validated('success_url'),
                cancelUrl: $request->validated('cancel_url'),
            );

            $payload = $this->service->pay($customer, $order, $data);

            return $this->success($payload, __('orders.payment.started'));
        } catch (\RuntimeException $e) {
            return $this->failed($e->getMessage(), null, 422);
        } catch (\Exception $e) {
            Log::error('Payment error', [
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->failed($e->getMessage(), null, 500);
        }
    }
}
