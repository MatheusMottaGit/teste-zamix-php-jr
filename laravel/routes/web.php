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
    if(Auth::check()) {
        return redirect()->route('products.index');
    } else {
        return redirect()->route('login');
    }
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/funcionarios', [UserController::class, 'listAll'])->name('users.index'); // view 
Route::get('/funcionarios/{id}', [UserController::class, 'seeUserDetails'])->name('users.show'); // view
Route::get('/funcionarios/{id}/editar', [UserController::class, 'updateUserForm'])->name('users.edit'); // view
Route::put('/funcionarios/{id}', [UserController::class, 'updateUser'])->name('users.update'); // method
Route::delete('/funcionarios/{id}', [UserController::class, 'deleteUser'])->name('users.delete'); // methods

Route::get('/produtos', [ProductController::class, 'listAll'])->name('products.index'); // view
Route::post('/produtos', [ProductController::class, 'createProduct'])->name('products.register'); // method
Route::get('/criar-produto', [ProductController::class, 'createProductForm'])->name('products.create'); // view
Route::get('/produtos/{id}', [ProductController::class, 'seeProductDetails'])->name('products.show'); // view
Route::get('/produtos/{id}/editar', [ProductController::class, 'updateProductForm'])->name('products.edit'); // view
Route::put('/produtos/{id}', [ProductController::class, 'updateProduct'])->name('products.update'); // methods
Route::delete('/produtos/{id}', [ProductController::class, 'deleteProduct'])->name('products.delete'); // method