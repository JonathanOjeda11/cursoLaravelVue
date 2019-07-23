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
    return view('content/content');
});

Route::get('/categoria', 'CategoryController@index');
Route::post('/categoria/registrar','CategoryController@store');
Route::put('/categoria/actualizar','CategoryController@update');
Route::put('/categoria/desactivar','CategoryController@desactivate');
Route::put('/categoria/activar','CategoryController@activate');
Route::get('/categoria/selectCategoria','CategoryController@selectCategory');   

Route::get('/articulo', 'ArticleController@index');
Route::post('/articulo/registrar','ArticleController@store');
Route::put('/articulo/actualizar','ArticleController@update');
Route::put('/articulo/desactivar','ArticleController@desactivate');
Route::put('/articulo/activar','ArticleController@activate');
Route::put('/categoria/activar','CategoryController@activate');
