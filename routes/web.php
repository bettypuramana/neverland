<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ExpenseController;


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


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
Route::get('/change-password',  [App\Http\Controllers\ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
Route::post('/change-password',  [App\Http\Controllers\ChangePasswordController::class, 'changePassword'])->name('password.update');
});


/// products

// routes/web.php
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// For autocomplete
Route::get('/products/autocomplete', [ProductController::class, 'autocomplete'])->name('products.autocomplete');
Route::get('/products/{id}/movements', [ProductController::class, 'movements'])->name('products.movements');

//expense Tracker
Route::get('/expense_tracker', [ExpenseController::class, 'index'])->name('expense');

Route::prefix('admin')->group(function () {
    Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
});
