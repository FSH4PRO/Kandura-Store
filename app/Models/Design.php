<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\DesignOption;
use App\Models\DesignOptionSelection;
use App\Models\Size;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Design extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasTranslations;

    protected $fillable = [
        'customer_id',
        'name',
        'description',
        'price',
    ];

    public array $translatable = ['name', 'description'];

    protected $casts = [
        'price'       => 'decimal:2',
        'name'        => 'array',      
        'description' => 'array',
    ];

    /* ========== Relations ========== */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'design_size', 'design_id', 'size_id')->withTimestamps();
    }

    public function optionSelections()
    {
        return $this->hasMany(DesignOptionSelection::class);
    }

    public function options()
    {
        return $this->belongsToMany(
            DesignOption::class,
            'design_option_selections',
            'design_id',
            'design_option_id',
        );
    }

    /* ========== Media ========== */

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->acceptsFile(function ($file) {
                return in_array($file->mimeType, ['image/jpeg', 'image/png', 'image/webp']);
            });
    }

    public function registerMediaConversions($media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(400)
            ->performOnCollections('images');
    }

    public function getFirstImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('images', 'thumb') ?: null;
    }

    /* ========== Scopes: search & filters ========== */

    // Search by name or description
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        $locale = app()->getLocale();

        return $query->where(function ($q) use ($locale, $search) {
            $q->where("name->{$locale}", 'like', "%{$search}%")
                ->orWhere("description->{$locale}", 'like', "%{$search}%");
        });
    }

    // Filter by size
    public function scopeFilterSize(Builder $query, ?int $sizeId): Builder
    {
        if (! $sizeId) {
            return $query;
        }

        return $query->whereHas('sizes', fn($q) => $q->where('sizes.id', $sizeId));
    }

    // Filter by price range
    public function scopeFilterPrice(Builder $query, ?float $min, ?float $max): Builder
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }

        if ($max !== null) {
            $query->where('price', '<=', $max);
        }

        return $query;
    }

    // Filter by design option
    public function scopeFilterOption(Builder $query, ?int $optionId): Builder
    {
        if (! $optionId) {
            return $query;
        }

        return $query->whereHas('options', fn($q) => $q->where('design_options.id', $optionId));
    }

    // For "my designs" (user side)
    public function scopeOwnedByCustomer(Builder $query, Customer $customer): Builder
    {
        return $query->where('customer_id', $customer->id);
    }
}
