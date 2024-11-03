<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     *
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     *
     *
     * @param array $credentials
     * @return mixed
     */
    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            return Auth::user();
        }

        return null;
    }

    /**
     *
     *
     * @return void
     */
    public function logout($user): void
    {
        $user->currentAccessToken()->delete();
    }
}
