<?php

use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

// Auth::routes();

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
    //Home / Dashboard
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //PARAMÊTROS

    //Usuários
    Route::get('usuarios', ['as' => 'usuarios.list', 'uses' => 'App\Http\Controllers\UserController@index']);
    Route::get('usuarios/cadastro', ['as' => 'usuarios.store', 'uses' => 'App\Http\Controllers\UserController@store']);
    Route::post('usuarios/create', ['as' => 'usuarios.create', 'uses' => 'App\Http\Controllers\UserController@create']);
    Route::get('usuarios/delete/{id}', ['as' => 'usuarios.delete', 'uses' => 'App\Http\Controllers\UserController@delete']);
    Route::get('usuarios/desativar/{id}', ['as' => 'usuarios.delete', 'uses' => 'App\Http\Controllers\UserController@desativar']);
    Route::get('usuarios/ativar/{id}', ['as' => 'usuarios.delete', 'uses' => 'App\Http\Controllers\UserController@ativar']);


    Route::get('perfil', ['as' => 'usuarios.perfil', 'uses' => 'App\Http\Controllers\UserController@perfil']);
	Route::put('profile/update', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

    //predios
    Route::get('predios', ['as' => 'predios.list', 'uses' => 'App\Http\Controllers\PrediosController@index']);
    Route::get('predios/cadastro', ['as' => 'predios.store', 'uses' => 'App\Http\Controllers\PrediosController@store']);
    Route::post('predios/create', ['as' => 'predios.create', 'uses' => 'App\Http\Controllers\PrediosController@create']);
    Route::get('predios/delete/{id}', ['as' => 'predios.delete', 'uses' => 'App\Http\Controllers\PrediosController@delete']);

    //PI
    Route::get('pi', ['as' => 'pi.list', 'uses' => 'App\Http\Controllers\PiController@index']);
    Route::get('pi/cadastro', ['as' => 'pi.store', 'uses' => 'App\Http\Controllers\PiController@store']);
    Route::post('pi/create', ['as' => 'pi.create', 'uses' => 'App\Http\Controllers\PiController@create']);
    Route::get('pi/delete/{id}', ['as' => 'pi.delete', 'uses' => 'App\Http\Controllers\PiController@delete']);
    Route::post('pi/programa',['as' => 'pi.programa', 'uses' => 'App\Http\Controllers\PiController@programa']);

    Route::post('items/create', ['as' => 'items.create', 'uses' => 'App\Http\Controllers\ItemController@create']);
    Route::get('items/store', ['as' => 'items.store', 'uses' => 'App\Http\Controllers\ItemController@store']);
    Route::get('items/delete/{id}/{id_pi}', ['as' => 'items.delete', 'uses' => 'App\Http\Controllers\ItemController@delete']);

    //Empreiteiras
    Route::get('empreiteiras', ['as' => 'empreiteiras.list', 'uses' => 'App\Http\Controllers\EmpreiteirasController@index']);
    Route::get('empreiteiras/cadastro', ['as' => 'empreiteiras.store', 'uses' => 'App\Http\Controllers\EmpreiteirasController@store']);
    Route::post('empreiteiras/create', ['as' => 'empreiteiras.create', 'uses' => 'App\Http\Controllers\EmpreiteirasController@create']);
    Route::get('empreiteiras/delete/{id}', ['as' => 'empreiteiras.delete', 'uses' => 'App\Http\Controllers\EmpreiteirasController@delete']);

    //Obras
    Route::prefix('vistorias')->namespace('App\Http\Controllers')->group(function() {
        Route::get('/', 'VistoriaController@index')->name('vistorias.cadastro');
        Route::get('/getpi', 'VistoriaController@getPi')->name('vistorias.getpi');
    });
    //Route::post('vistorias/obras/create', ['as' => 'obras.vistoria.create', 'uses' => 'App\Http\Controllers\CadastroVistoriaController@store']);

        //List
    Route::get('vistorias/obras/list', ['as' => 'obras.list', 'uses' => 'App\Http\Controllers\PrediosController@index']);
    Route::post('vistorias/obras/list/create', ['as' => 'obras.store', 'uses' => 'App\Http\Controllers\PrediosController@store']);

        //Gerencial
    // Route::get('vistorias/obras/list', ['as' => 'obras.list', 'uses' => 'App\Http\Controllers\PrediosController@index']);
    // Route::post('vistorias/obras/list/create', ['as' => 'obras.store', 'uses' => 'App\Http\Controllers\PrediosController@store']);



    //END PARAMÊTROS

    //OBRAS

    //ENDOBRAS

    //Rotas de Pesquisa
    Route::post('/carrega/predio','App\Http\Controllers\CarregaController@predios')->name('carrega.predio');
    Route::post('/carrega/empreiteiras','App\Http\Controllers\CarregaController@empreiteiras')->name('carrega.empreiteiras');
    Route::post('/carrega/pi','App\Http\Controllers\CarregaController@processoIntervencao')->name('carrega.pi');

    //End Rotas de pesquisa

});

//Rederização de pagina.
Route::group(['middleware' => 'auth'], function () {
    Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});
