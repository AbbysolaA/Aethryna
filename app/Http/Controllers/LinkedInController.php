<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LinkedInController extends Controller
{
    /**
     * Redirect the user to LinkedIn's OAuth page.
     */
    public function redirectToProvider()
    {
        return Socialite::driver('linkedin-openid')->redirect();
    }

    /**
     * Handle callback from LinkedIn.
     */
    public function handleProviderCallback()
    {
        $socialUser = Socialite::driver('linkedin-openid')->stateless()->user();

        // Find or create a user based on email
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName() ?? 'LinkedIn User',
                'password' => bcrypt(Str::random(16)),
            ]
        );

        Auth::login($user, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
