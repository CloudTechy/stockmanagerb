<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes under the 'auth' prefix
Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::get('refresh', 'AuthController@refresh');
    Route::post('logout', 'AuthController@logout');
});


// Protected routes (require authentication)
Route::middleware('auth:api')->group(function () {
    Route::get('user', 'AuthController@user');
    Route::post('logout', 'AuthController@logout');

    Route::resource('orderdetails', 'OrderDetailController');
    Route::resource('purchasedetails', 'PurchaseDetailController');
    Route::resource('purchases', 'PurchaseController');
    Route::resource('attributeproducts', 'AttributeProductController');
    Route::resource('products', 'ProductController');
    Route::resource('attributes', 'AttributeController');
    Route::resource('types', 'TypeController');
    Route::resource('units', 'UnitController');
    Route::resource('banks', 'BankController');
    Route::resource('categories', 'CategoryController');
    Route::resource('sizes', 'SizeController');
    Route::resource('suppliers', 'SupplierController');
    Route::resource('orders', 'OrderController');
    Route::resource('customers', 'CustomerController');
    Route::resource('users', 'UserController');
    Route::resource('transactions', 'TransactionController');
    Route::resource('invoices', 'InvoiceController');
    Route::resource('announcements', 'AnnouncementController');
    Route::resource('bankdetails', 'BankDetailController');
    Route::resource('statuses', 'StatusController');

    Route::post('/products/image/{product}', 'ProductController@image');
    Route::get('/print/{invoice}/{user}', 'PrintController@prints');

    // Statistics routes
    Route::get('/statistics/products', 'StatisticController@product');
    Route::get('/statistics/orders', 'StatisticController@order');
    Route::get('/statistics/purchases', 'StatisticController@purchase');
    Route::get('/statistics/invoices', 'StatisticController@invoice');
    Route::get('/statistics/transactions', 'StatisticController@transaction');
    Route::get('/statistics/customers', 'StatisticController@customer');
    Route::get('/statistics/suppliers', 'StatisticController@supplier');
});
