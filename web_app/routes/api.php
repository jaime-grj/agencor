<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use Illuminate\Routing\RouteUri;
use Spatie\FlareClient\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    
    Route::get('/events', [ApiController::class, 'getEvents']);
    Route::get('/events/featured', [ApiController::class, 'getFeaturedEvents']);
    Route::get('/events/{id}', [ApiController::class, 'getEventById']);
    Route::get('/events/category/{id}', [ApiController::class, 'getEventsByCategory']);
    Route::get('/categories', [ApiController::class, 'getCategories']);
    Route::get('/categories/{id}', [ApiController::class, 'getCategoryById']);

    Route::get('/search', [ApiController::class, 'getSearchResults']);
});
