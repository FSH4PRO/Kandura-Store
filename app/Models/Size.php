<?php

namespace App\Models;

use App\Enums\Sizes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Size extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = [
        'code',
        'name',
        'sort_order',
    ];

    protected $casts = [
        'name' => 'array',
        'code' => Sizes::class, 
    ];

    /* ========== Relations ========== */

    public function designs()
    {
        return $this->belongsToMany(Design::class, 'design_size')
            ->withTimestamps();
    }

    /* ========== Scopes  ========== */

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
