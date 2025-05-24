<?php

use App\Models\Admin;
use Laravel\Sanctum\Sanctum;

test('admin can login with valid credentials', function () {
    $admin = Admin::factory()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson(route('admin.login'), [
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'token',
            'admin' => [
                'id',
                'name',
                'email',
            ]
        ]);
});

test('admin cannot login with invalid credentials', function () {
    $admin = Admin::factory()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson(route('admin.login'), [
        'email' => 'admin@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422);
});

test('admin can logout', function () {
    $admin = Admin::factory()->create();
    Sanctum::actingAs($admin, ['*']);

    $response = $this->postJson(route('admin.logout'));

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Logged out successfully'
        ]);
});

test('admin can get user information', function () {
    $admin = Admin::factory()->create();
    Sanctum::actingAs($admin, ['*'], 'admin');

    $response = $this->getJson(route('admin.user'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'email',
        ])
        ->assertJson([
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
        ]);
});

test('unauthenticated admin cannot access protected routes', function () {
    $this->getJson(route('admin.user'))->assertStatus(401);
    $this->postJson(route('admin.logout'))->assertStatus(401);
});
