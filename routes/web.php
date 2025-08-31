<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanySearchController;
use App\Http\Controllers\CartController;

// Home page - redirect to search
Route::get('/', function () {
    return redirect()->route('companies.search');
});

// Company search routes
Route::get('/search', [CompanySearchController::class, 'index'])->name('companies.search');
Route::get('/search/results', [CompanySearchController::class, 'search'])->name('companies.search.results');
        Route::get('/company/{country}/{id}', [CompanySearchController::class, 'show'])->name('companies.show');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/{itemId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');


