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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/import-check', 'ImportController@check');

Route::middleware(['auth'])->group(function () {

	Route::resource('products', 'ProductController');
	// Route::get('/products/import', 'ProductController@import');
	Route::post('pimport', 'ImportController@import')->name('pimport');
});