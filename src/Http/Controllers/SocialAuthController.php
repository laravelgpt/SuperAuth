<?php

namespace SuperAuth\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use SuperAuth\Models\User;
use SuperAuth\Models\SocialAccount;

class SocialAuthController extends Controller
{
    /**
     * Redirect to social provider
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle social provider callback
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Find or create user
            $user = $this->findOrCreateUser($socialUser, $provider);
            
            // Log the user in
            Auth::login($user, true);
            
            return redirect()->intended('/dashboard');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Social authentication failed.');
        }
    }

    /**
     * Find or create user from social data
     */
    protected function findOrCreateUser($socialUser, $provider)
    {
        // Check if social account exists
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            return $socialAccount->user;
        }

        // Check if user exists by email
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(str_random(16)),
                'email_verified_at' => now(),
            ]);
        }

        // Create social account
        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'avatar' => $socialUser->getAvatar(),
            'data' => $socialUser->getRaw(),
        ]);

        return $user;
    }

    /**
     * API redirect to social provider
     */
    public function apiRedirectToProvider(Request $request, $provider)
    {
        $redirectUrl = Socialite::driver($provider)->redirect()->getTargetUrl();
        
        return response()->json([
            'redirect_url' => $redirectUrl
        ]);
    }

    /**
     * API handle social provider callback
     */
    public function apiHandleProviderCallback(Request $request, $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            $user = $this->findOrCreateUser($socialUser, $provider);
            
            $token = $user->createToken('auth-token')->plainTextToken;
            
            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'Social authentication successful'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Social authentication failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
