<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\Dashboard\GetDashboardStatsAction;
use App\Http\Controllers\Controller;

class AdminDashboardApiController extends Controller
{
    /**
     * Display the admin dashboard stats.
     */
    public function index(GetDashboardStatsAction $action)
    {
        $stats = $action->handle();

        return response()->json($stats);
    }
}
