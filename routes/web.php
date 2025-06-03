<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\Broker\BrokerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->name("login.page");
Route::post('/loginForm', [UserController::class, 'login'])->name('api.login');


Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get('/{username}', [AdminController::class, 'index'])->name('dashboard');


    // broker routes
    Route::get('/{username}/brokers', [BrokerController::class, 'index'])->name('broker.index');
    Route::get('/{username}/brokers/create', [BrokerController::class, 'create'])->name('broker.create');
    Route::post('/{username}/brokers/store', [BrokerController::class, 'store'])->name('broker.store');
    Route::get('/{username}/brokers/edit/{id}', [BrokerController::class, 'edit'])->name('broker.edit');
    Route::post('/{username}/brokers/update/{id}', [BrokerController::class, 'update'])->name('broker.update');
    Route::post('/{username}/brokers/destroy/{id}', [BrokerController::class, 'destroy'])->name('broker.destroy');

    // clients route 
    Route::get('/{username}/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/{username}/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/{username}/clients/store', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/{username}/clients/edit/{id}', [ClientController::class, 'edit'])->name('clients.edit');
    Route::post('/clients/{id}/update', [ClientController::class, 'update'])->name('clients.update');
    Route::post('/clients/{id}/destory', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/{username}/clients/view/{id}', [ClientController::class, 'getclient'])->name('clients.getclient');

    // client document route
    Route::get('/{username}/clients/view/documents/{id}', [ClientController::class, 'getClientDocuments'])->name('clients.getClientDocuments');
    Route::get('/{username}/clients/view/documents/create/{id}', [ClientController::class, 'createClientDocument'])->name('clients.createClientDocument');
    Route::post('/{username}/clients/view/documents/store/{id}', [ClientController::class, 'storeClientDocument'])->name('clients.storeClientDocument');
    Route::get('/{username}/clients/view/documents/edit/{id}', [ClientController::class, 'editDocument'])->name('clients.documents.edit');
    Route::post('/{username}/clients/view/documents/update/{id}', [ClientController::class, 'updateDocument'])->name('clients.documents.update');
    Route::post('/clients/view/documents/delete/{id}', [ClientController::class, 'destory'])->name('clients.documents.destory');


    // 
    Route::prefix('clients/{client}/assets')->group(function () {
        Route::get('/', [AssetController::class, 'index'])->name('assets.index');
        Route::get('/create', [AssetController::class, 'create'])->name('assets.create');
        Route::post('/', [AssetController::class, 'store'])->name('assets.store');
        Route::get('/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
        Route::put('/{asset}', [AssetController::class, 'update'])->name('assets.update');
        Route::delete('/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
        Route::get('/export/{format}', [AssetController::class, 'export'])->name('assets.export');
        Route::get('/email', [AssetController::class, 'emailRecords'])->name('assets.emailRecords');
    });
    Route::post('/clients/{client}/assets/{asset}/remove-file', [AssetController::class, 'removeFile'])->name('assets.removeFile');


    // settings routes
    Route::get('/{username}/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/{username}/settings/update', [SettingController::class, 'update'])->name('settings.update');

    // users
    Route::get('/{username}/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/{username}/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/{username}/users/store', [UserController::class, 'store'])->name('users.store');

    Route::get('/{username}/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/{username}/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    
    Route::get('/{username}/logout',[UserController::class,'logout'])->name('user.logout');


    //brokers route
    Route::get('/{username}/me/profile', [BrokerController::class, 'BrokerProifle'])->name('broker.profile');
    Route::get('/{username}/report', [BrokerController::class, 'report'])->name('broker.report');
    Route::get('/{username}/report/export', [BrokerController::class, 'downloadMonthlyReportPdf'])->name('broker.downloadMonthlyReportPdf');


    // client 
    Route::get('/{username}/profile', [ClientController::class, 'ClientProfile'])->name('client.profile');
    Route::get('/{username}/documents', [ClientController::class, 'ClientSideDocument'])->name('client.ClientSideDocument');
    Route::get('/{username}/assets', [ClientController::class, 'clientsideAssets'])->name('client.clientsideAssets');


    // reports for admin
    Route::get('/{username}/report/broker',[ReportController::class,'currentMonthClientCountPerBroker'])->name('report.currentMonthClientCountPerBroker');
    Route::get('/{username}/report/client',[ReportController::class,'clientAssetDocumentReport'])->name('report.clientAssetDocumentReport');

});
