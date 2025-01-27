<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ParticipantRoleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductCategoryTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResellerOrderController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);


Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('customers', CustomerController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('participants', ParticipantController::class);
    Route::resource('participant_roles', ParticipantRoleController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('reseller_orders', ResellerOrderController::class);
    Route::resource('product_categories', ProductCategoryController::class);
    Route::resource('product_category_types', ProductCategoryTypeController::class);
    Route::resource('order_statuses', OrderStatusController::class);
});

Route::get('api/product_category_types/{category}', [ProductCategoryTypeController::class, 'showType']);
Route::get('api/product/{id}', [ProductController::class, 'getProductById']);
Route::get('api/order_statuses/{enum_type}', [OrderStatusController::class, 'getOrderStatus']);
//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');



Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
