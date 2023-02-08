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
Route::group([
    'middleware' => 'api',
    'prefix' => 'categories'

], function () {
   
    Route::get('/all', [CategoryController::class,'index']);
    Route::get('/{id}', [CategoryController::class,'show']);
    Route::delete('/delete-id={id}', [CategoryController::class,'destroy']);
    Route::post('/store', [CategoryController::class,'store']);
    route::post('/update-id={id}', [CategoryController::class,'update']);
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'products'

], function () {
   
    Route::get('/', [ProductController::class,'index']);
    Route::get('/{id}', [ProductController::class,'show']);
    Route::delete('/delete-id={id}', [ProductController::class,'destroy']);
    Route::post('/store', [ProductController::class,'store']);
    route::post('/update-id={id}', [ProductController::class,'update']);
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'payments'

], function () {
   
    Route::get('/', [PaymentController::class,'show']);
    Route::delete('/delete-id={productID}', [PaymentController::class,'destroy']);
    Route::post('/store', [PaymentController::class,'store']);
    route::post('/update-id={productID}', [PaymentController::class,'update']);
});