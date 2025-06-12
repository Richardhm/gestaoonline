<?php

use App\Http\Controllers\ACController;
use App\Http\Controllers\ACN2Controller;
use App\Http\Controllers\ARController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',[App\Http\Controllers\DashboardController::class, 'index'] )->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(callback: function () {

    Route::post('/importar-json', [App\Http\Controllers\DashboardController::class, 'importarJson'])->name('importar-json');


    Route::resource('acs', ACController::class);
    Route::resource('acn2s', ACN2Controller::class);
    Route::resource('ars', ARController::class);


    Route::get('/qrcode/{entity}', [App\Http\Controllers\QRCodeController::class, 'generate'])->name('qrcode.generate');



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
