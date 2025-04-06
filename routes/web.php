<?php

use App\Http\Controllers\dashboard\permissionsController;
use App\Http\Controllers\dashboard\productsController;
use App\Http\Controllers\dashboard\RoleController;
use App\Http\Controllers\dashboard\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');







    //* permissions routes for showlist and create one
    Route::get('/permissions', [permissionsController::class, 'index'])->name('permission.index');
    Route::get('/permissions/create', [permissionsController::class, 'create'])->name('permission.create');
    Route::post('/permissions', [permissionsController::class, 'store'])->name('permission.store');

    //* permissions routes for edit and update 
    Route::get('/permissions/{id}/edit', [permissionsController::class, 'edit'])->name('permission.edit');
    Route::post('/permissions/{id}', [permissionsController::class, 'update'])->name('permission.update');

    //* permissions routes for destroy
    Route::delete('/permissions', [permissionsController::class, 'destroy'])->name('permission.destroy');




    //* roles routes for showlist and create one
    Route::get('/roles', [RoleController::class, 'index'])->name('role.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('role.store');

    //* Roles routes for edit and update 
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::post('/roles/{id}', [RoleController::class, 'update'])->name('role.update');

    //* Roles routes for destroy
    Route::delete('/roles', [RoleController::class, 'destroy'])->name('role.destroy');





    //* Users routes for showlist and create one
    Route::get('/Users', [UserController::class, 'index'])->name('user.index');
    Route::get('/Users/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/Users', [UserController::class, 'store'])->name('user.store');

    //* Users routes for edit and update 
    Route::get('/Users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/Users/{id}', [UserController::class, 'update'])->name('user.update');

    //* Users routes for destroy
    Route::delete('/Users', [UserController::class, 'destroy'])->name('user.destroy');


    //* products routes for showlist and create one
    Route::get('/products', [productsController::class, 'index'])->name('product.index');
    Route::get('/products/create', [productsController::class, 'create'])->name('product.create');
    Route::post('/products', [productsController::class, 'store'])->name('product.store');

    //* products routes for edit and update 
    Route::get('/products/{id}/edit', [productsController::class, 'edit'])->name('product.edit');
    Route::post('/products/{id}', [productsController::class, 'update'])->name('product.update');

    //* products routes for destroy
    Route::delete('/products', [productsController::class, 'destroy'])->name('product.destroy');
});

require __DIR__ . '/auth.php';
