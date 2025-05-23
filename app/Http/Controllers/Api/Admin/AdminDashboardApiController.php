<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\Request;

class AdminDashboardApiController extends Controller
{
    /**
     * Display the admin dashboard stats.
     */
    public function index()
    {
        $productCount = ProductType::count();
        
        return response()->json([
            'stats' => [
                'productCount' => $productCount,
            ]
        ]);
    }
}