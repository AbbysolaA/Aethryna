<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect the user to Google's OAuth page.
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google.
     */
    public function handleProviderCallback()
    {
        $socialUser = Socialite::driver('google')->stateless()->user();

        // Find or create a user based on email
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Google User',
                'password' => bcrypt(Str::random(16)),
                // Optional extra fields you may store:
                // 'google_id' => $socialUser->getId(),
                // 'avatar' => $socialUser->getAvatar(),
            ]
        );

        Auth::login($user, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
