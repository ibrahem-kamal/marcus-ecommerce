<?php

namespace App\Actions\Admin\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class GetAdminUserAction
{
    /**
     * Handle retrieving the authenticated admin user.
     *
     * @param Authenticatable $user The authenticated user
     * @return Authenticatable The authenticated user
     */
    public function handle(Authenticatable $user): Authenticatable
    {
        return $user;
    }
}