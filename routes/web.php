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

Route::post('/dashboard/webhook/toggle', [DashboardController::class, 'toggleWebhook'])
    ->middleware('auth')->name('dashboard.webhook.toggle');

Route::post('/push-readme', [PullRequestController::class, 'push'])->name('readme.push')->middleware('auth');

Route::get('/run-migrations-temp', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    return 'Migrations ran successfully! Output: <br><pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
});

// GitHub Webhook Route (Exempt from CSRF in bootstrap/app.php)
Route::post('/api/webhooks/github', [\App\Http\Controllers\WebhookController::class, 'handle'])
    ->middleware(\App\Http\Middleware\VerifyGithubWebhookSignature::class)
    ->name('webhook.github.payload');