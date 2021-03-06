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

Route::group(['middleware' => ['guest']], function () {
	Route::get('/', 'Auth\LoginController@showLoginForm');
	Route::post('/login','Auth\LoginController@login')->name('login');
 });

Route::group(['middleware' => ['auth']], function () {
	
	Route::get('/main', function () {
    return view('content/content');
	})->name('main');

	Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
 

	Route::group(['middleware' => ['storekeeper']], function () {
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
		Route::get('/articulo/buscarArticulo','ArticleController@searchArticle');
		Route::get('/articulo/listar', 'ArticleController@listArticle');
		
		Route::get('/articulo/buscarArticuloVenta', 'ArticleController@searchArticleSale');
        Route::get('/articulo/listarArticuloVenta', 'ArticleController@listArticleSale');

        Route::get('/articulo/listarPDF', 'ArticleController@listPdf')->name('articles_pdf');



		Route::get('/proveedor', 'SupplierController@index');
		Route::post('/proveedor/registrar','SupplierController@store');
		Route::put('/proveedor/actualizar','SupplierController@update');
		Route::get('/proveedor/selectProveedor','SupplierController@selectProveedor');

		Route::get('/ingreso','EntryController@index');
		Route::post('/ingreso/registrar','EntryController@store');
		Route::get('/ingreso/desactivar','EntryController@desactivate');
		Route::get('/ingreso/obtenerCabecera', 'EntryController@getHeader');
        Route::get('/ingreso/obtenerDetalles', 'EntryController@getDetails');


		


	 });

	Route::group(['middleware' => ['seller']], function () {
		Route::get('/cliente', 'ClientController@index');
		Route::post('/cliente/registrar','ClientController@store');
		Route::put('/cliente/actualizar','ClientController@update');
		Route::get('/cliente/selectCliente', 'ClientController@selectClient');

		Route::get('/articulo/buscarArticuloVenta', 'ArticleController@searchArticleSale');
        Route::get('/articulo/listarArticuloVenta', 'ArticleController@listArticleSale');



		Route::get('/ventas','SalesController@index');
		Route::post('/ventas/registrar','SalesController@store');
		Route::get('/ventas/desactivar','SalesController@desactivate');
		Route::get('/ventas/obtenerCabecera', 'SalesController@getHeader');
        Route::get('/ventas/obtenerDetalles', 'SalesController@getDetails');
        Route::get('/ventas/pdf/{id}', 'SalesController@pdf')->name('sale_pdf');

 
	 });

	Route::group(['middleware' => ['admin']], function()
	{
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
		Route::get('/articulo/buscarArticulo','ArticleController@searchArticle');
		Route::get('/articulo/listar', 'ArticleController@listArticle');
		
		Route::get('/articulo/buscarArticuloVenta', 'ArticleController@searchArticleSale');
        Route::get('/articulo/listarArticuloVenta', 'ArticleController@listArticleSale');
         Route::get('/articulo/listarPDF', 'ArticleController@listPdf')->name('articles_pdf');



		Route::get('/proveedor', 'SupplierController@index');
		Route::post('/proveedor/registrar','SupplierController@store');
		Route::put('/proveedor/actualizar','SupplierController@update');
		Route::get('/proveedor/selectProveedor','SupplierController@selectProveedor');


		Route::get('/ingreso','EntryController@index');
		Route::get('/ingreso/registrar','EntryController@store');
		Route::get('/ingreso/desactivar','EntryController@desactivate');
		Route::get('/ingreso/obtenerCabecera', 'EntryController@getHeader');
        Route::get('/ingreso/obtenerDetalles', 'EntryController@getDetails');

		Route::get('/cliente', 'ClientController@index');
		Route::post('/cliente/registrar','ClientController@store');
		Route::put('/cliente/actualizar','ClientController@update');
		Route::get('/cliente/selectCliente', 'ClientController@selectClient');

		Route::get('/rol','RolController@index');
		Route::get('/rol/selectRol','RolController@selectRol');

		Route::get('/user', 'UserController@index');
		Route::post('/user/registrar','UserController@store');
		Route::put('/user/actualizar','UserController@update');
		Route::put('/user/desactivar','UserController@desactivate');
		Route::put('/user/activar','UserController@activate');

		Route::get('/ventas','SalesController@index');
		Route::post('/ventas/registrar','SalesController@store');
		Route::get('/ventas/desactivar','SalesController@desactivate');
		Route::get('/ventas/obtenerCabecera', 'SalesController@getHeader');
        Route::get('/ventas/obtenerDetalles', 'SalesController@getDetails');
        Route::get('/ventas/pdf/{id}', 'SalesController@pdf')->name('sale_pdf');



  });

 });




