<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

// 商品一覧ページを表示
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// 商品詳細ページを表示
Route::get('/products/{productId}', [ProductController::class, 'show'])->name('products.show');

// 新規商品の登録フォームを表示
Route::get('/products/register', [ProductController::class, 'create'])->name('products.create');

// 新規商品を保存
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// 商品を更新
Route::put('/products/{productId}/update', [ProductController::class, 'update'])->name('products.update');

// 商品を検索
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// 商品を削除
Route::delete('/products/{productId}/delete', [ProductController::class, 'destroy'])->name('products.destroy');
