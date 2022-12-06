<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Home\ProductsController as HomeProductsController;
use App\Http\Controllers\Home\BasketController;
use App\Http\Controllers\Home\CheckOutController;


// Admin Routes
Route::prefix('admin')->group(function ()
{
   Route::prefix('categories')->group(function ()
   {
       // categories route
       Route::get('',[CategoriesController::class,'all'])->name('admin.categories.all');
       Route::get('create',[CategoriesController::class,'create'])->name('admin.categories.create');
       Route::post('',[CategoriesController::class , 'store'])->name('admin.categories.store');
       Route::delete('{category_id}/delete',[CategoriesController::class , 'delete'])->name('admin.categories.delete');
       Route::get('{category_id}/edit' , [CategoriesController::class , 'edit'])->name('admin.categories.edit');
       Route::put('{category_id}/update' , [CategoriesController::class , 'update'])->name('admin.categories.update');
   });

   // products route
    Route::prefix('products')->group(function ()
    {
       Route::get('create',[ProductsController::class , 'create'])->name('admin.products.create');
       Route::post('',[ProductsController::class , 'store'])->name('admin.products.store');
       Route::get('all',[ProductsController::class , 'all'])->name('admin.products.all');

       Route::get('{product_id}/downlaod/demoImage',[ProductsController::class , 'downlaodDemo'])->name('admin.products.downlaod.demoImage');
       Route::get('{product_id}/downlaod/sourceImage',[ProductsController::class , 'downloadSource'])->name('admin.products.downlaod.demo.sourceImage');
       Route::delete('{product_id}/delete',[ProductsController::class , 'delete'])->name('admin.products.delete');
       Route::get('{product_id}/edit',[ProductsController::class , 'edit'])->name('admin.products.edit');
       Route::put('{product_id}/update' , [ProductsController::class,'update'])->name('admin.products.update');
    });

    // users routes
    Route::prefix('users')->group(function ()
    {
        Route::get('create',[UsersController::class , 'create'])->name('admin.users.create');
        Route::post('',[UsersController::class,'store'])->name('admin.users.store');
        Route::get('',[UsersController::class , 'all'])->name('admin.users.all');
        Route::delete('{user_id}/delete',[UsersController::class,'delete'])->name('admin.users.delete');
        Route::get('{user_id}/edit',[UsersController::class,'edit'])->name('admin.users.edit');
        Route::put('{user_id}/update' , [UsersController::class,'update'])->name('admin.users.update');
    });

    // orders routs
    Route::prefix('orders')->group(function ()
    {
        Route::get('',[OrdersController::class , 'all'])->name('admin.orders.all');
    });

    // payments route
    Route::prefix('payments')->group(function ()
    {
       Route::get('',[PaymentsController::class,'all'])->name('admin.payments.all');
    });
});

// Home Routes
Route::prefix('')->group(function ()
{
   Route::get('',[HomeProductsController::class , 'index'])->name('home.products.all');
   Route::get('{product_id}/show',[HomeProductsController::class , 'show'])->name('home.products.show');
   Route::get('{product_id}/addToBasket',[BasketController::class , 'addToBasket'])->name('home.products.addToBasket');
   Route::get('{product_id}/removeFromBasket' , [BasketController::class , 'removeFromBasket'])->name('home.remove.basket');
   Route::get('checkout',[CheckOutController::class , 'show'])->name('home.checkout.show');
});
