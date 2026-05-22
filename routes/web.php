<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PullRequestController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::get('/auth/github', [GithubController::class, 'redirect']);
Route::get('/auth/github/callback', [GithubController::class, 'callback']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect('/');
})->name('logout');

Route::get('/generate-readme/{owner}/{repo}', [DashboardController::class, 'generate'])->middleware('auth')
    ->name('readme.generate');

Route::post('/push-readme', [PullRequestController::class, 'push'])->name('readme.push')->middleware('auth');