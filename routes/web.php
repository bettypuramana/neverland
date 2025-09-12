<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\SettlementController;


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


Route::prefix('admin')->group(function () {
    // Categories (already there)
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

    // Expenses/Income
    Route::get('/expenses', [App\Http\Controllers\Admin\ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [App\Http\Controllers\Admin\CategoryController::class, 'storeexp'])->name('expenses.store');
    Route::delete('/expenses/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroyexp'])->name('expenses.destroy');
});

// SalesController
Route::get('/sales', [App\Http\Controllers\Admin\SalesController::class, 'index'])->name('admin.sales.index');
Route::get('/sale/new', [App\Http\Controllers\Admin\SalesController::class, 'sale_new'])->name('admin.sales.new');
Route::post('/sale/store', [App\Http\Controllers\Admin\SalesController::class, 'store'])->name('admin.sales.store');
Route::post('/rent-items-by-main-sale', [App\Http\Controllers\Admin\SalesController::class, 'rent_items_by_main_sale'])->name('admin.rent_items_by_main_sale');

// CustomerController
Route::get('/customer/search', [App\Http\Controllers\Admin\CustomerController::class, 'search'])->name('customer.search');

Route::post('/products/movements/store', [ProductController::class, 'storeMovement'])
    ->name('products.movements.store');


Route::prefix('settlements')->group(function () {
    Route::get('/', [SettlementController::class, 'index'])->name('settlements.index');
    Route::post('/', [SettlementController::class, 'store'])->name('settlements.store');
    Route::post('/settle', [SettlementController::class, 'settleDay'])->name('settlements.settle');
    Route::delete('/{id}', [SettlementController::class, 'destroy'])->name('settlements.destroy');
    Route::post('/settle', [SettlementController::class, 'settledDay'])->name('settlements.settle');

});
