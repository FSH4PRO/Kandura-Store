<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $guard_name = 'admin';

    protected $fillable = [
        'email',
        'password',
        'super_admin',
        'fcm_token',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function routeNotificationForFcm()
    {
        return $this->fcm_token? $this->fcm_token : null;
    }

    protected function casts(): array
    {
        return [
            'password'    => 'hashed',
            'super_admin' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->morphOne(User::class, 'usable');
    }
}
