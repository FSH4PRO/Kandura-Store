<?php

namespace App\Services\Global;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function register(array $data): Customer
    {
        return DB::transaction(function () use ($data) {

            $customer = Customer::create([
                'phone'    => $data['phone'],
                'password' => bcrypt($data['password']),
            ]);

            $user = User::create([
                'name'        => $data['name'],
                'is_active'   => true,
                'usable_id'   => $customer->id,
                'usable_type' => Customer::class,
            ]);

            $customer->setRelation('user', $user);

            return $customer;
        });
    }


    public function login(array $credentials): ?Customer
    {
        return DB::transaction(function () use ($credentials) {

            $phone    = $credentials['phone'] ?? null;
            $password = $credentials['password'] ?? null;

            if (! $phone || ! $password) {
                return null;
            }

            $customer = Customer::where('phone', $phone)->first();

            if (! $customer || ! Hash::check($password, $customer->password)) {
                return null;
            }

            $user = $customer->user;


            if ($user && ! $user->is_active) {
                return null;
            }

            $customer->setRelation('user', $user);

            return $customer;
        });
    }
}
