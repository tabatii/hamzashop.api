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

Route::get('home', 'HomeController@index');
Route::post('contact', 'ContactController@send');
Route::post('newsletter', 'NewsletterController@join');

Route::post('paypal/create', 'PayPalController@create');
Route::post('paypal/capture', 'PayPalController@capture');

Route::apiResource('/addresses', 'AddressController')->parameters(['addresses' => 'id']);
Route::apiResource('/products', 'ProductController')->parameters(['products' => 'id']);
Route::apiResource('/orders', 'OrderController')->parameters(['orders' => 'id']);
