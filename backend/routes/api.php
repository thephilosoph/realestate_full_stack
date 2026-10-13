<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Api\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Api\Admin\FeatureController as AdminFeatureController;
use App\Http\Controllers\Api\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Api\Admin\PropertyImageController;
use App\Http\Controllers\Api\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Api\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Api\Public\ClientController as PublicClientController;
use App\Http\Controllers\Api\Public\FaqController as PublicFaqController;
use App\Http\Controllers\Api\Public\PropertyController as PublicPropertyController;
use App\Http\Controllers\Api\Public\TeamMemberController as PublicTeamMemberController;
use App\Http\Controllers\Api\Public\TestimonialController as PublicTestimonialController;
use Illuminate\Support\Facades\Route;

// Non-numeric ids can never reach a controller: the route simply 404s.
// This blocks type-juggling and SQL-injection probes on route parameters.
Route::pattern('id', '[0-9]+');

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Public
    |--------------------------------------------------------------------------
    */
    Route::get('properties', [PublicPropertyController::class, 'index']);
    Route::get('properties/{id}', [PublicPropertyController::class, 'show']);

    Route::get('faqs', [PublicFaqController::class, 'index']);
    Route::get('testimonials', [PublicTestimonialController::class, 'index']);
    Route::get('clients', [PublicClientController::class, 'index']);
    Route::get('team-members', [PublicTeamMemberController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | Admin auth (no token)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin protected
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')
        ->middleware('auth:sanctum')
        ->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('logout-all', [AuthController::class, 'logoutAll']);
            Route::get('me', [AuthController::class, 'me']);

            Route::apiResource('properties', AdminPropertyController::class);
            Route::delete('property-images/{id}', [PropertyImageController::class, 'destroy']);
            Route::post('property-images/{id}/primary', [PropertyImageController::class, 'setPrimary']);

            Route::apiResource('features', AdminFeatureController::class);
            Route::apiResource('faqs', AdminFaqController::class);
            Route::apiResource('testimonials', AdminTestimonialController::class);
            Route::apiResource('clients', AdminClientController::class);
            Route::apiResource('team-members', AdminTeamMemberController::class);
        });
});