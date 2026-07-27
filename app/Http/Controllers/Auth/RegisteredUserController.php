<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Someone registering purely to answer a volunteer offer gets the
        // volunteer welcome on acceptance instead. Sending the learner welcome
        // as well would tell a new mentor to go and take the pathway
        // assessment, which is not their journey.
        if (! $request->session()->pull('claiming_volunteer_offer', false)) {
            $user->notify(new WelcomeEmail());
        }

        // intended() rather than a bare redirect so a user who arrived from a
        // gated page (a volunteer offer link, say) lands back where they were
        // headed. Falls through to the dashboard when nothing was intended,
        // which is the previous behaviour.
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
