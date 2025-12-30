<?php

namespace App\Models;

use App\Enums\DesignOptionsType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class DesignOption extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'type',
        'is_active',
    ];

    protected $casts = [
        'name'      => 'array',
        'is_active' => 'boolean',
        'type'      => DesignOptionsType::class, 
    ];

    /* ========== Relations ========== */

    public function selections()
    {
        return $this->hasMany(DesignOptionSelection::class, 'design_option_id');
    }

    public function designs()
    {
        return $this->belongsToMany(
            Design::class,
            'design_option_selections',
            'design_option_id',
            'design_id'
        );
    }

    /* ========== Scopes ========== */

    public function scopeType(Builder $query, DesignOptionsType|string|null $type): Builder
    {
        if (! $type) {
            return $query;
        }

        $value = $type instanceof DesignOptionsType ? $type->value : $type;

        return $query->where('type', $value);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
