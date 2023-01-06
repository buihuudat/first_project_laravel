<?php

use App\Http\Controllers\Admin\CustomerController as CustomerAdminController;
use App\Http\Controllers\Admin\MainController as MainAminController;
use App\Http\Controllers\Admin\MenuController as MenuAdminController;
use App\Http\Controllers\Admin\ProductController as ProductAdminController;
use App\Http\Controllers\Admin\SliderController as SliderAdminController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\users\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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

Route::get('admin/users/login', [LoginController::class, 'index'])->name('login');
Route::post('admin/users/login', [LoginController::class, 'store']);

Route::middleware(['auth'])->group(function () {
  Route::prefix('admin')->group(
    function () {
      Route::get('/', [MainAminController::class, 'index'])->name('admin');

      Route::prefix('menus')->group(
        function () {
          Route::get('add', [MenuAdminController::class, 'index']);
          Route::post('add', [MenuAdminController::class, 'store']);
          Route::get('list', [MenuAdminController::class, 'list']);
          Route::get('update/{menu}', [MenuAdminController::class, 'show']);
          Route::post('update/{id}', [MenuAdminController::class, 'update']);
          Route::delete('delete', [MenuAdminController::class, 'delete']);
        }
      );

      Route::prefix('products')->group(function () {
        Route::get('add', [ProductAdminController::class, 'create']);
        Route::post('add', [ProductAdminController::class, 'store']);
        Route::get('list', [ProductAdminController::class, 'gets']);
        Route::get('edit/{product}', [ProductAdminController::class, 'update']);
        Route::post('edit/{product}', [ProductAdminController::class, 'updated']);
        Route::delete('destroy', [ProductAdminController::class, 'delete']);
      });

      Route::prefix('sliders')->group(
        function () {
          Route::get('add', [SliderAdminController::class, 'create']);
          Route::post('add', [SliderAdminController::class, 'store']);
          Route::get('list', [SliderAdminController::class, 'list']);
          Route::get('edit/{slider}', [SliderAdminController::class, 'update']);
          Route::post('edit/{slider}', [SliderAdminController::class, 'updated']);
          Route::delete('destroy', [SliderAdminController::class, 'delete']);
        }
      );

      // upload
      Route::post('upload/services', [UploadController::class, 'upload']);
      Route::get('customers', [CustomerAdminController::class, 'index']);
    }
  );
});

Route::get('/', [MainController::class, 'index']);
Route::get('contact', [MainController::class, 'contact']);
Route::post('/services/load-product', [MainController::class, 'loadProduct']);

Route::get('danh-muc/{id}-{slug}.html', [MenuController::class, 'index']);
Route::get('san-pham/{id}-{slug}.html', [ProductController::class, 'index']);

Route::post('add-cart', [CartController::class, 'index']);
Route::get('carts', [CartController::class, 'cart']);
Route::post('carts', [CartController::class, 'addCart']);
