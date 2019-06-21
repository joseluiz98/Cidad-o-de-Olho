<?php


Route::group(['prefix' => 'deputados'], function () {
    Route::get('initialize','DeputadoController@initialize');
    Route::get('que_mais_pediram_reembolso', "DeputadoController@topCincoVerbas");
});
Route::resource('deputados','DeputadoController');

Route::get('/', function () {
    return redirect('api');
});