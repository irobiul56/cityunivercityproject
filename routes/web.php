<?php

use App\Http\Controllers\FronendController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\PosController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\CutomerController;
use App\Http\Controllers\Depertment\DepertmentController;
use App\Http\Controllers\Inventory\InventoryTrackingController;
use App\Http\Controllers\Inventory\RoomController;
use App\Http\Controllers\Inventory\StorageController;
use App\Http\Controllers\Inventory\Vendor;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/about', [FronendController::class, 'about']) -> name('about');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    Route::get('/', [FronendController::class, 'index']) -> name('home');
    
    Route::resource('inventoryTracking', InventoryTrackingController::class);
    Route::resource('customer', CutomerController::class);

    //Product Category
    Route::resource('category', CategoryController::class);

    //Product
    Route::resource('product', ProductController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //User/Role/Permission
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::resource('user', UserController::class);

    //Depertment
    Route::resource('department', DepertmentController::class);

    //Rome
    Route::resource('room', RoomController::class);

    //Rome
    Route::resource('storages', StorageController::class);

    //Rome
    Route::resource('vendor', Vendor::class);
});

require __DIR__.'/auth.php';
