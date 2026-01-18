<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Services\Admin\CouponService;
use App\Http\Requests\Admin\StoreCouponRequest as AdminStoreCouponRequest;
use App\Http\Requests\Admin\UpdateCouponRequest;

class CouponController extends Controller
{

    protected $service;
    public function __construct(CouponService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $coupons = Coupon::query()
            ->withCount('redemptions')
            ->latest()
            ->paginate(10);

        return view('content.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $customers = Customer::query()
            ->with('user')
            ->latest()
            ->limit(200) // keep it reasonable; can be replaced with search/ajax
            ->get();

        return view('content.coupons.create', compact('customers'));
    }

    public function store(AdminStoreCouponRequest $request)
    {
        $adminId = auth('admin')->id();

        $this->service->create($request->validated(), $adminId);
        return redirect()
            ->route('coupons.index')
            ->with('success', __('coupons.coupon_created_successfully'));
    }
w
    public function show(Coupon $coupon)
    {
        $coupon->loadCount('redemptions')
            ->load('allowedCustomers.user');

        return view('content.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        $customers = Customer::query()
            ->with('user')
            ->latest()
            ->limit(200)
            ->get();

        $selectedCustomerIds = $coupon->allowedCustomers()
            ->pluck('customers.id')
            ->toArray();

        return view('content.coupons.edit', compact('coupon', 'customers', 'selectedCustomerIds'));
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $this->service->update($coupon, $request->validated());

        return redirect()
            ->route('coupons.index')
            ->with('success', __('coupons.coupon_updated_successfully'));
    }

    public function toggle(Coupon $coupon)
    {
        $this->service->toggle($coupon);

        return redirect()
            ->back()
            ->with('success', __('coupons.coupon_status_updated_successfully'));
    }

    public function destroy(Coupon $coupon)
    {
        $this->service->delete($coupon);

        return redirect()
            ->route('coupons.index')
            ->with('success', __('coupons.coupon_deleted_successfully'));
    }
}
