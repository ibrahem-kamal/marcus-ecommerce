<?php

namespace App\Actions\Admin\Auth;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginAdminAction
{
    /**
     * Handle the admin login action.
     *
     * @param array $credentials The validated credentials
     * @return array The token and admin data
     * @throws ValidationException If credentials are invalid
     */
    public function handle(array $credentials): array
    {
        $admin = Admin::where('email', $credentials['email'])->first();
        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;
        
        return [
            'token' => $token,
            'admin' => $admin,
        ];
    }
}