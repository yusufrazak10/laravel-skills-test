<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/product-entry', [ProductController::class, 'showForm']);
    
Route::post('/product-entry', [ProductController::class, 'store'])->name('product.store');
    
