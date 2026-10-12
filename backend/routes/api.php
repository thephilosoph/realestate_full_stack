<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Api\Admin\PropertyImageController;
use App\Http\Controllers\Api\Public\PropertyController as PublicPropertyController;

Route::prefix('v1')->group(function () {

    
    Route::get('properties', [PublicPropertyController::class, 'index']);
    Route::get('properties/{id}', [PublicPropertyController::class, 'show']);

    Route::prefix('admin')->middleware('auth:api')->group(function () {
        Route::apiResource('properties', AdminPropertyController::class);
        Route::delete('property-images/{id}', [PropertyImageController::class, 'destroy']);
        Route::post('property-images/{id}/primary', [PropertyImageController::class, 'setPrimary']);
    });
});
