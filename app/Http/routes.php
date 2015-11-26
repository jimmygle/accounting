<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

setlocale(LC_MONETARY, 'en_US.UTF-8');

Route::get('/', 'DashboardController@index');
Route::resource('accounts', 'AccountsController');
Route::resource('transactions', 'TransactionsController');
Route::resource('categories', 'CategoriesController');
Route::resource('vendors', 'VendorsController');
Route:get('taxes', 'TaxesController@index');