<?php

namespace App\Services\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function listForUser($userId, array $filters = [])
    {
        $query = Address::query()
            ->ownedBy($userId);

        // 🔍 Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $locale = app()->getLocale();

            $query->where(function ($q) use ($search, $locale) {
                $q->where("city->{$locale}", 'like', "%{$search}%")
                    ->orWhere("area->{$locale}", 'like', "%{$search}%")
                    ->orWhere("street->{$locale}", 'like', "%{$search}%");
            });
        }

        // 🏙 Filter city
        if (!empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        // 📍 Filter area
        if (!empty($filters['area'])) {
            $query->where('area', $filters['area']);
        }

        // ⭐ Filter by default
        if (!empty($filters['is_default'])) {
            $query->where('is_default', (bool) $filters['is_default']);
        }

        // 📌 Filter: has_coordinates
        if (!empty($filters['has_coordinates'])) {
            $query->whereNotNull('longitude')->whereNotNull('latitude');
        }


        // 🔃 Sorting
        $allowedSorts = ['city', 'area', 'latitude', 'longitude', 'created_at'];

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc');

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $query->orderBy($sortBy, $sortDir);

        // 📄 Pagination
        $perPage = isset($filters['per_page']) ? (int) $filters['per_page'] : 15;

        return $query->paginate($perPage)->withQueryString();
    }


    public function create(array $data, $userId)
    {
        return DB::transaction(function () use ($data, $userId) {

            // إذا is_default = true → لازم نحذف default السابق
            if (isset($data['is_default']) && $data['is_default']) {
                Address::ownedBy($userId)->update(['is_default' => false]);
            }

            return Address::create(array_merge($data, [
                'user_id' => $userId,
            ]));
        });
    }

    public function update(Address $address, array $data)
    {
        return DB::transaction(function () use ($address, $data) {

            if (isset($data['is_default']) && $data['is_default']) {
                Address::ownedBy($address->user_id)->update(['is_default' => false]);
            }

            $address->update($data);

            return $address->fresh();
        });
    }

    public function delete(Address $address)
    {
        return $address->delete();
    }
}
