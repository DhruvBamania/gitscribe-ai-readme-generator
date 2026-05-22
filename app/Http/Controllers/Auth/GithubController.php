<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GithubController extends Controller
{
    //
    public function redirect()
    {
        return Socialite::driver('github')->scopes(['repo']) ->redirect();
    }

    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            $user = User::updateOrCreate([
                'github_id' => $githubUser->id,
            ], [
                'name' => $githubUser->name ?? $githubUser->nickname,
                'email' => $githubUser->email ?? $githubUser->nickname . '@github.com', 
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
                'password' => bcrypt(Str::random(24)), 
            ]);

            Auth::login($user);

            return redirect('/dashboard');
            
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Failed to authenticate with GitHub.');
        }
    }
}
