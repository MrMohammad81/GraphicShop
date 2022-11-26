<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoriesController;

// Admin Routes
Route::prefix('admin')->group(function ()
{
   Route::prefix('categories')->group(function ()
   {
       Route::get('',[CategoriesController::class,'all'])->name('admin.categories.all');
       Route::get('create',[CategoriesController::class,'create'])->name('admin.categories.create');
       Route::post('',[CategoriesController::class , 'store'])->name('admin.categories.store');
   });
});
