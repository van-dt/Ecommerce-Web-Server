<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// auth
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/change-pass', [AuthController::class, 'changePassWord']);
});
// upload image
Route::post('/upload', [ImageController::class,'postUpload']);
// categories
Route::resource('/categories',CategoryController::class)->except(['create','edit']);
// products
Route::resource('/products',ProductController::class)->except(['create','edit']);
// payments (payments crud la cart thong thuong, con show du thong tin payments se khac nha)
Route::resource('/payments',PaymentController::class)->except(['create','edit']);
//count cart
Route::get('load-cart-data',[PaymentController::class,'cartcount']);
// ajax cho cart neu can:
// $.ajax(
//     {
//         method:"GET",
//         url:"/load-cart-data",
//         success:function(response)
//         {
//             $('.ten class').html();
//             $('.ten class').html(response.count);
            
//         }

//     }
// );

//get products theo category
Route::get('/products-by-cate',[ProductController::class,'suggestProdByCate']);
Route::get('/products-by-user',[ProductController::class,'suggestProdByUser']);
//get checkout (thong tin mua hang)
Route::get('/checkout',[PaymentController::class,'checkout']);
Route::post('/purchase',[PaymentController::class,'purchase']);
/// search product by name
Route::get('/search/{keyword}',[ProductController::class,'searchProducts']);
