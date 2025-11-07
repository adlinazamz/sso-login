<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Illuminate\Support\Facades\Http;

class ThirdPartyProviderAuthController extends Controller
{
    /**
     * Providers that use OpenID Connect (return id_token)
     */
    protected array $oidcProviders = ['google', 'apple'];

    /**
     * Redirect to the provider's authentication page
     */
    public function redirect(Request $request, string $provider)
    {
        if (!in_array($provider, ['google', 'facebook', 'linkedin', 'x', 'apple'])) {
            abort(404);
        }

        // Define provider scopes
        $scopes = match($provider) {
            'google' => ['openid', 'email', 'profile'],
            'apple' => ['name', 'email'],
            'linkedin' => ['r_liteprofile', 'r_emailaddress'],
            'facebook' => ['email', 'public_profile'],
            'x' => ['users.read', 'tweet.read'],
            default => ['openid', 'email', 'profile'],
        };

        // Google: requires consent screen & offline token
        if ($provider === 'google') {
            $driver = Socialite::driver('google')
                ->scopes($scopes)
                ->with(['access_type' => 'offline', 'prompt' => 'consent']);
            $url = $driver->redirect()->getTargetUrl();
        } else {
            $driver = Socialite::driver($provider)->scopes($scopes);
            $url = $driver->stateless()->redirect()->getTargetUrl();
        }

        Log::info("Redirecting to provider {$provider}", ['url' => $url]);
        return redirect($url);
    }

    /**
     * Handle callback from the provider
     */
    public function callback(Request $request, string $provider)
    {
        Log::info("Callback hit for {$provider}", [
            'state' => $request->input('state'),
        ]);

        try {
            // --- OIDC HANDLING (Google / Apple) ---
            if (in_array($provider, $this->oidcProviders)) {
                if ($provider === 'google') {
                    // Step 1: Exchange code for token (contains id_token)
                    $tokenResponse = Socialite::driver('google')
                        ->stateless()
                        ->getAccessTokenResponse($request->input('code'));

                    $idToken = $tokenResponse['id_token'] ?? null;
                    if (!$idToken) {
                        throw new \Exception('Google did not return id_token');
                    }

                    // Step 2: Verify the id_token (JWT)
                    $oidcUser = $this->verifyOidcToken($idToken, 'google');

                    // Step 3: Fetch userinfo for avatar, etc.
                    $googleUser = Socialite::driver('google')
                        ->stateless()
                        ->userFromToken($tokenResponse['access_token']);

                    $email = $oidcUser['email'] ?? $googleUser->getEmail();
                    $name = $oidcUser['name'] ?? $googleUser->getName();
                    $avatar = $googleUser->getAvatar();
                    $providerId = $oidcUser['sub'] ?? $googleUser->getId();
                } elseif ($provider === 'apple') {
                    // TODO: implement Apple OIDC if needed
                    throw new \Exception('Apple OIDC not yet implemented');
                }
            }

            // --- STANDARD OAUTH PROVIDERS ---
            else {
                $socialUser = Socialite::driver($provider)
                    ->stateless()
                    ->setHttpClient(new Client([
                        'verify' => 'C:/laragon/bin/php/php-8.1.10-Win32-vs16-x64/extras/ssl/cacert.pem'
                    ]))
                    ->user();

                Log::info("Socialite user fetched for {$provider}", [
                    'id' => $socialUser->getId(),
                    'email' => $socialUser->getEmail(),
                    'name' => $socialUser->getName(),
                    'nickname' => $socialUser->getNickname(),
                ]);

                $email = $socialUser->getEmail();
                $name = $socialUser->getName() ?? $socialUser->getNickname();
                $avatar = $socialUser->getAvatar();
                $providerId = $socialUser->getId();
            }

            // --- EMAIL VERIFICATION CHECK---
            $emailVerified = false;

            if(in_array($provider, ['google', 'apple'])){
                $emailVerified = isset($oidcUser['email_verified']) ? (bool)$oidcUser['email_verified'] : false;
            } elseif ($provider === 'x'){
                $emailVerified = false;
            }
            if(!$emailVerified){
                Log::warning("Unverified email detected for {$provider}", ['email' => $email]);

                // If user exists, force them to verify manually
                $existingUser = User::where('email', $email)->first();
                if ($existingUser && !$existingUser->hasVerifiedEmail()) {
                    Auth::logout();
                    return redirect()->route('verification.notice')
                        ->with('status', 'Please verify your email to continue.');
                }
            }

            // --- USER MANAGEMENT ---
            $user = User::where('provider', $provider)
                ->where('provider_id', $providerId)
                ->first();

            if (!$user && $email) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $providerId,
                        'avatar' => $avatar,
                    ]);
                }
            }

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email ?? "{$provider}_{$providerId}@example.com",
                    'avatar' => $avatar,
                    'password' => bcrypt(Str::random(16)),
                    'provider' => $provider,
                    'provider_id' => $providerId,
                ]);
                if (!empty($emailVerified) && !$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                    Log::info("Verification email sent to {$user->email}");
                }
            }

            // --- LOGIN + JWT ---
            Auth::login($user);
            session(['last_oauth_provider' => $provider]);

            $jwt = $this->issueSessionToken($user);
            session(['session_jwt' => $jwt]);
            $user->update(['session_token' => $jwt]);

            Log::info("User logged in and JWT issued", [
                'user_id' => $user->id,
                'provider' => $provider
            ]);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            Log::error("Social login failed for {$provider}", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('login')->withErrors([
                'social_login' => 'Login failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Verify OIDC id_token for Google/Apple
     */
    protected function verifyOidcToken(string $idToken, string $provider): array
    {
        try {
            $jwksUri = match ($provider) {
                'google' => 'https://www.googleapis.com/oauth2/v3/certs',
                'apple' => 'https://appleid.apple.com/auth/keys',
            };

            $jwks = Http::get($jwksUri)->json();
            $keys = JWK::parseKeySet($jwks);

            // ✅ Allow 60-second clock skew tolerance
            JWT::$leeway = 60;

            $decoded = JWT::decode($idToken, $keys);

            // Validate issuer and audience
            $expectedIssuer = match ($provider) {
                'google' => 'https://accounts.google.com',
                'apple' => 'https://appleid.apple.com',
            };

            if ($decoded->iss !== $expectedIssuer) {
                throw new \Exception('Issuer mismatch');
            }

            $expectedAud = config("services.{$provider}.client_id");
            if (is_array($decoded->aud) && !in_array($expectedAud, $decoded->aud)) {
                throw new \Exception('Audience mismatch');
            } elseif (is_string($decoded->aud) && $decoded->aud !== $expectedAud) {
                throw new \Exception('Audience mismatch');
            }

            return [
                'sub' => $decoded->sub ?? null,
                'email' => $decoded->email ?? null,
                'name' => $decoded->name ?? null,
                'email_verified' => $decoded->email_verified ?? false,
                'avatar' => null,
            ];
        } catch (\Exception $e) {
            Log::error("OIDC verification error for {$provider}", ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Issue JWT for authenticated session
     */
    protected function issueSessionToken($user): string
    {
        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'email' => $user->email,
            'iat' => now()->timestamp,
            'exp' => now()->addHours(1)->timestamp,
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }
}
