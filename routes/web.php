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
Route::post('/preferencie', [\App\Http\Controllers\TagController::class, 'pouzivatelPreferencieSet'])->name('preferencie.post');
Route::post('/preferencie/regiony', [\App\Http\Controllers\TagController::class, 'pouzivatelRegionySet'])->name('regiony.post');

#POST
Route::get('/domov', [\App\Http\Controllers\PostController::class, 'domovGet'])->name('domov');
Route::get('/domov/prispevok/dalsi', [\App\Http\Controllers\PostController::class, 'domov_prispevok_dalsiGet'])->name('domov_dalsi');
Route::get('/domov/prispevok/predosli', [\App\Http\Controllers\PostController::class, 'domov_prispevok_predosliGet'])->name('domov_predosli');
Route::get('/domov/prispevok/{id_post}', [\App\Http\Controllers\PostController::class, 'domov_prispevokGet'])->name('domov_prispevok');
Route::post('/domov/zobrazenie', [\App\Http\Controllers\PostController::class, 'domov_zobrazeniePost'])->name('domov_zobrazenie');
Route::post('/domov/zobrazenie/anketa_hlasuj', [\App\Http\Controllers\PollController::class, 'anketa_hlasujPost'])->name('anketa_hlasuj.post');
Route::post('/domov/zobrazenie/post_hlasuj', [\App\Http\Controllers\PostController::class, 'post_hlasujPost'])->name('post_hlasuj.post');
Route::post('/domov/zobrazenie/pridaj_koment', [\App\Http\Controllers\PostController::class, 'post_pridaj_komentPost'])->name('post_pridaj_koment.post');
Route::post('/domov/zobrazenie/hlasuj_koment', [\App\Http\Controllers\PostController::class, 'post_hlasuj_komentPost'])->name('post_hlasuj_koment.post');

Route::get('/pridaj_prispevok', [\App\Http\Controllers\PostController::class, 'pridaj_prispevokGet'])->name('pridaj_prispevok');
Route::post('/pridaj_prispevok', [\App\Http\Controllers\PostController::class, 'pridaj_prispevokPost'])->name('pridaj_prispevok.post');
Route::post('/pridaj_prispevok/pridaj_moznost_anketa', [\App\Http\Controllers\PollController::class, 'pridaj_moznost_anketaPost'])->name('pridaj_moznost_anketa.post');

#AUTH
Route::get('/prihlasenie', [AuthController::class, 'prihlasenie'])->name('prihlasenie');
Route::post('/prihlasenie', [AuthController::class, 'prihlaseniePost'])->name('prihlasenie.post');
Route::get('/registracia', [AuthController::class, 'registracia'])->name('registracia');
Route::post('/registracia', [AuthController::class, 'registraciaPost'])->name('registracia.post');
Route::get('/odhlasenie', [AuthController::class, 'odhlasenie'])->name('odhlasenie');

Route::get('/profil_uprava', [AuthController::class, 'profil_uprava'])->name('profil_uprava');
Route::post('/profil_uprava/pridaj_obrazok', [AuthController::class, 'pridaj_obrazokPost'])->name('pridaj_obrazok.post');
Route::post('/profil_uprava/uloz_obrazok', [AuthController::class, 'uloz_obrazokPost'])->name('uloz_obrazok.post');
