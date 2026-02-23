<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SSOController extends Controller
{
    /**
     * Redirect to Foodpanda OAuth server
     */
    public function redirectToFoodpanda(Request $request)
    {
        $state = Str::random(40);
        $request->session()->put('oauth_state', $state);

        $query = http_build_query([
            'client_id' => env('FOODPANDA_CLIENT_ID'),
            'redirect_uri' => env('FOODPANDA_REDIRECT_URI'),
            'response_type' => 'code',
            'state' => $state,
        ]);

        return redirect(env('FOODPANDA_URL') . '/oauth/authorize?' . $query);
    }

    /**
     * Handle callback from Foodpanda OAuth server
     */
    public function handleCallback(Request $request)
    {
        // Verify state
        if ($request->state !== $request->session()->get('oauth_state')) {
            return redirect('/')->withErrors(['error' => 'Invalid state parameter']);
        }

        if ($request->has('error')) {
            return redirect('/')->withErrors(['error' => 'Authorization was denied']);
        }

        // Exchange authorization code for access token
        try {
            $response = Http::post(env('FOODPANDA_URL') . '/oauth/token', [
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'client_id' => env('FOODPANDA_CLIENT_ID'),
                'client_secret' => env('FOODPANDA_CLIENT_SECRET'),
                'redirect_uri' => env('FOODPANDA_REDIRECT_URI'),
            ]);

            if ($response->failed()) {
                return redirect('/')->withErrors(['error' => 'Failed to get access token']);
            }

            $tokenData = $response->json();

            // Get user info using access token
            $userResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenData['access_token'],
            ])->get(env('FOODPANDA_URL') . '/api/user');

            if ($userResponse->failed()) {
                return redirect('/')->withErrors(['error' => 'Failed to get user info']);
            }

            $userData = $userResponse->json();

            // Find or create user locally
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make(Str::random(16)),
                ]
            );

            // Store token in session
            $request->session()->put('foodpanda_token', $tokenData['access_token']);

            // Login user
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Logged in via Foodpanda SSO!');

        } catch (\Exception $e) {
            return redirect('/')->withErrors(['error' => 'SSO Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Logout from both systems
     */
    public function logout(Request $request)
    {
        $request->session()->forget('foodpanda_token');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully');
    }
}
