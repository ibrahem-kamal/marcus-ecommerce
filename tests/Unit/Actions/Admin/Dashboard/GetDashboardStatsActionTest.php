<?php

use App\Actions\Admin\Dashboard\GetDashboardStatsAction;
use App\Models\ProductType;

test('it returns dashboard statistics with product count', function () {
    ProductType::factory()->count(5)->create();

    $action = new GetDashboardStatsAction();
    $result = $action->handle();

    expect($result)->toBeArray()
        ->toHaveKey('stats')
        ->and($result['stats'])->toBeArray()
        ->toHaveKey('productCount')
        ->and($result['stats']['productCount'])->toBe(5);
});
