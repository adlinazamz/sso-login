<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ThirdPartyProviderAuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::view('/privacy-policy', 'profile.privacy-policy')->name('profile.privacy-policy');
    Route::view('/terms-of-service', 'profile.terms-of-service')->name('profile.terms-of-service');
});

require __DIR__.'/auth.php';

// Third-party authentication routes
Route::get('/auth/{provider}/redirect', [ThirdPartyProviderAuthController::class, 'redirect'])->name('auth.provider.redirect');
Route::get('/auth/{provider}/callback', [ThirdPartyProviderAuthController::class, 'callback'])->name('auth.provider.callback');

Route::get('/verify-email', function(){
    return view('auth.verify-email');
})->name('verify-email');
