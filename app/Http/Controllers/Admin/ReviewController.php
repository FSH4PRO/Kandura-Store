<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
  public function index()
  {
    $this->authorize('viewAny', Review::class);

    $reviews = Review::with(['user', 'order.customer.user'])
      ->latest()
      ->paginate(15);

    return view('content.reviews.index', compact('reviews'));
  }

  public function show(Review $review)
  {
    $this->authorize('view', $review);

    $review->load(['user', 'order.customer.user', 'order.items.design']);

    return view('content.reviews.show', compact('review'));
  }
}
