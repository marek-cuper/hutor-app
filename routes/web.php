<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


#CONVERSATION
Route::get('/spravy', [\App\Http\Controllers\ConversationController::class, 'spravyGet'])->name('spravy');
Route::get('/spravy/konverzacie', [\App\Http\Controllers\ConversationController::class, 'spravyKonverzacieGet'])->name('spravy_konverzacie');
Route::post('/spravy/konverzacie/nacitanie', [\App\Http\Controllers\ConversationController::class, 'vrat_konverzaciePost'])->name('spravy_konverzacie_vrat.post');
Route::post('/spravy/konverzacia_pouzivatel', [\App\Http\Controllers\ConversationController::class, 'showConversationFromUserPost'])->name('spravy_konverzacia_pouzivatel.post');
Route::post('/spravy/konverzacia_id', [\App\Http\Controllers\ConversationController::class, 'showConversationFromIdPost'])->name('spravy_konverzacia_id.post');
Route::post('/spravy/konverzacia/posli_spravu', [\App\Http\Controllers\ConversationController::class, 'posli_spravuPost'])->name('posli_spravu.post');
Route::post('/neprecitane', [\App\Http\Controllers\ConversationController::class, 'neprecitane_spravyPost'])->name('neprecitane.post');

#TAG
Route::get('/preferencie', [\App\Http\Controllers\TagController::class, 'pouzivatelPreferencieGet'])->name('preferencie');
Route::post('/preferencie', [\App\Http\Controllers\TagController::class, 'pouzivatelPreferencieSet'])->name('preferencie.post');
Route::post('/preferencie/regiony', [\App\Http\Controllers\TagController::class, 'pouzivatelRegionySet'])->name('regiony.post');

#POST
Route::get('/domov', [\App\Http\Controllers\PostController::class, 'domovGet'])->name('domov');
Route::post('/domov/zobrazenie', [\App\Http\Controllers\PostController::class, 'domov_zobrazeniePost'])->name('domov_zobrazenie');
Route::post('/domov/zobrazenie/anketa_hlasuj', [\App\Http\Controllers\PollController::class, 'anketa_hlasujPost'])->name('anketa_hlasuj.post');
Route::post('/domov/zobrazenie/post_hlasuj', [\App\Http\Controllers\PostController::class, 'post_hlasujPost'])->name('post_hlasuj.post');
Route::post('/domov/zobrazenie/pridaj_koment', [\App\Http\Controllers\PostController::class, 'post_pridaj_komentPost'])->name('post_pridaj_koment.post');
Route::post('/domov/zobrazenie/hlasuj_koment', [\App\Http\Controllers\PostController::class, 'post_hlasuj_komentPost'])->name('post_hlasuj_koment.post');
Route::post('/domov/zobrazenie/vymaz_koment', [\App\Http\Controllers\PostController::class, 'post_vymaz_komentPost'])->name('post_vymaz_koment.post');
Route::post('/domov/nacitaj_prispevky', [\App\Http\Controllers\PostController::class, 'nacitaj_prispevkyPost'])->name('nacitaj_prispevky.post');
Route::post('/domov/vymaz_prispevok', [\App\Http\Controllers\PostController::class, 'vymaz_prispevokPost'])->name('vymaz_prispevok.post');

Route::get('/pridaj_prispevok', [\App\Http\Controllers\PostController::class, 'pridaj_prispevokGet'])->name('pridaj_prispevok');
Route::post('/pridaj_prispevok', [\App\Http\Controllers\PostController::class, 'pridaj_prispevokPost'])->name('pridaj_prispevok.post');
Route::post('/pridaj_prispevok/pridaj_moznost_anketa', [\App\Http\Controllers\PollController::class, 'pridaj_moznost_anketaPost'])->name('pridaj_moznost_anketa.post');

#AUTH
Route::get('/prihlasenie', [AuthController::class, 'prihlasenie'])->name('prihlasenie');
Route::post('/prihlasenie', [AuthController::class, 'prihlaseniePost'])->name('prihlasenie.post');
Route::get('/registracia', [AuthController::class, 'registracia'])->name('registracia');
Route::post('/registracia', [AuthController::class, 'registraciaPost'])->name('registracia.post');
Route::get('/odhlasenie', [AuthController::class, 'odhlasenie'])->name('odhlasenie');

Route::get('/profil/{id}', [AuthController::class, 'profilGet'])->name('profil');
Route::get('/profil_uprava', [AuthController::class, 'profil_upravaGet'])->name('profil_uprava');
Route::post('/profil_uprava/pridaj_obrazok', [AuthController::class, 'pridaj_obrazokPost'])->name('pridaj_obrazok.post');
Route::post('/profil_uprava/uloz_obrazok', [AuthController::class, 'uloz_obrazokPost'])->name('uloz_obrazok.post');
Route::post('/profil_uprava/overenie_meno', [AuthController::class, 'overenie_menoPost'])->name('overenie_meno.post');
Route::post('/profil_uprava/overenie_email', [AuthController::class, 'overenie_emailPost'])->name('overenie_email.post');
Route::post('/profil_uprava/nastevenie_udajov', [AuthController::class, 'nastevenie_udajovPost'])->name('nastevenie_udajov.post');
Route::get('/profil_vyhladavanie', [AuthController::class, 'vyhladaj_profil'])->name('vyhladaj_profil');
Route::post('/profil_vyhladavanie', [AuthController::class, 'vyhladaj_profilPost'])->name('vyhladaj_profil.post');
Route::post('/pouzivatel/vymaz', [AuthController::class, 'vymaz_pouzivatelaPost'])->name('vymaz_pouzivatela.post');

Route::get('/moderator/panel', [AuthController::class, 'moderator_panel'])->name('moderator_panel');
Route::post('/moderator/pridaj', [AuthController::class, 'pridaj_moderatoraPost'])->name('moderator_pridaj.post');
Route::post('/moderator/odober', [AuthController::class, 'odober_moderatoraPost'])->name('moderator_odober.post');
Route::get('pravidla', [AuthController::class, 'pravidla'])->name('pravidla');
