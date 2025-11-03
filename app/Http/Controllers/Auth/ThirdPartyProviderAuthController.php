<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class ThirdPartyProviderAuthController extends Controller
{
    public function redirect($provider)
    {
        // Validate provider name
        if (!in_array($provider, ['google', 'github', 'facebook', 'linkedin', 'gitlab', 'bitbucket', 'slack', 'x', 'apple'])) {
            abort(404);
        }
        if ($provider === 'linkedin') {
            $url = Socialite::driver('linkedin')
            ->scopes(['r_liteprofile', 'r_emailaddress'])
            ->redirect()
            ->getTargetUrl();

        Log::info('LinkedIn redirect URL', ['url' => $url]);

        return redirect($url);
        }
        
        $url = Socialite::driver($provider)
            ->stateless()
            ->redirect()
            ->getTargetUrl();
        $url .= '&prompt=login'; // Force login prompt

        return redirect($url);
    }

    public function callback($provider)
    {
        Log::info('Callback hit', ['provider' => $provider]);
        try {
            $socialUser = Socialite::driver($provider)->stateless()->setHttpClient(new Client(['verify'=>'C:/laragon/bin/php/php-8.1.10-Win32-vs16-x64/extras/ssl/cacert.pem']))->user();
            Log::info('Socialite user', ['user' => $socialUser]);
        } catch (\Exception $e) {
            Log::error('Socialite error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'code' => $e->getCode(),
                'exception' => get_class($e),
            ]);
            return redirect()->route('login')->withErrors([
                'social_login' => 'Login failed, please try again.'
            ]);
        }
        $user = User::where('provider_id', $socialUser->getId())->where('provider', $provider)->first();

        if (!$user && $socialUser->getEmail()) {
            $user = User::where('email', $socialUser->getEmail())->first();
            if ($user) {
                // Link existing user with provider
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                ]);
            }
        }
        if (!$user){
        // Find or create user
            $user = User::create(
                [
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail() ?? "{$provider}_{$socialUser->getId()}@example.com",
                    'avatar' => $socialUser->getAvatar(),
                    'password' => bcrypt(str()->random(16)), // Random password
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(), 

                ]
            );
        }

        Auth::login($user);
        session(['last_oauth_provider' => $provider]);
        return redirect()->intended('/dashboard');
    }
}
