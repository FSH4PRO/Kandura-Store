<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'city_id',
        'street',
        'latitude',
        'longitude',
        'details',
    ];

    /**
     * Relations
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * SCOPES
     */

    // 🔍 بحث
    public function scopeSearch($query, $term)
    {
        if (!$term) return $query;

        $locale = app()->getLocale();

        return $query->whereHas('city', function ($q) use ($term, $locale) {
            $q->where("name->{$locale}", 'like', "%{$term}%");
        })
            ->orWhere('street', 'like', "%{$term}%")
            ->orWhere('details', 'like', "%{$term}%");
    }

    // 📍 فلترة
    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        if (!empty($filters['has_coordinates'])) {
            $query->whereNotNull('latitude')->whereNotNull('longitude');
        }

        return $query;
    }

    // ↕ ترتيب
    public function scopeSort($query, $sortBy = null, $direction = 'desc')
    {
        $allowed = ['street', 'latitude', 'longitude', 'created_at'];

        if (!in_array($sortBy, $allowed)) {
            $sortBy = 'created_at';
        }

        $direction = strtolower($direction);
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        return $query->orderBy($sortBy, $direction);
    }

    // 🧑‍💼 خاص بالمستخدم
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('customer_id', $userId);
    }
}
