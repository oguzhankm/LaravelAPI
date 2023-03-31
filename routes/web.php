<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::prefix('basics')->group(function () {

    Route::get('/merhaba', function () {
        return "Merhaba API";
    });

    Route::get('merhaba-json', function () {
        return ['message' => 'Merhaba Api'];
    });

    Route::get('merhaba-json2', function () {
        return response(['message' => 'Merhaba Api'], 200)
            ->header('Content-Type', 'application/json');
    });
    Route::get('/merhaba-json3', function () {
        return response()->json(['message' => 'Merhaba Api']);
    });

    Route::get('/product/{id}/{type?}', function ($id, $type = 'test') {
        return "Product ID: $id, Type: $type";
    });

    Route::get('/category/{slug}', function ($slug) {
        return "Category Slug: $slug";
    })->name('category-show');

});

Route::middleware('auth')->get('/secured', function (){
   return 'You\'re authenticated!';
});

//Route::get('/product/{id}/{type?}', [ProductController::class, 'show'])->name('product.show');

//Route::resource('/products', 'App\Http\Controllers\ProductController');
//Route::resource('/users', 'App\Http\Controllers\Api\UserController');
//Route::resource('/products', ProductController::class)->only('index', 'show');
//Route::resource('/products', ProductController::class)->except('destroy');

//Üstteki ve alttaki aynı


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/upload', [App\Http\Controllers\HomeController::class, 'upload_form'])->name('upload_form');
