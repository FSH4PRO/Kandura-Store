<?php

namespace App\Services\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function listForUser($customerId, array $filters = [])
    {
        return Address::query()
            ->ownedBy($customerId)
            ->search($filters['search'] ?? null)
            ->filter($filters)
            ->sort($filters['sort_by'] ?? null, $filters['sort_dir'] ?? null)
            ->paginate($filters['per_page'] ?? 15)
            ->withQueryString();
    }

    public function create(array $data, $customerId)
    {
        return DB::transaction(function () use ($data, $customerId) {
            return Address::create(array_merge($data, [
                'customer_id' => $customerId,
            ]));
        });
    }

    public function update(Address $address, array $data)
    {
        return DB::transaction(function () use ($address, $data) {
            $address->update($data);

            return $address->fresh();
        });
    }

    public function delete(Address $address)
    {
        return $address->delete();
    }
}
