<?php

use App\Http\Controllers\AuthController;
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


Route::get('/spravy', function () {
    return view('spravy');
})->name('spravy');

#TAG
Route::get('/preferencie', [\App\Http\Controllers\TagController::class, 'pouzivatelPreferencieGet'])->name('preferencie');


#POST
Route::get('/domov', [\App\Http\Controllers\PostController::class, 'domovGet'])->name('domov');
Route::get('/domov/prispevok/dalsi', [\App\Http\Controllers\PostController::class, 'domov_prispevok_dalsiGet'])->name('domov_dalsi');
Route::get('/domov/prispevok/predosli', [\App\Http\Controllers\PostController::class, 'domov_prispevok_predosliGet'])->name('domov_predosli');
Route::get('/domov/prispevok/{id_post}', [\App\Http\Controllers\PostController::class, 'domov_prispevokGet'])->name('domov_prispevok');

Route::get('/pridaj_prispevok', [\App\Http\Controllers\PostController::class, 'pridaj_prispevokGet'])->name('pridaj_prispevok');
Route::post('/pridaj_prispevok', [\App\Http\Controllers\PostController::class, 'pridaj_prispevokPost'])->name('pridaj_prispevok.post');

#AUTH
Route::get('/prihlasenie', [AuthController::class, 'prihlasenie'])->name('prihlasenie');
Route::post('/prihlasenie', [AuthController::class, 'prihlaseniePost'])->name('prihlasenie.post');
Route::get('/registracia', [AuthController::class, 'registracia'])->name('registracia');
Route::post('/registracia', [AuthController::class, 'registraciaPost'])->name('registracia.post');
Route::get('/odhlasenie', [AuthController::class, 'odhlasenie'])->name('odhlasenie');
