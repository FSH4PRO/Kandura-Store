<?php

namespace App\Services\Search;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Wallet;
use App\Models\Design;
use App\Models\User;
use Illuminate\Support\Collection;

class SearchService
{
  /**
   * Search across all entities
   */
  public function search(string $query, array $types = []): array
  {
    $results = [
      'orders' => [],
      'customers' => [],
      'wallets' => [],
      'designs' => [],
      'users' => [],
    ];

    if (empty($query) || strlen(trim($query)) < 2) {
      return $results;
    }

    $searchTerm = trim($query);

    // Search orders
    if (empty($types) || in_array('orders', $types)) {
      $results['orders'] = $this->searchOrders($searchTerm);
    }

    // Search customers
    if (empty($types) || in_array('customers', $types)) {
      $results['customers'] = $this->searchCustomers($searchTerm);
    }

    // Search wallets
    if (empty($types) || in_array('wallets', $types)) {
      $results['wallets'] = $this->searchWallets($searchTerm);
    }

    // Search designs
    if (empty($types) || in_array('designs', $types)) {
      $results['designs'] = $this->searchDesigns($searchTerm);
    }

    // Search users
    if (empty($types) || in_array('users', $types)) {
      $results['users'] = $this->searchUsers($searchTerm);
    }

    return $results;
  }

  /**
   * Search orders
   */
  protected function searchOrders(string $query): Collection
  {
    $locale = app()->getLocale();

    return Order::with(['customer.user'])
      ->where(function ($q) use ($query) {
        // Search by order ID
        if (is_numeric($query)) {
          $q->where('id', (int) $query);
        }

        // Search by customer name
        $q->orWhereHas('customer', function ($customerQuery) use ($query) {
          $customerQuery->where('phone', 'like', "%{$query}%")
            ->orWhereHas('user', function ($userQuery) use ($query) {
              $userQuery->where('name->en', 'like', "%{$query}%")
                ->orWhere('name->ar', 'like', "%{$query}%");
            });
        });
      })
      ->limit(10)
      ->orderBy('created_at', 'desc')
      ->get();
  }

  /**
   * Search customers
   */
  protected function searchCustomers(string $query): Collection
  {
    return Customer::with('user')
      ->where(function ($q) use ($query) {
        $q->where('phone', 'like', "%{$query}%")
          ->orWhereHas('user', function ($userQuery) use ($query) {
            $userQuery->where('name->en', 'like', "%{$query}%")
              ->orWhere('name->ar', 'like', "%{$query}%");
          });
      })
      ->limit(10)
      ->orderBy('created_at', 'desc')
      ->get();
  }

  /**
   * Search wallets
   */
  protected function searchWallets(string $query): Collection
  {
    return Wallet::with(['customer.user'])
      ->where(function ($q) use ($query) {
        // Search by wallet ID
        if (is_numeric($query)) {
          $q->where('id', (int) $query);
        }

        // Search by customer
        $q->orWhereHas('customer', function ($customerQuery) use ($query) {
          $customerQuery->where('phone', 'like', "%{$query}%")
            ->orWhereHas('user', function ($userQuery) use ($query) {
              $userQuery->where('name->en', 'like', "%{$query}%")
                ->orWhere('name->ar', 'like', "%{$query}%");
            });
        });
      })
      ->limit(10)
      ->orderBy('created_at', 'desc')
      ->get();
  }

  /**
   * Search designs
   */
  protected function searchDesigns(string $query): Collection
  {
    $locale = app()->getLocale();

    return Design::with(['customer.user'])
      ->where(function ($q) use ($locale, $query) {
        $q->where("name->{$locale}", 'like', "%{$query}%")
          ->orWhere("description->{$locale}", 'like', "%{$query}%")
          ->orWhereHas('customer', function ($customerQuery) use ($query) {
            $customerQuery->where('phone', 'like', "%{$query}%")
              ->orWhereHas('user', function ($userQuery) use ($query) {
                $userQuery->where('name->en', 'like', "%{$query}%")
                  ->orWhere('name->ar', 'like', "%{$query}%");
              });
          });
      })
      ->limit(10)
      ->orderBy('created_at', 'desc')
      ->get();
  }

  /**
   * Search users
   */
  protected function searchUsers(string $query): Collection
  {
    return User::where(function ($q) use ($query) {
      $q->where('name->en', 'like', "%{$query}%")
        ->orWhere('name->ar', 'like', "%{$query}%");
    })
      ->limit(10)
      ->orderBy('created_at', 'desc')
      ->get();
  }

  /**
   * Get quick search suggestions (for autocomplete)
   */
  public function getSuggestions(string $query, int $limit = 5): array
  {
    if (empty($query) || strlen(trim($query)) < 2) {
      return [];
    }

    $searchTerm = trim($query);
    $suggestions = [];

    // Order suggestions
    if (is_numeric($searchTerm)) {
      $order = Order::with(['customer.user'])->find((int) $searchTerm);
      if ($order) {
        $customer = $order->customer?->user;
        $suggestions[] = [
          'type' => 'order',
          'id' => $order->id,
          'title' => 'Order #' . $order->id,
          'subtitle' => $customer?->name ?? $order->customer?->phone ?? 'Unknown',
          'url' => route('admin.orders.show', $order->id),
        ];
      }
    }

    // Customer suggestions
    $customers = Customer::with('user')
      ->where('phone', 'like', "%{$searchTerm}%")
      ->orWhereHas('user', function ($q) use ($searchTerm) {
        $q->where('name->en', 'like', "%{$searchTerm}%")
          ->orWhere('name->ar', 'like', "%{$searchTerm}%");
      })
      ->limit($limit)
      ->get();

    foreach ($customers as $customer) {
      $user = $customer->user;
      $suggestions[] = [
        'type' => 'customer',
        'id' => $customer->id,
        'title' => $user?->name ?? $customer->phone,
        'subtitle' => $customer->phone,
        'url' => route('users.index', ['search' => $searchTerm]),
      ];
    }

    return array_slice($suggestions, 0, $limit);
  }
}
