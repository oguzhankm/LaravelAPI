<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Models\User;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::post('/upload', [\App\Http\Controllers\Api\UploadController::class, 'upload']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users/{id}', [UserController::class, 'update']);

    Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    Route::get('inside-mware', function () {
        return response()->json(['Success', 200]);
    });
});

Route::get('/hello', function () {
    return 'Hello RESTful API';
});
Route::get('/users', function () {
    return User::factory(10)->make();
});

//Route::apiResource('/products', \App\Http\Controllers\Api\ProductController::class);
//Route::apiResource('/users', \App\Http\Controllers\Api\UserController::class);
//Route::apiResource('/categories', \App\Http\Controllers\Api\CategoryController::class);

Route::get('categories/custom1', [CategoryController::class, 'custom1']);
Route::get('products/custom1', [ProductController::class, 'custom1']);
Route::get('products/custom2', [ProductController::class, 'custom2']);
Route::get('categories/report1', [CategoryController::class, 'report1']);
Route::get('users/custom1', [UserController::class, 'custom1']);
Route::get('products/custom3', [ProductController::class, 'custom3']);
Route::get('products/listWithCategories', [ProductController::class, 'listWithCategories']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'users' => 'App\Http\Controllers\Api\UserController',
        'products' => 'App\Http\Controllers\Api\ProductController',
        'categories' => 'App\Http\Controllers\Api\CategoryController'
    ]);
});


