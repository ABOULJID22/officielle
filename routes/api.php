<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('products', [\App\Http\Controllers\Api\V1\ProductController::class, 'index']);
    Route::get('products/{id}', [\App\Http\Controllers\Api\V1\ProductController::class, 'show']);

    Route::get('categories', [\App\Http\Controllers\Api\V1\CategoryController::class, 'index']);
    Route::get('categories/{id}', [\App\Http\Controllers\Api\V1\CategoryController::class, 'show']);

    Route::get('purchases', [\App\Http\Controllers\Api\V1\PurchaseController::class, 'index']);
    Route::get('purchases/{id}', [\App\Http\Controllers\Api\V1\PurchaseController::class, 'show']);

    Route::get('trade-operations', [\App\Http\Controllers\Api\V1\TradeOperationController::class, 'index']);
    Route::get('trade-operations/{id}', [\App\Http\Controllers\Api\V1\TradeOperationController::class, 'show']);

    Route::get('commercials', [\App\Http\Controllers\Api\V1\CommercialController::class, 'index']);
    Route::get('commercials/{id}', [\App\Http\Controllers\Api\V1\CommercialController::class, 'show']);

    Route::get('contacts', [\App\Http\Controllers\Api\V1\ContactController::class, 'index']);
    Route::get('contacts/{id}', [\App\Http\Controllers\Api\V1\ContactController::class, 'show']);

    Route::get('posts', [\App\Http\Controllers\Api\V1\PostController::class, 'index']);
    Route::get('posts/{id}', [\App\Http\Controllers\Api\V1\PostController::class, 'show']);

    Route::get('users', [\App\Http\Controllers\Api\V1\UserController::class, 'index']);
    Route::get('users/{id}', [\App\Http\Controllers\Api\V1\UserController::class, 'show']);

    Route::get('site-settings', [\App\Http\Controllers\Api\V1\SiteSettingController::class, 'index']);
    Route::get('site-settings/{id}', [\App\Http\Controllers\Api\V1\SiteSettingController::class, 'show']);

    Route::get('events', [\App\Http\Controllers\Api\V1\EventController::class, 'index']);
    Route::get('events/{id}', [\App\Http\Controllers\Api\V1\EventController::class, 'show']);
});
    // Users
