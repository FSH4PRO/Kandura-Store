<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Wallet\WalletService;
use App\Models\Wallet;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
  public function __construct(
    protected WalletService $walletService
  ) {}

  /**
   * Display a listing of wallets
   */
  public function index(Request $request)
  {
    $admin = auth('admin')->user();
    $this->authorize('viewAny', Wallet::class);

    $filters = $request->validate([
      'search' => ['nullable', 'string', 'max:255'],
      'is_active' => ['nullable', 'boolean'],
      'balance_min' => ['nullable', 'numeric', 'min:0'],
      'balance_max' => ['nullable', 'numeric', 'min:0'],
      'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
    ]);

    $query = Wallet::with(['customer.user']);

    // Search by customer name or phone
    if (!empty($filters['search'])) {
      $search = $filters['search'];
      $query->whereHas('customer', function ($q) use ($search) {
        $q->where('phone', 'like', "%{$search}%")
          ->orWhereHas('user', function ($q2) use ($search) {
            $q2->where('name->en', 'like', "%{$search}%")
              ->orWhere('name->ar', 'like', "%{$search}%");
          });
      });
    }

    // Filter by active status
    if (isset($filters['is_active'])) {
      $query->where('is_active', $filters['is_active']);
    }

    // Filter by balance range
    if (!empty($filters['balance_min'])) {
      $query->where('balance', '>=', $filters['balance_min']);
    }

    if (!empty($filters['balance_max'])) {
      $query->where('balance', '<=', $filters['balance_max']);
    }

    $perPage = $filters['per_page'] ?? 15;
    $wallets = $query->orderBy('created_at', 'desc')->paginate($perPage);

    return view('content.wallets.index', [
      'wallets' => $wallets,
      'filters' => $filters,
    ]);
  }

  /**
   * Show a specific wallet
   */
  public function show(Wallet $wallet)
  {
    $admin = auth('admin')->user();
    $this->authorize('view', $wallet);

    $wallet->load(['customer.user', 'transactions' => function ($query) {
      $query->latest()->limit(50);
    }]);

    return view('content.wallets.show', [
      'wallet' => $wallet,
    ]);
  }

  /**
   * Add credit to a specific wallet
   */
  public function topup(Request $request, Wallet $wallet)
  {
    $admin = auth('admin')->user();
    $this->authorize('addCredit', $wallet);

    $data = $request->validate([
      'amount' => ['required', 'numeric', 'min:0.01'],
      'reference' => ['nullable', 'string', 'max:255'],
      'note' => ['nullable', 'string', 'max:500'],
    ]);

    try {
      $tx = $this->walletService->credit(
        $wallet,
        (float) $data['amount'],
        'Admin top-up',
        [
          'note' => $data['note'] ?? null,
          'reference' => $data['reference'] ?? null,
          'admin_id' => $admin->id,
        ]
      );

      return back()->with('success', __('wallets.messages.topped_up', ['amount' => $data['amount']]));
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  /**
   * Add credit to all active wallets
   */
  public function bulkTopup(Request $request)
  {
    $admin = auth('admin')->user();
    $this->authorize('bulkAddCredit', Wallet::class);

    $data = $request->validate([
      'amount' => ['required', 'numeric', 'min:0.01'],
      'reference' => ['nullable', 'string', 'max:255'],
      'note' => ['nullable', 'string', 'max:500'],
    ]);

    try {
      $count = $this->walletService->bulkCredit(
        (float) $data['amount'],
        'Bulk credit from admin',
        [
          'note' => $data['note'] ?? null,
          'reference' => $data['reference'] ?? null,
          'admin_id' => $admin->id,
        ]
      );

      return back()->with('success', __('wallets.messages.bulk_topped_up', ['count' => $count, 'amount' => $data['amount']]));
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  /**
   * Activate a wallet
   */
  public function activate(Wallet $wallet)
  {
    $admin = auth('admin')->user();
    $this->authorize('manageStatus', $wallet);

    try {
      $this->walletService->activate($wallet);
      return back()->with('success', __('wallets.messages.activated'));
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }

  /**
   * Deactivate a wallet
   */
  public function deactivate(Wallet $wallet)
  {
    $admin = auth('admin')->user();
    $this->authorize('manageStatus', $wallet);

    try {
      $this->walletService->deactivate($wallet);
      return back()->with('success', __('wallets.messages.deactivated'));
    } catch (\Exception $e) {
      return back()->with('error', $e->getMessage());
    }
  }
}
