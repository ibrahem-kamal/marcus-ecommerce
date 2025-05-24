<?php

use App\Actions\Admin\Auth\LoginAdminAction;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

test('it authenticates admin with valid credentials', function () {
    $admin = Admin::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
    ]);

    $credentials = [
        'email' => 'admin@example.com',
        'password' => 'password',
    ];

    $action = new LoginAdminAction();
    $result = $action->handle($credentials);

    expect($result)->toBeArray()
        ->toHaveKeys(['token', 'admin'])
        ->and($result['admin']->id)->toBe($admin->id)
        ->and($result['admin']->email)->toBe($admin->email);
});

test('it throws validation exception with invalid credentials', function () {
    $admin = Admin::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
    ]);

    $credentials = [
        'email' => 'admin@example.com',
        'password' => 'wrong-password',
    ];

    $action = new LoginAdminAction();

    expect(fn() => $action->handle($credentials))
        ->toThrow(ValidationException::class);
});
