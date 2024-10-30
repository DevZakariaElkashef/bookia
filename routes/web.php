<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**start Slider Routes */
    Route::resource('sliders', SliderController::class);
    Route::get('search-sliders', [SliderController::class, 'search'])->name('sliders.search');
    Route::get('sliders-toggle-status/{slider}', [SliderController::class, 'toggleStatus'])->name('slider.toggleStatus');
    Route::delete('delete-sliders', [SliderController::class, 'delete'])->name('sliders.delete');
    /**end Slider Routes */

    /**start Slider Routes */
    Route::resource('categories', CategoryController::class);
    Route::get('search-categories', [CategoryController::class, 'search'])->name('categories.search');
    Route::get('categories-toggle-status/{category}', [CategoryController::class, 'toggleStatus'])->name('category.toggleStatus');
    Route::delete('delete-categories', [CategoryController::class, 'delete'])->name('categories.delete');
    /**end Slider Routes */

    /**start Slider Routes */
    Route::resource('books', BookController::class);
    Route::get('search-books', [BookController::class, 'search'])->name('books.search');
    Route::get('books-toggle-status/{book}', [BookController::class, 'toggleStatus'])->name('book.toggleStatus');
    Route::delete('delete-books', [BookController::class, 'delete'])->name('books.delete');
    /**end Slider Routes */

    /**start Slider Routes */
    Route::resource('orders', controller: OrderController::class);
    Route::get('search-orders', [OrderController::class, 'search'])->name('orders.search');
    Route::get('orders-toggle-status/{product}', [OrderController::class, 'toggleStatus'])->name('orders.toggleStatus');
    Route::get('orders-export', [OrderController::class, 'export'])->name('orders.export');
    Route::post('orders-update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders-update-payment', [OrderController::class, 'updatePayment'])->name('orders.updatePayment');
    Route::delete('delete-orders', [OrderController::class, 'delete'])->name('orders.delete');
    /**end Slider Routes */

    /**start Slider Routes */
    Route::resource('orderitems', OrderItemController::class);
    /**end Slider Routes */

});

require __DIR__ . '/auth.php';
