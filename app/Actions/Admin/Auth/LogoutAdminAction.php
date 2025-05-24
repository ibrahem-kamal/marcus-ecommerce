<?php

namespace App\Actions\Admin\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class LogoutAdminAction
{
    /**
     * Handle the admin logout action.
     *
     * @param Authenticatable $user The authenticated user
     * @return void
     */
    public function handle(Authenticatable $user): void
    {
        $user->currentAccessToken()->delete();
    }
}