<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Http\Requests\dashboard\TransactionIndexRequest;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;

class Transactions extends Controller
{
  public function index(TransactionIndexRequest $request)
  {
    $query = WalletTransaction::with(['wallet.customer.user']);

    // Apply filters
    if ($request->filled('type')) {
      $query->where('type', $request->type);
    }

    if ($request->filled('date_from')) {
      $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
      $query->whereDate('created_at', '<=', $request->date_to);
    }

    if ($request->filled('search')) {
      $search = $request->search;
      $query->whereHas('wallet.customer.user', function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%");
      });
    }

    $transactions = $query->latest()->paginate(15);

    if ($request->expectsJson()) {
      return TransactionResource::collection($transactions);
    }

    return view('content.dashboard.transactions', compact('transactions'));
  }

  public function show(WalletTransaction $transaction)
  {
    $transaction->load(['wallet.customer.user']);

    if (request()->expectsJson()) {
      return new TransactionResource($transaction);
    }

    return view('content.dashboard.transaction-detail', compact('transaction'));
  }
}
