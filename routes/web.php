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
    return redirect('login');
})->name('/');

Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth']], function () {

    Route::get('/home', 'QuotesController@quoteList')->name('home');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

    //quotes
    Route::get('/quote-list', 'QuotesController@quoteList')->name('quoteList');
    Route::get('/quote-add', 'QuotesController@quoteAdd')->name('quoteAdd');
    Route::get('/quote-edit/{id}', 'QuotesController@quoteEdit')->name('quoteEdit');
    Route::post('/quote-save', 'QuotesController@quoteSave')->name('quoteSave');
    Route::get('/quote-view/{id}', 'QuotesController@quoteView')->name('quoteView');
    Route::get('/quote-delete/{id}', 'QuotesController@quoteDelete')->name('quoteDelete');
    Route::get('/get-base-value', 'QuotesController@getBaseValue')->name('getBaseValue');

});
