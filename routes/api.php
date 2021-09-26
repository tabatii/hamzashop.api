<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/auth/me', 'AuthController@me');
Route::post('/auth/register', 'AuthController@register');
Route::post('/auth/login', 'AuthController@login');
Route::post('/auth/logout', 'AuthController@logout');

Route::get('/email/verify/{id}', 'VerificationController@verify')->name('verification.verify');
Route::post('/email/resend', 'VerificationController@resend')->name('verification.resend');

Route::get('/password/reset', 'PasswordController@redirect')->name('password.reset');
Route::post('/password/reset', 'PasswordController@reset');
Route::post('/password/forgot', 'PasswordController@forgot');

Route::get('/admin/me', 'AdminController@me');
Route::post('/admin/login', 'AdminController@login');
Route::post('/admin/logout', 'AdminController@logout');

Route::get('/home', 'HomeController@index');
Route::post('/newsletter', 'NewsletterController@join');
Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/header', 'DashboardController@header');
Route::get('/notifications', 'NotificationController@index');

Route::post('/paypal/create', 'PayPalController@create');
Route::post('/paypal/capture', 'PayPalController@capture');

Route::post('/paymentwall', 'PaymentwallController@payment');

Route::patch('/products/quick/{id}', 'ProductController@quick');
Route::patch('/products/show/{id}', 'ProductController@display');
Route::patch('/products/hide/{id}', 'ProductController@hide');
Route::apiResource('/products', 'ProductController')->parameters(['products' => 'id']);

Route::patch('/orders/refuse/{id}', 'OrderController@refuse');
Route::patch('/orders/finish/{id}', 'OrderController@finish');
Route::patch('/orders/cancel/{id}', 'OrderController@cancel');
Route::apiResource('/orders', 'OrderController')->parameters(['orders' => 'id']);

Route::apiResource('/users', 'UserController')->parameters(['users' => 'id']);
Route::apiResource('/addresses', 'AddressController')->parameters(['addresses' => 'id']);
Route::apiResource('/messages', 'MessageController')->parameters(['messages' => 'id']);
Route::apiResource('/shipping', 'ShippingController')->parameters(['shipping' => 'id']);
