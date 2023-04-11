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

Route::get('/admin', 'AdminController@admin')->middleware('is_admin')->name('admin');

Route::group(['middleware' => 'is_admin'], function () 
{
    // <-- Formas de pago -->

    Route::get('/admin/getfdpago', 'FormaPagoController@getFdPago');
    
    // <-- Afip -->
        
    Route::get('/admin/afip', 'AfipController@index')->name('afip.index');

    Route::post('/admin/afip/generarFactura', 'AfipController@generarCbte');

    Route::post('/admin/afip/generarNotaDeCredito', 'AfipController@generarCbte');

    Route::get('/admin/afip/verFactura/{idAfip}', 'AfipController@verCbte');

    Route::get('/admin/afip/verNotaDeCredito/{idAfip}', 'AfipController@verCbte');

    Route::get('/admin/afip/pagarTodo', 'AfipController@pagarTodo');

    Route::post('/admin/afip/filtro', 'AfipController@filtrarCbtes');

    // <--Ticket -->

    Route::get('/admin/verTicket/{idOrder}', 'AfipController@verTicket');

    // Route::post('/admin/productos/actualizarprecios', 'ProductController@actualizarPrecios');

    // Route::get('/admin/productos/nuevo', 'ProductController@create')->name('products.create');

    // Route::post('/admin/productos', 'ProductController@store');

    // Route::get('/admin/productos/{id}/editar', 'ProductController@edit')->name('products.edit');

    // Route::put('/admin/productos/{product}', 'ProductController@update');

    // Route::delete('/admin/productos/{product}', 'ProductController@delete')->name('products.delete');

    // <-- Productos -->
        
    Route::get('/admin/search/{keywords}', 'SearchProductController@search');

    Route::get('/admin/productos', 'ProductController@index')->name('products.index');

    //Route::get('/admin/productos/buscar', 'ProductController@search');

    Route::post('/admin/productos/filtro', 'ProductController@filter');

    Route::post('/admin/productos/actualizarprecios', 'ProductController@actualizarPrecios');

    Route::get('/admin/productos/nuevo', 'ProductController@create')->name('products.create');

    Route::post('/admin/productos', 'ProductController@store');

    Route::get('/admin/productos/{id}/editar', 'ProductController@edit')->name('products.edit');

    Route::put('/admin/productos/{product}', 'ProductController@update');

    Route::delete('/admin/productos/{product}', 'ProductController@delete')->name('products.delete');

    // <-- Categorias de productos -->
        
    Route::get('/admin/categorias', 'ProductCategoryController@index')->name('categories.index');

    Route::get('/admin/categorias/nueva', 'ProductCategoryController@create')->name('categories.create');

    Route::get('/admin/categorias/papelera', 'ProductCategoryController@papelera')->name('categories.recycleBin');
    
    Route::delete('/admin/categorias/{category}/resurrect', 'ProductCategoryController@resurrect')->name('categories.resurrect');

    Route::post('/admin/categorias', 'ProductCategoryController@store');

    Route::get('/admin/categorias/{id}/editar', 'ProductCategoryController@edit')->name('categories.edit');

    Route::put('/admin/categorias/{category}', 'ProductCategoryController@update');

    Route::delete('/admin/categorias/{category}', 'ProductCategoryController@delete')->name('categories.delete');

    // <-- Control -->
        
    //Route::get('/admin/control/', 'ControlController@inicio')->name('control.caja.inicio');
    Route::get('/admin/control/', 'ControlController@ordenes')->name('control.ingresos.productos');

    Route::get('/admin/control/caja/inicio', 'ControlController@inicio')->name('control.caja.inicio');

    Route::get('/admin/control/caja/cierre/', 'ControlController@cierre')->name('control.caja.cierre');

    Route::post('/admin/control/', 'ControlController@store');
    
    Route::delete('/admin/control/{id}', 'ControlController@delete')->name('control.delete');

    Route::get('/admin/control/caja/retiros', 'ControlController@retiros')->name('control.caja.retiros');

    Route::post('/admin/control/caja/retiros', 'ControlController@historial_retiros');

    // Control.Deudas
    
    Route::post('/admin/control/deudas/pagar', 'DeudaController@saldar');
    
    // Control.Gastos

    Route::get('/admin/control/gastos/varios', 'ControlController@gastos')->name('control.gastos.varios');

    Route::get('/admin/control/gastos/servicios', 'ControlController@gastos')->name('control.gastos.servicios');

    Route::get('/admin/control/gastos/proveedores', 'ControlController@gastos')->name('control.gastos.proveedores');

    Route::post('/admin/control/gastos/varios', 'ControlController@historial_gastos');

    Route::post('/admin/control/gastos/servicios', 'ControlController@historial_gastos');

    Route::post('/admin/control/gastos/proveedores', 'ControlController@historial_gastos');

    // Control.Ingresos

    Route::get('/admin/control/ingresos/productos', 'ControlController@ordenes')->name('control.ingresos.productos');

    Route::post('/admin/control/ingresos/productos', 'ControlController@store_orden');

    Route::post('/admin/control/ingresos/productos/historial', 'ControlController@historial_ordenes');

    //---DESCUENTO en ORDEN---//
    Route::post('/admin/control/productos/descuento/{id_order}', 'ControlController@descuento_orden');
    
    //-----------FIN----------//
    
    //---CERRAR ORDEN---//
    Route::post('/admin/control/productos/cerrar/{id_order}', 'ControlController@cerrar_orden');
    
    //-----------FIN----------//
    Route::get('/admin/control/ingresos/productos/{id_order}', 'ControlController@subordenes')->name('control.ingresos.productos.agregar');

    Route::post('/admin/control/ingresos/productos/{id_order}', 'ControlController@store_suborden');

    Route::delete('/admin/order/{id}', 'OrderController@delete')->name('order.delete');

    Route::get('/admin/getsubordenes/{id_order}', 'OrderProductController@getSubordenes');

    Route::post('/admin/createsuborden/{id_order}', 'OrderProductController@store');

    Route::delete('/admin/deletesuborden/{id}', 'OrderProductController@delete');

    // Control.Movimientos

    Route::get('/admin/control/movimientos/', 'ControlController@movimientos')->name('control.movimientos');

    Route::post('/admin/control/movimientos/historial', 'ControlController@historial_movimientos');

    // <-- Usuarios -->
        
    Route::get('/admin/getclientes', 'UserController@getClientes');
    
    Route::get('/admin/{type}s', 'UserController@index')->name('users.index');

    Route::get('/admin/{type}s/papelera', 'UserController@papelera');

    Route::delete('/admin/{type}s/{user}/resurrect', 'UserController@resurrect')->name('users.resurrect');

    Route::get('/admin/{type}s/nuevo', 'UserController@create')->name('users.create');

    Route::post('/admin/{type}s', 'UserController@store');

    Route::get('/admin/{type}s/{nombre}', 'UserController@show')->name('users.show'); // ID por USER

    Route::get('/admin/{type}s/{nombre}/editar', 'UserController@edit')->name('users.edit');

    Route::put('/admin/{type}s/{user}', 'UserController@update')->name('users.update');

    Route::delete('/admin/{type}s/{user}', 'UserController@delete')->name('users.delete');

    Route::get('/admin/clientes/{nombre}/historial', 'UserController@record')->name('users.record');

    Route::post('/admin/clientes/{nombre}/historial', 'UserController@historial_record');
    
});