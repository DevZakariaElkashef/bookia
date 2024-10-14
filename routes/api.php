<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\WishListController;

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



Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('forget_password', [AuthController::class, 'forgetPassword']);
Route::post('verify_code', [AuthController::class, 'verifyCode']);



Route::get('home', [HomeController::class, 'index']);
Route::get('search', [HomeController::class, 'search']);
Route::get('get_books_by_category_id/{id}', [HomeController::class, 'getByCategory']);


Route::get('books/{id}', [BookController::class, 'show']);


Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update_password', [AuthController::class, 'updatePassword']);

    Route::get('wishlists', [WishListController::class, 'index']);
    Route::get('toggle_wishlist/{id}', [WishListController::class, 'toggle']);


    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('update_profile', [ProfileController::class, 'update']);
    Route::post('reset_password', [ProfileController::class, 'updatePassword']);


    Route::get('cart', [CartController::class, 'index']);
    Route::post('add_to_cart', [CartController::class, 'store']);
    Route::post('remove_from_cart', [CartController::class, 'decrease']);
    Route::delete('delete_from_cart', [CartController::class, 'delete']);
    Route::post('check_coupon', [CartController::class, 'checkCoupon']);
    Route::post('calck_shipping', [CartController::class, 'calckShipping']);


    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::post('checkout', [OrderController::class, 'store']);


});
