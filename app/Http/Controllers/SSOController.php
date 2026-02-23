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
    // redirect user to foodpanda for OAuth login
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

    // handle callback after user authorizes on foodpanda
    public function handleCallback(Request $request)
    {
        // check state matches
        if ($request->state !== $request->session()->get('oauth_state')) {
            return redirect('/')->withErrors(['error' => 'Invalid state']);
        }

        if ($request->has('error')) {
            return redirect('/')->withErrors(['error' => 'Authorization denied']);
        }

        try {
            // exchange code for token
            $response = Http::post(env('FOODPANDA_URL') . '/oauth/token', [
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'client_id' => env('FOODPANDA_CLIENT_ID'),
                'client_secret' => env('FOODPANDA_CLIENT_SECRET'),
                'redirect_uri' => env('FOODPANDA_REDIRECT_URI'),
            ]);

            if ($response->failed()) {
                return redirect('/')->withErrors(['error' => 'Token exchange failed']);
            }

            $tokenData = $response->json();

            // get user info from foodpanda
            $userResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenData['access_token'],
            ])->get(env('FOODPANDA_URL') . '/api/user');

            if ($userResponse->failed()) {
                return redirect('/')->withErrors(['error' => 'Could not get user info']);
            }

            $userData = $userResponse->json();

            // create or update user in our db
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make(Str::random(16)),
                ]
            );

            // save token and login
            $request->session()->put('foodpanda_token', $tokenData['access_token']);
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Logged in via Foodpanda!');

        } catch (\Exception $e) {
            return redirect('/')->withErrors(['error' => 'SSO failed: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('foodpanda_token');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out');
    }
}
