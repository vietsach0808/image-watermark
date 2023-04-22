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

Route::prefix('iw-image')->middleware('auth')->group(function () {
    Route::get('/', 'ImageWatermarkController@index')->name('iw.index');
    Route::get('/create', 'ImageWatermarkController@create')->name('iw.create');
    Route::post('/store', 'ImageWatermarkController@store')->name('iw.store');
    Route::get('/edit/{id}', 'ImageWatermarkController@edit')->name('iw.edit');
    Route::post('/edit/{id}', 'ImageWatermarkController@update')->name('iw.update');
    Route::post('/delete/{id}', 'ImageWatermarkController@destroy')->name('iw.destroy');
});

Route::prefix('list-image')->middleware('auth')->group(function () {
    Route::get('/', 'ImageWatermarkController@listImage')->name('iw.list');
    Route::post('/download/{id}', 'ImageWatermarkController@download')->name('iw.download');
});
