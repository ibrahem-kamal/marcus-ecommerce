<?php

use App\Actions\Admin\Auth\GetAdminUserAction;
use App\Models\Admin;

test('it returns the authenticated user', function () {
    $admin = Admin::factory()->create();

    $action = new GetAdminUserAction();

    $result = $action->handle($admin);

    expect($result)->toBe($admin)
        ->and($result->id)->toBe($admin->id);
});
