<?php


Route::group(['prefix' => 'deputados'], function () {
    Route::get('initialize','DeputadoController@initialize');
});
Route::resource('deputados','DeputadoController');

Route::get('/', function () {
    return redirect('api');
});