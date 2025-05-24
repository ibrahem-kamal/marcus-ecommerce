<?php

use App\Http\Middleware\EnsureAdminAuthMiddleware;
use App\Models\Admin;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;

test('it allows authenticated admin to pass through', function () {
    $admin = Admin::factory()->create();
    
    Sanctum::actingAs($admin, ['*'], 'admin');
    
    $request = Request::create('/admin/dashboard', 'GET');
    
    $middleware = new EnsureAdminAuthMiddleware();
    
    $next = fn ($request) => response('OK', 200);
    
    $response = $middleware->handle($request, $next);
    
    expect($response->getStatusCode())->toBe(200)
        ->and($response->getContent())->toBe('OK');
});

test('it rejects unauthenticated users with 401 response', function () {
    auth()->forgetGuards();
    
    $request = Request::create('/admin/dashboard', 'GET');
    
    $middleware = new EnsureAdminAuthMiddleware();
    
    $next = fn ($request) => response('OK', 200);
    
    $response = $middleware->handle($request, $next);
    
    expect($response->getStatusCode())->toBe(401)
        ->and($response->getContent())->toContain('Unauthorized');
});