<?php

use App\Models\Admin;
use App\Models\ProductType;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $admin = Admin::factory()->create();
    Sanctum::actingAs($admin, ['*']);
});

test('it returns dashboard statistics', function () {
    ProductType::factory()->count(3)->create();

    $response = $this->getJson(route('admin.dashboard'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'stats' => [
                'productCount'
            ]
        ])
        ->assertJson([
            'stats' => [
                'productCount' => 3
            ]
        ]);
});

test('it requires authentication', function () {
    auth()->forgetGuards();

    $response = $this->getJson(route('admin.dashboard'));

    $response->assertStatus(401);
});
