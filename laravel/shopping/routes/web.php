<?php

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

Route::get('products', ['uses' => 'ProductsController@index', 'as' => 'allProducts']);

Route::get('product/addToCart/{id}', ['uses' => 'ProductsController@addProductToCart', 'as' => 'AddToCartProduct']);

// show cart items
Route::get('cart', ['uses' => 'ProductsController@showCart', 'as' => 'cartProducts']);

// Delete item from cart
Route::get('product/deleteItemFromCart/{id}', ['uses' => 'ProductsController@deleteItemFromCart', 'as' => 'DeleteItemFromCart']);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Admin Panel
Route::get('admin/products', ['uses' => 'Admin\AdminProductsController@index', 'as' => 'adminDisplayProducts'])->middleware('restrictToAdmin');

// display edit product form
Route::get('admin/editProductForm/{id}', ['uses' => 'Admin\AdminProductsController@editProductForm', 'as' => 'adminEditProductForm']);

// display edit product image form
Route::get('admin/editProductImageForm/{id}', ['uses' => 'Admin\AdminProductsController@editProductImageForm', 'as' => 'adminEditProductImageForm']);

// update product image
Route::post('admin/updateProductImage/{id}', ['uses' => 'Admin\AdminProductsController@updateProductImage', 'as' => 'adminUpdateProductImage']);

// update product data
Route::post('admin/updateProduct/{id}', ['uses' => 'Admin\AdminProductsController@updateProduct', 'as' => 'adminUpdateProduct']);

// display create product form
Route::get('admin/createProductForm', ['uses' => 'Admin\AdminProductsController@createProductForm', 'as' => 'adminCreateProductForm']);

// Send new product data to database
Route::post('admin/createProductForm', ['uses' => 'Admin\AdminProductsController@sendCreateProductForm', 'as' => 'adminSendCreateProductForm']);

// delete product
Route::get('admin/deleteProduct/{id}', ['uses' => 'Admin\AdminProductsController@deleteProduct', 'as' => 'adminDeleteProduct']);
