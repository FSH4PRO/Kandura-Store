<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\MediaLibrary\HasMedia;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, InteractsWithMedia, SoftDeletes, HasTranslations, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getDefaultGuardName(): string
    {
        // دايمًا خلّي Spatie يستخدم guard 'web' للأدوار والصلاحيات
        return 'web';
    }


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

    public function scopeHasRoleName($query, $role)
    {
        return $query->whereHas('roles', fn($q) => $q->where('name', $role));
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }
}
