<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProducerSpaceController;
use App\Http\Controllers\PublicController;
use App\Livewire\Member\ContractSubscription;
use App\Livewire\Member\GroupOrderForm;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Site public
Route::get('/', [PublicController::class, 'home'])->name('accueil');
Route::get('/l-amap', [PublicController::class, 'amap'])->name('amap');
Route::get('/producteurs', [PublicController::class, 'producers'])->name('producteurs.index');
Route::get('/producteurs/{producer:slug}', [PublicController::class, 'producer'])->name('producteurs.show');
Route::get('/calendrier', [PublicController::class, 'calendar'])->name('calendrier');
Route::get('/adherer', [PublicController::class, 'join'])->name('adherer');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,10')->name('contact.store');
Route::get('/infos/{slug}', [PublicController::class, 'page'])->name('page');

// Comptes
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::get('/mot-de-passe-oublie', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [AuthController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/nouveau-mot-de-passe/{token}', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/nouveau-mot-de-passe', [AuthController::class, 'reset'])->name('password.update');
});
Route::post('/deconnexion', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Espace adhérent
Route::middleware('auth')->prefix('mon-espace')->name('espace.')->group(function () {
    Route::get('/', [MemberController::class, 'dashboard'])->name('index');
    Route::get('/contrats', [MemberController::class, 'contracts'])->name('contrats.index');
    Route::livewire('/contrats/{offer}', ContractSubscription::class)->name('contrats.show');
    Route::get('/commandes-groupees', [MemberController::class, 'groupPurchases'])->name('commandes-groupees.index');
    Route::livewire('/commandes-groupees/{purchase:slug}', GroupOrderForm::class)->name('commandes-groupees.show');
    Route::get('/calendrier', [MemberController::class, 'calendar'])->name('calendrier');
    Route::get('/paiements', [MemberController::class, 'payments'])->name('paiements');
    Route::get('/echanges', [ExchangeController::class, 'show'])->name('echanges');
    Route::post('/echanges', [ExchangeController::class, 'store'])->middleware('throttle:10,60')->name('echanges.store');
    Route::get('/compte', [MemberController::class, 'account'])->name('compte');
    Route::put('/compte', [MemberController::class, 'updateAccount'])->name('compte.update');
});

// Espace producteur
Route::middleware(['auth', 'role:producteur'])->prefix('espace-producteur')->name('producteur.')->group(function () {
    Route::get('/', [ProducerSpaceController::class, 'index'])->name('index');
    Route::get('/distributions/{distribution}/recapitulatif', [ProducerSpaceController::class, 'summary'])->name('recapitulatif');
    Route::get('/distributions/{distribution}/emargement', [ProducerSpaceController::class, 'signInSheets'])->name('emargement');
    Route::post('/paiements/{installment}', [ProducerSpaceController::class, 'mark'])->name('pointage');
    Route::get('/contrats/{offer}/adherents.{format}', [ProducerSpaceController::class, 'subscribers'])->whereIn('format', ['csv', 'pdf'])->name('adherents');
});

// Prototype uniquement : connexion en un clic aux comptes de démonstration (jamais en production).
if (app()->isLocal()) {
    Route::get('/demo/connexion/{email}', function (string $email) {
        abort_unless(str_ends_with($email, '@exemple.fr'), 404);
        auth()->login(User::where('email', $email)->firstOrFail());
        request()->session()->regenerate();

        $to = (string) request('vers');

        return redirect(str_starts_with($to.'/', url('/').'/') ? $to : route('espace.index'));
    })->name('demo.login');
}
