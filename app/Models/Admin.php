<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable ,HasRoles ,SoftDeletes;

    
    protected $guard = 'admin';

    protected $fillable = [
        'email',
        'password',
        'super_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

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
