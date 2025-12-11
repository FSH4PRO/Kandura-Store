<?php

namespace App\Services\Admin;

use App\Models\Design;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DesignService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        $query = Design::query()
            ->with(['sizes', 'options', 'customer.user']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $locale = app()->getLocale();

            $query->where(function ($q) use ($search, $locale) {
                $q->where("name->{$locale}", 'like', "%{$search}%")
                    ->orWhereHas('customer.user', function ($uq) use ($search, $locale) {
                        $uq->where("name->{$locale}", 'like', "%{$search}%");
                    });
            });
        }


        if (!empty($filters['size_id'])) {
            $query->filterSize((int) $filters['size_id']);
        }


        $query->filterPrice(
            $filters['price_min'] ?? null,
            $filters['price_max'] ?? null
        );


        if (!empty($filters['option_id'])) {
            $query->filterOption((int) $filters['option_id']);
        }

        $sortBy  = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';

        if (! in_array($sortBy, ['created_at', 'price'], true)) {
            $sortBy = 'created_at';
        }
        if (! in_array($sortDir, ['asc', 'desc'], true)) {
            $sortDir = 'desc';
        }

        $query->orderBy($sortBy, $sortDir);

        $perPage = isset($filters['per_page']) ? (int) $filters['per_page'] : 15;
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
