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
}
