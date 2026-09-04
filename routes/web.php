<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategorieEntreeController;
use App\Http\Controllers\CategorieSortieController;
use App\Http\Controllers\EntreeController;
use App\Http\Controllers\SortieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RapportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ActiviteController;



Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('categories-entrees', CategorieEntreeController::class);

    Route::resource('categories-sorties', CategorieSortieController::class);

    Route::resource('entrees', EntreeController::class);

    Route::resource('sorties', SortieController::class);
    Route::resource('users', UsersController::class);

Route::get('/rapports', [RapportsController::class, 'index'])->name('rapports.index');
Route::get('/rapports/export', [RapportsController::class, 'export'])->name('rapports.export');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
        Route::patch('/sorties/{sortie}/valider', [SortieController::class, 'valider'])->name('sorties.valider');
Route::patch('/sorties/{sortie}/rejeter', [SortieController::class, 'rejeter'])->name('sorties.rejeter');
Route::get('/activites', [ActiviteController::class, 'index'])->name('activites.index');
});

require __DIR__.'/auth.php';