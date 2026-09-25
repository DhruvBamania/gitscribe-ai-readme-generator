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
})->name('login');

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

Route::post('/generate-wiki', [\App\Http\Controllers\WikiController::class, 'generate'])->middleware('auth')
    ->name('wiki.generate');

Route::post('/dashboard/webhook/toggle', [DashboardController::class, 'toggleWebhook'])
    ->middleware('auth')->name('dashboard.webhook.toggle');

Route::post('/push-readme', [PullRequestController::class, 'push'])->name('readme.push')->middleware('auth');

Route::get('/run-migrations-temp', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    return 'Migrations ran successfully! Output: <br><pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
});

Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    return 'Cache cleared successfully! You can now test the API again.';
});

// GitHub Webhook Route (Exempt from CSRF in bootstrap/app.php)
Route::post('/api/webhooks/github', [\App\Http\Controllers\WebhookController::class, 'handle'])
    ->middleware(\App\Http\Middleware\VerifyGithubWebhookSignature::class)
    ->name('webhook.github.payload'); 