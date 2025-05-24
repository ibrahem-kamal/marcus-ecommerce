<?php

use App\Actions\Admin\Auth\LogoutAdminAction;
use App\Models\Admin;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

test('it deletes the current access token', function () {
    $admin = Admin::factory()->create();

    $tokenResult = $admin->createToken('test-token');
    $tokenId = $tokenResult->accessToken->id;

    $admin->withAccessToken($tokenResult->accessToken);

    expect(PersonalAccessToken::find($tokenId))->not->toBeNull();

    $action = new LogoutAdminAction();

    $action->handle($admin);

    expect(PersonalAccessToken::find($tokenId))->toBeNull();
});
