<?php

/*
 * |==============================================================|
 * | Please DO NOT modify this information :                      |
 * |--------------------------------------------------------------|
 * | Author          : Susantokun
 * | Email           : admin@susantokun.com
 * | Instagram       : @susantokun
 * | Website         : http://www.susantokun.com
 * | Youtube         : http://youtube.com/susantokun
 * | File Created    : Friday, 28th February 2020 2:50:20 pm
 * | Last Modified   : Friday, 28th February 2020 2:50:20 pm
 * |==============================================================|
 */

Route::get('locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware' => 'auth'], function () {
    Route::resource('menus', 'MenuController');
    Route::post('menus/delete/{id}', 'MenuController@delete');
});
