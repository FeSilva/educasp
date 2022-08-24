<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicaoController;

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
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboardCharts', [App\Http\Controllers\DashboardController::class, 'returnChartJson'])->name('dashboardCharts');
    Route::get('/dashboardChartsMult', [App\Http\Controllers\DashboardController::class, 'returnChartsJsonMulti'])->name('returnChartsJsonMulti');
    Route::get('/dashboardChartsMultType', [App\Http\Controllers\DashboardController::class, 'returnChartsJsonMultType'])->name('dashboard.ChartsMultType');
    Route::get('/dashboardSinteticTableVistorias', [App\Http\Controllers\DashboardController::class, 'sintenticTableVistorias'])->name('dashboard.SinteticTableVistorias');
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
    Route::post('pi/programa', ['as' => 'pi.programa', 'uses' => 'App\Http\Controllers\PiController@programa']);
    Route::post('items/create', ['as' => 'items.create', 'uses' => 'App\Http\Controllers\ItemController@create']);
    Route::get('items/store', ['as' => 'items.store', 'uses' => 'App\Http\Controllers\ItemController@store']);
    Route::get('items/delete/{id}/{id_pi}', ['as' => 'items.delete', 'uses' => 'App\Http\Controllers\ItemController@delete']);

    //Calendar
    Route::get('calendar/{codigo}/pi', ['as' => 'calendar.list', 'uses' => 'App\Http\Controllers\CalendarController@index']);
    //Empreiteiras
    Route::prefix('empreiteiras')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'EmpreiteirasController@index')->name('empreiteiras.list');
        Route::get('/cadastro', 'EmpreiteirasController@store')->name('empreiteiras.store');
        Route::post('/create', 'EmpreiteirasController@create')->name('empreiteiras.create');
        Route::get('/delete/{id}', 'EmpreiteirasController@delete')->name('empreiteiras.delete');
    });
    //Obras
    Route::prefix('vistorias')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'VistoriaController@index')->name('vistorias.list');
        Route::get('/cadastro', 'VistoriaController@store')->name('vistorias.store');
        Route::post('/store', 'VistoriaController@create')->name('vistorias.create');
        Route::get('/excluir', 'VistoriaController@excluir')->name('vistorias.excluir');
        Route::post('/donwload', 'VistoriaController@download')->name('downloadAnexoVistoria');
        Route::post('/validateDate', 'VistoriaController@validateDate')->name('validateDate');
        Route::get('/storage/{filename}', function ($filename) {
            $path = storage_path('app/public/' . $filename);
            if (!File::exists($path)) {
                abort(404);
            }
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        });
    });
    Route::prefix('vistorias/multiplas')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'VistoriaMutiplasController@index')->name('multiplas.list');
        Route::get('/store', 'VistoriaMutiplasController@store')->name('multiplas.store');
        Route::get('/edit', 'VistoriaMutiplasController@store')->name('multiplas.edit');
        Route::post('/create', 'VistoriaMutiplasController@create')->name('multiplas.create');
        Route::get('/excluir', 'VistoriaMutiplasController@excluir')->name('multiplas.excluir');
    });

    Route::prefix('vistorias/tipos')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'VistoriasTiposController@index')->name('vistorias.tipo.list');
        Route::get('/create', 'VistoriasTiposController@create')->name('vistorias.tipo.create');

        Route::post('/store', 'VistoriasTiposController@store')->name('vistorias.tipo.store');

    });

    Route::prefix('ziparchive')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'ZipArchiveController@index')->name('zipArchive.index');
        Route::post('/descompact', 'ZipArchiveController@descompactZip')->name('zipArchive.descompact');
    });
    Route::prefix('ziparchive/multiplos')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'ZipArchiveMultiplosController@index')->name('zipArchiveMultiplos.index');
        Route::post('/descompact', 'ZipArchiveMultiplosController@descompactZip')->name('zipArchiveMultiplos.descompact');
    });
    Route::prefix('listaenvios')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'ListaEnvioController@index')->name('listaenvios.index');
        Route::get('/create', 'ListaEnvioController@create')->name('listaenvios.create');
        Route::get('/store/{id}', 'ListaEnvioController@store')->name('listaenvios.store');

        Route::post('/', 'ListaEnvioController@carregar')->name('listaenvios.carregar');
        Route::post('/enviaremail', 'ListaEnvioController@enviarEmail')->name('listaenvios.enviaremail');
        Route::post('/consultaMes', 'ListaEnvioController@consultaMes')->name('listaenvios.consultaMes');

        Route::get('/viewEmail', 'ListaEnvioController@viewEmail')->name('listaenvios.viewEmail');
    });
    Route::prefix('listaenviosMultiplos')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'ListaEnvioMultiplosController@index')->name('listaenviosMultiplos.index');
        Route::get('/create', 'ListaEnvioMultiplosController@create')->name('listaenviosMultiplos.create');
        Route::get('/store/{id}', 'ListaEnvioMultiplosController@store')->name('listaenviosMultiplos.store');

        Route::post('/', 'ListaEnvioMultiplosController@carregar')->name('listaenviosMultiplos.carregar');
        Route::post('/enviaremail', 'ListaEnvioMultiplosController@enviarEmail')->name('listaenviosMultiplos.enviaremail');
        Route::post('/consultaMes', 'ListaEnvioMultiplosController@consultaMes')->name('listaenviosMultiplos.consultaMes');

        Route::get('/viewEmail', 'ListaEnvioMultiplosController@viewEmail')->name('listaenviosMultiplos.viewEmail');
    });
    //Rotas de Pesquisa
    Route::prefix('carrega')->namespace('App\Http\Controllers')->group(function () {
        Route::post('/predio', 'CarregaController@predios')->name('carrega.predio');
        Route::post('/empreiteiras', 'CarregaController@empreiteiras')->name('carrega.empreiteiras');
        Route::post('/pi', 'CarregaController@processoIntervencao')->name('carrega.pi');
        Route::post('/vistorias', 'CarregaController@vistorias')->name('carrega.vistorias');
        Route::post('/vistorias/multiplas', 'CarregaController@vistoriasMultiplas')->name('carrega.vistoriasMultiplas');
    });
    //Logs
    Route::prefix('logs')->group(function () {
        Route::prefix('upload')->namespace('App\Http\Controllers')->group(function () {
            Route::match(['GET', 'POST'], '/', 'UploadLogController@index')->name('logs.upload.list');
        });
    });
    //End Rotas de pesquisa
    //download
    Route::get('/storage-link', function () {
        $artisanLink = Artisan::call('storage:link', []);
        return "Storage Link created :".$artisanLink;
    });
    //Documentos
    Route::prefix('documents')->namespace('App\Http\Controllers')->group(function () {
        Route::get('os', 'DocumentController@index')->name('documents.os');
        Route::get('os/cadastro', 'DocumentController@create')->name('documents.create');
        Route::post('os', 'DocumentController@store')->name('documents.store');
        Route::get('/os/{id}', 'DocumentController@show')->name('documents.show');
        Route::post('/os/{id}', 'DocumentController@edit')->name('documents.edit');
        Route::get('/os/{id}/pdf', 'DocumentController@pdf')->name('documents.pdf');

        Route::prefix('protocolos')->group(function () {
            Route::get('/', 'ProtocoloController@index')->name('protocolos.index');
            Route::get('/cadastro', 'ProtocoloController@store')->name('protocolos.store');
            Route::post('/create', 'ProtocoloController@create')->name('protocolos.create');
            Route::get('/edit/{id}', 'ProtocoloController@edit')->name('protocolos.edit');
            Route::get('/pdf/{id}', 'ProtocoloController@pdf')->name('protocolo.pdf');
            Route::get('/update/{id}', 'ProtocoloController@update')->name('protocolo.update');
            Route::get('/delete/{id}', 'ProtocoloController@destroy')->name('protocolo.delete');

        
            Route::prefix('multiplos')->group(function () {
                Route::get('/', 'ProtocoloMultiplosController@index')->name('protocolosMultiplos.index');
                Route::get('/cadastro', 'ProtocoloMultiplosController@store')->name('protocolosMultiplos.store');
                Route::post('/create', 'ProtocoloMultiplosController@create')->name('protocolosMultiplos.create');
                Route::get('/edit/{id}', 'ProtocoloMultiplosController@edit')->name('protocolosMultiplos.edit');
                Route::get('/pdf/{id}', 'ProtocoloMultiplosController@pdf')->name('protocolosMultiplos.pdf');
                Route::get('/update/{id}', 'ProtocoloMultiplosController@update')->name('protocolosMultiplos.update');
                Route::get('/delete/{id}', 'ProtocoloMultiplosController@destroy')->name('protocolosMultiplos.delete');
                //Segurança do trabalho
                Route::post('/update-st', 'ProtocoloMultiplosController@AprovarVistoriasST')->name('protocolosMultiplos.update-st');
            });
        });
    });

    //Relatorios
    Route::prefix('relatory')->namespace('App\Http\Controllers')->group(function () {
        Route::prefix('supervisor')->group(function () {
            Route::get('/', 'RelatoryController@index')->name('relatory.supervisor.index');
            Route::post('/gerarXml', 'RelatoryController@relatory')->name('relatory.supervisor.xml');
        });
    });

    Route::prefix('company')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'CompanyController@index')->name('company.list');
        Route::get('/cadastro', 'CompanyController@create')->name('company.create');
        Route::post('/store', 'CompanyController@store')->name('company.store');
        Route::get('/edit/{company_id}', 'CompanyController@edit')->name('company.edit');
        Route::post('/update', 'CompanyController@update')->name('company.update');
    });

    //Medição

    Route::prefix('medicao')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', [MedicaoController::class, 'index'])->name('medicao.list');
        Route::post('/store', [MedicaoController::class, 'store'])->name('medicao.store');
        Route::get('/show/{medicao_id}', [MedicaoController::class, 'show'])->name('medicao.show');
        Route::get('/show/{medicao_id}/fiscal/{fiscal_id}/show/{status}', [MedicaoController::class, 'medicaoFiscal'])->name('medicao.fiscal.show');

        //ajax
        Route::post('/fiscal/vistoria_details/', [MedicaoController::class, 'vistoriaDetails'])->name('medicao.fiscal.vistoriaetails');
        Route::post('/fiscal/medirVistoria/', [MedicaoController::class, 'medirVistoria'])->name('medicao.fiscal.medirVistoria');
        Route::post('/fiscal/status/', [MedicaoController::class, 'atualizarStatusMedicaoFiscais'])->name('medicao.fiscal.status');

        
        Route::post('/vistorias/despesas/list', [MedicaoController::class, 'listVistoriasDespesas'])->name('vistorias.despesas.list');
        Route::post('/vistorias/despesas/create', [MedicaoController::class, 'createVistoriasDespesas'])->name('vistorias.despesas.create');
        Route::post('/vistorias/despesas/update', [MedicaoController::class, 'updateVistoriasDespesas'])->name('vistorias.despesas.update');

        //Relatorios
        Route::get('/relatorio/fiscal/{fiscal_id}/medicao/{medicao_id}', [MedicaoController::class, 'relatoryMedicoes'])->name('medicao.fiscal.relatorio.medicoes');
        Route::get('/relatorio/fiscal/{fiscal_id}/medicao/{medicao_id}/despesas', [MedicaoController::class, 'relatoryDespesas'])->name('medicao.fiscal.relatorio.despesas');

        //medição anexos 
        Route::post('/fiscal/anexos/create', [MedicaoController::class, 'createAnexos'])->name('medicao.fiscal.anexos');
        Route::get('/storage/{medicao_id}/{fiscal_id}/{filename}', function ($medicao_id, $fiscal_id, $filename) {
            $path = public_path("storage/archives/medicao/{$medicao_id}/fiscal/{$fiscal_id}/" . $filename);
            if (!File::exists($path)) {
                abort(404);
            }
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        });
    });
});

//Rederização de pagina.
Route::group(['middleware' => 'auth'], function () {
    Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});
