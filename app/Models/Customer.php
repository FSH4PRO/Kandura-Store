<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;


    protected $guard = 'customer';

    protected $fillable = [
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


    public function user()
    {
        return $this->morphOne(User::class, 'usable');
    }


    public function addresses()
    {
        return $this->hasMany(Address::class, 'customer_id');
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class, 'customer_id')->where('is_default', true);
    }

    public function designs()
    {
        return $this->hasMany(Design::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    // NOTE: this used to have a custom notifications() override here that
    // shadowed the correct Notifiable trait method — it treated the
    // Illuminate\Support\Facades\Notification FACADE as if it were the
    // DatabaseNotification model, and assumed a customer_id column that
    // doesn't exist on the polymorphic notifications table. It crashed
    // every single time a customer notification was actually sent (see
    // NOTIFICATION_SYSTEM.md). Removed — Notifiable already provides the
    // correct morphMany relationship.

 

    public function getWalletOrCreate(): Wallet
    {
        return $this->wallet()->firstOrCreate(
            ['customer_id' => $this->id],
            ['balance' => 0, 'is_active' => true]
        );
    }
}
