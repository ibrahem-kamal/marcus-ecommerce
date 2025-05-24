<?php

namespace App\Actions\Admin\Dashboard;

use App\Models\ProductType;

class GetDashboardStatsAction
{
    /**
     * Handle retrieving the dashboard statistics.
     *
     * @return array The dashboard statistics
     */
    public function handle(): array
    {
        $productCount = ProductType::query()->count();
        
        return [
            'stats' => [
                'productCount' => $productCount,
            ]
        ];
    }
}