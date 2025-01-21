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

// 商品一覧ページを表示するためのルート
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// 特定の商品詳細ページを表示するためのルート
Route::get('/products/{productId}', [ProductController::class, 'show'])->name('products.show');

// 新しい商品を登録するための入力フォームページを表示するルート
Route::get('/products/register', [ProductController::class, 'create'])->name('products.create');

// 入力フォームから送られてきた新しい商品のデータを保存するルート
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// 既存の商品を編集するための入力フォームページを表示するルート
Route::get('/products/{productId}/update', [ProductController::class, 'edit'])->name('products.edit');

// 入力フォームから送られてきた編集済みの商品データを更新するルート
Route::put('/products/{productId}', [ProductController::class, 'update'])->name('products.update');

// 商品名を使って商品を検索し、その結果を表示するためのルート
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// 特定の商品を削除するためのルート
Route::delete('/products/{productId}/delete', [ProductController::class, 'destroy'])->name('products.destroy');
