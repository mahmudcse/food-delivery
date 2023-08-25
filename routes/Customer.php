<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;

Route::group([
    'prefix'     => 'customer',
    'as'         => 'customer.',
    'middleware' => ['auth'],
], function () {
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart/{product}/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('cart/{uuid}/remove', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
});
