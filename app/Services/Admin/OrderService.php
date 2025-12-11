<?php

namespace App\Services\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        $query = Order::query()
            ->with(['customer.user', 'items.design']);
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }
                $q->orWhereHas('customer', function ($q1) use ($search) {
                    $q1->whereHas('user', function ($q2) use ($search) {
                        $q2->where('name->en', 'like', "%{$search}%")
                            ->orWhere('name->ar', 'like', "%{$search}%");
                    });
                });
            });
        }

        if (!empty($filters['status'])) {
            $status = $filters['status'];

            if (in_array($status, array_column(OrderStatus::cases(), 'value'), true)) {
                $query->where('status', $status);
            }
        }


        if (!empty($filters['total_min'])) {
            $query->where('total', '>=', (float) $filters['total_min']);
        }

        if (!empty($filters['total_max'])) {
            $query->where('total', '<=', (float) $filters['total_max']);
        }


        $sortBy  = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';

        if (! in_array($sortBy, ['id', 'created_at', 'total_amount'], true)) {
            $sortBy = 'created_at';
        }

        if (! in_array(strtolower($sortDir), ['asc', 'desc'], true)) {
            $sortDir = 'desc';
        }

        $query->orderBy($sortBy, $sortDir);


        $perPage = isset($filters['per_page']) ? (int) $filters['per_page'] : 15;
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        return $query->paginate($perPage)->withQueryString();
    }


    public function updateStatus(Order $order, string $status): Order
    {
        $order->status = OrderStatus::from($status);
        $order->save();

        return $order->fresh();
    }
}
