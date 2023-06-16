<?php

use App\Http\Controllers\ProductosController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\PqrsController;
use App\Http\Controllers\ProveedoresController;
use App\Models\Compras;

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


//Rutas para el frontEnd


Route::get('/', [App\Http\Controllers\FrontController::class, 'index'])->name('main');
Route::get('/confirmar', [App\Http\Controllers\FrontController::class, 'confirmar'])->name('confirmar');




//Rutas para el backend
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('panel.home')->middleware('can:panel.home');
Route::POST('/traergarantias', [App\Http\Controllers\ProductosController::class, 'garantias'])->name('productos.garantias')->middleware('auth');
Route::POST('/traermarcas', [App\Http\Controllers\ProductosController::class, 'marcas'])->name('productos.marcas')->middleware('auth');
Route::POST('/traercategoria', [App\Http\Controllers\ProductosController::class, 'categorias'])->name('productos.categorias')->middleware('auth');

//PRODUCTOS
Route::resource('/productos', App\Http\Controllers\ProductosController::class)->middleware('can:productos.index');

Route::get('/allProductos', [App\Http\Controllers\ProductosController::class, 'allProduct'])->name('productos.allProduct')->middleware('can:productos.allProduct');
Route::post('/productos/update/{id}', [App\Http\Controllers\ProductosController::class, 'update'])->name('productos.actualizar')->middleware('can:productos.actualizar');

//Detalle producto
Route::get('/detalle/{id}', [App\Http\Controllers\FrontController::class, 'detalle'])->name('detalle');

//Categoria Clientes
Route::resource('/categoriaClientes', App\Http\Controllers\CategoriaClientesController::class)->middleware('can:productos.index');
Route::get('/allCategoria', [App\Http\Controllers\CategoriaClientesController::class, 'allCategori'])->name('categoriaClientes.allCategori')->middleware('can:categoriaClientes.allCategori');
Route::post('/categoriaClientes/update/{id}', [App\Http\Controllers\CategoriaClientesController::class, 'update'])->name('categoriaClientes.update')->middleware('auth');

//Gestión de Paises*
Route::resource('/paises', App\Http\Controllers\PaisesController::class)->middleware('auth');
Route::get('/allPaises', [App\Http\Controllers\PaisesController::class, 'allPaises'])->name('paises.allPaises')->middleware('auth');
Route::post('/paises/update/{id}', [App\Http\Controllers\PaisesController::class, 'update'])->name('paises.update')->middleware('auth');

//Gestión de Ciudades
Route::resource('/ciudades', App\Http\Controllers\CiudadesController::class)->middleware('auth');
Route::get('/allCiudades', [App\Http\Controllers\CiudadesController::class, 'allCiudades'])->name('ciudades.allCiudades')->middleware('can:panel.home');

Route::post('/ciudades/update/{id}', [App\Http\Controllers\CiudadesController::class, 'update'])->name('ciudades.update')->middleware('can:panel.home');
Route::POST('/traerpaises', [App\Http\Controllers\CiudadesController::class, 'paises'])->name('ciudades.paises')->middleware('can:panel.home');

//Clientes
Route::resource('/clientes', \App\Http\Controllers\ClientesController::class)->middleware('auth');
Route::get('/clientes/mostrarCompra/{id}',[\App\Http\Controllers\ClientesController::class, 'mostrarCompra'])->name('clientes.mostrarCompra')->middleware('auth');
Route::post('/clientes/update/{id}',[\App\Http\Controllers\ClientesController::class, 'update'])->name('clientes.actualizar')->middleware('auth');

//Banner
Route::resource('/banners', App\Http\Controllers\BannersController::class)->middleware('can:panel.home');
Route::get('/allBanners', [App\Http\Controllers\BannersController::class, 'allBanners'])->name('banners.allBanners')->middleware('can:banners.index');
Route::post('/banners/update/{id}', [App\Http\Controllers\BannersController::class, 'update'])->name('banners.update')->middleware('can:panel.home');


//Categorias
Route::get('/verCategoria', [App\Http\Controllers\FrontController::class, 'verCategoria'])->name('verCategoria');
Route::get('/mostrarProduct{id}', [App\Http\Controllers\FrontController::class, 'mostrarProduct'])->name('mostrarProduct');

//Users
Route::resource('/users', UserController::class);//->middleware('can:users')
Route::get('/allUser', [\App\Http\Controllers\Admin\UserController::class, 'allUsers'])->middleware('can:panel.home')->name('users.allUsers');
Route::post('/users/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update')->middleware('can:panel.home');
Route::POST('/roles', [App\Http\Controllers\Admin\UserController::class, 'roles'])->name('users.roles')->middleware('can:panel.home');

//Carrito
Route::resource('/carrito', CarritoController::class);
Route::post('/carrito/pago', [\App\Http\Controllers\CarritoController::class, 'detallecompra'])->name('carrito.detallecompra');
Route::post('/carrito/puntos', [\App\Http\Controllers\CarritoController::class, 'updatePuntos'])->name('carrito.updatePuntos');

//Gestion pqrs
Route::resource('/pqrs', PqrsController::class);
Route::get('/gestion', [\App\Http\Controllers\PqrsController::class, 'gestion'])->middleware('can:panel.home')->name('pqrs.gestion');
Route::get('/allPqrs', [\App\Http\Controllers\PqrsController::class, 'allPqrs'])->middleware('can:panel.home')->name('pqrs.allPqrs');
Route::post('/pqrs/update/{id}', [App\Http\Controllers\PqrsController::class, 'update'])->name('pqrs.update')->middleware('can:panel.home');

//Compras
Route::resource('/compras', ComprasController::class);
Route::get('/allCompras', [\App\Http\Controllers\ComprasController::class, 'allCompras'])->middleware('can:panel.home')->name('compras.allCompras');
Route::get('/tipo', [\App\Http\Controllers\ComprasController::class, 'tipo'])->middleware('can:panel.home')->name('compras.tipo');
Route::post('/compras/update/{id}', [\App\Http\Controllers\ComprasController::class, 'update'])->middleware('can:panel.home')->name('compras.update');

//Proveedores
Route::resource('/proveedores', ProveedoresController::class)->middleware('can:panel.home');
Route::get('/allproveedrs', [\App\Http\Controllers\ProveedoresController::class, 'allProveedrs'])->middleware('can:panel.home')->name('proveedores.allProveedrs');
Route::post('/proveedores/update/{id}', [\App\Http\Controllers\ProveedoresController::class, 'update'])->middleware('can:panel.home')->name('proveedores.update');
