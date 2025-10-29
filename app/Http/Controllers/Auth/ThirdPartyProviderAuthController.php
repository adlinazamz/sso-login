<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class ThirdPartyProviderAuthController extends Controller
{
    public function redirect($provider)
    {
        // Validate provider name
        if (!in_array($provider, ['google', 'github', 'facebook', 'linkedin', 'gitlab', 'bitbucket', 'slack', 'x', 'apple'])) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['social_login' => 'Login failed, please try again.']);
        }

        // Find or create user
        $user = User::updateOrCreate(
            ['provider_id' => $socialUser->getId(), 'provider' => $provider],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail() ?? "{$provider}_{$socialUser->getId()}@example.com",
                'avatar' => $socialUser->getAvatar(),
            ]
        );

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
