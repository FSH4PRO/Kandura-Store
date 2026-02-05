<?php


namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Review;
use App\Http\Controllers\Controller;
use App\Services\User\ReviewService;
use App\Http\Resources\ReviewResource;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;

class OrderReviewController extends Controller
{
  public function __construct(protected ReviewService $service) {}

  public function store(StoreReviewRequest $request, Order $order)
  {
    $customer = auth('customer')->user();

  
    $review = $this->service->create($customer, $order, $request->validated());

    return $this->success(new ReviewResource($review), __('reviews.created'), 201);
  }


  public function update(UpdateReviewRequest $request, Review $review)
  {
    $customer = auth('customer')->user();

    $this->authorize('update', $review);

    $updated = $this->service->update($review, $request->validated());

    return $this->success(new ReviewResource($updated), __('reviews.updated'));
  }

  public function destroy(Review $review)
  {
    $customer = auth('customer')->user();

    $this->authorize('delete', $review);

    $this->service->delete($review);

    return $this->success(null, __('reviews.deleted'));
  }
}
