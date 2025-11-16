<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Translatable\HasTranslations;

class Address extends Model
{
    use HasTranslations ,HasFactory;

    protected $translatable = ['address_title', 'country', 'city', 'area' , 'street', 'building', 'apartment'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'street',
        'building',
        'apartment',
        'address_title',
        'phone',
        'longitude',
        'latitude',
        'is_default',
        'city',
        'postal_code',
        'country',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
