<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\Auth\GetAdminUserAction;
use App\Actions\Admin\Auth\LoginAdminAction;
use App\Actions\Admin\Auth\LogoutAdminAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginAdminRequest;
use Illuminate\Http\Request;

class AdminAuthApiController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(LoginAdminRequest $request, LoginAdminAction $action)
    {
        $result = $action->handle($request->validated());

        return response()->json($result);
    }

    /**
     * Get the authenticated admin user.
     */
    public function user(Request $request, GetAdminUserAction $action)
    {
        $user = $action->handle($request->user());

        return response()->json($user);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request, LogoutAdminAction $action)
    {
        $action->handle($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }
}
