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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); 

Route::prefix('auth')->group(function () {
// Below mention routes are public, user can access those without any restriction.
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::get('refresh', 'AuthController@refresh');
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
});
Route::middleware('auth:api')->group(function () {
    Route::get('user', 'AuthController@user');
    Route::post('logout', 'AuthController@logout');
    Route::resource('users', 'UserController');
    Route::resource('transactions', 'TransactionController');
    Route::resource('invoices', 'InvoiceController');
    
    Route::resource('orders', 'OrderController');
    
    
    
    Route::resource('suppliers', 'SupplierController');
    
    Route::resource('statuses', 'StatusController');
    
    Route::resource('announcements', 'AnnouncementController');
    Route::resource('bankdetails', 'BankDetailController');
    
    Route::resource('customers', 'CustomerController');
  
    Route::post('/products/image/{product}', 'ProductController@image');
    Route::get('/print/{invoice}/{user}', 'PrintController@prints');

    // /*
    // |--------------------------------------------------------------------------
    // | API Routes for statistics resource goes below
    // |--------------------------------------------------------------------------
    //  */
    Route::get('/statistics/products', 'StatisticController@product');
    Route::get('/statistics/orders', 'StatisticController@order');
    Route::get('/statistics/purchases', 'StatisticController@purchase');
    Route::get('/statistics/invoices', 'StatisticController@invoice');
    Route::get('/statistics/transactions', 'StatisticController@transaction');
    Route::get('/statistics/customers', 'StatisticController@customer');
    Route::get('/statistics/suppliers', 'StatisticController@supplier');

});

// /*
// |--------------------------------------------------------------------------
// | API Routes for statistics resource goes below
// |--------------------------------------------------------------------------
//  */
// //Route::get('/announcements', 'AnnouncementController@index');
// Route::post('/announcements', 'AnnouncementController@store');
// Route::get('/announcements/{announcement}', 'AnnouncementController@show');
// Route::patch('/announcements/{announcement}', 'AnnouncementController@update');
// Route::delete('/announcements/{announcement}', 'AnnouncementController@destroy');

// /*
// |--------------------------------------------------------------------------
// | API Routes for Banks resource goes below
// |--------------------------------------------------------------------------
//  */
// Route::get('/banks', 'BankController@index');
// Route::post('/banks', 'BankController@store');
// Route::get('/banks/{bank}', 'BankController@show');
// Route::patch('/banks/{bank}', 'BankController@update');
// Route::delete('/banks/{bank}', 'BankController@destroy');

// /*
// |--------------------------------------------------------------------------
// | API Routes for Categories resource goes below
// |--------------------------------------------------------------------------
//  */
// Route::get('/categories', 'CategoryController@index');
// Route::post('/categories', 'CategoryController@store');
// Route::get('/categories/{category}', 'CategoryController@show');
// Route::patch('/categories/{category}', 'CategoryController@update');
// Route::delete('/categories/{category}', 'CategoryController@destroy');

// /*
// |--------------------------------------------------------------------------
// | API Routes for Customers resource goes below
// |--------------------------------------------------------------------------
//  */
// Route::get('/customers', 'CustomerController@index');
// Route::post('/customers', 'CustomerController@store');
// Route::get('/customers/{customer}', 'CustomerController@show');
// Route::patch('/customers/{customer}', 'CustomerController@update');
// Route::delete('/customers/{customer}', 'CustomerController@destroy');

// /*
// |--------------------------------------------------------------------------
// | API Routes for Sizes resource goes below
// |--------------------------------------------------------------------------
//  */
// Route::get('/sizes', 'SizeController@index');
// Route::post('/sizes', 'SizeController@store');
// Route::get('/sizes/{size}', 'SizeController@show');
// Route::patch('/sizes/{size}', 'SizeController@update');
// Route::delete('/sizes/{size}', 'SizeController@destroy');
