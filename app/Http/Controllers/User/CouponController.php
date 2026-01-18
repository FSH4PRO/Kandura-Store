<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\CouponService;
use App\Http\Resources\OrderResource;

class CouponController extends Controller
{
    protected $service;

    public function __construct(CouponService $service)
    {
        $this->service = $service;
    }
    public function apply(Request $request, Order $order)
    {
        $this->authorize('applyCoupon', $order);
        
        $customer = auth('customer')->user();

        $order = $this->service->apply(
            $customer,
            $order,
            $request->input('code')
        );

        return $this->success(new OrderResource($order));
    }
}
