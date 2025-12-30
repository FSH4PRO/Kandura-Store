<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model implements HasMedia
{
    use HasFactory,
        Notifiable,
        InteractsWithMedia,
        SoftDeletes,
        HasTranslations;

    protected $translatable = ['name'];

    protected $fillable = [
        'name',
        'is_active',
        'usable_id',
        'usable_type',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /* ===================== Media ===================== */

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_image')
            ->singleFile()
            ->useDisk('public');
    }

    public function registerMediaConversions($media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->performOnCollections('profile_image');
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('profile_image') ?: asset('images/default-avatar.png');
    }

    /* ===================== Scopes ===================== */

   

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name->en', 'like', "%{$search}%")
                ->orWhere('name->ar', 'like', "%{$search}%");
        });
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if (empty($status) || ! in_array($status, ['active', 'inactive'])) {
            return $query;
        }

        $isActive = $status === 'active';

        return $query->where('is_active', $isActive);
    }

    public function scopeSort(Builder $query, ?string $sortBy, ?string $sortDir): Builder
    {
        $allowedSorts = ['id', 'name', 'created_at'];

        $sortBy  = $sortBy ?: 'created_at';
        $sortDir = strtolower($sortDir ?: 'desc');

        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        if (! in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        return $query->orderBy($sortBy, $sortDir);
    }

    /* ===================== relations ===================== */


    public function usable()
    {
        return $this->morphTo();
    }
}
