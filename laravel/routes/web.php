<?php

use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/usuarios', [UserController::class, 'listAll'])->name('users.index');
Route::get('/usuarios/{id}', [UserController::class, 'seeUserDetails'])->name('users.show');
Route::put('/usuarios/{id}', [UserController::class, 'updateUser'])->name('users.update');
Route::delete('/usuarios/{id}', [UserController::class, 'deleteUser'])->name('users.delete');

Route::get('/produtos', [ProductController::class, 'listAll'])->name('products.index');
Route::post('/produtos', [ProductController::class, 'createProduct'])->name('products.register'); // method
Route::get('/criar-produto', [ProductController::class, 'createProductForm'])->name('products.create'); // view
Route::get('/produtos/{id}', [ProductController::class, 'seeProductDetails'])->name('products.show');
Route::put('/produtos/{id}', [ProductController::class, 'updateProduct'])->name('products.update');
Route::delete('/produtos/{id}', [ProductController::class, 'deleteProduct'])->name('products.delete');