<?php

namespace App\Actions\Admin\Auth;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
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
        if (!Auth::guard('admin')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $admin = Admin::where('email', $credentials['email'])->first();
        $token = $admin->createToken('admin-token')->plainTextToken;
        
        return [
            'token' => $token,
            'admin' => $admin,
        ];
    }
}