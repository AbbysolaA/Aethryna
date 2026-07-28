<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * Accepting a staff invitation.
 *
 * Mechanically a password reset, deliberately: the token half is Laravel's,
 * which is well tested, rather than something hand rolled. What differs is the
 * broker, which allows seven days instead of sixty minutes, and the wording,
 * because being invited and forgetting your password are not the same event.
 */
class AcceptInviteController extends Controller
{
    private const BROKER = 'invites';

    public function show(Request $request, string $token): View
    {
        $email = (string) $request->query('email', '');

        return view('auth.accept-invite', [
            'token' => $token,
            'email' => $email,
            // Only for the greeting. Access still rests entirely on the token,
            // so a wrong or stale email simply fails at store().
            'user'  => User::where('email', $email)->first(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::broker(self::BROKER)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                // An invite proves control of the address, so someone accepting
                // one is verified whether or not they were before.
                if ($user->email_verified_at === null) {
                    $user->email_verified_at = now();
                }

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PasswordReset) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }

        // Sign them in and drop them where their role belongs, rather than at a
        // login form they have no reason to see again.
        $user = User::where('email', $request->string('email'))->firstOrFail();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route($user->homeRoute())
            ->with('status', 'Welcome to Skills Co-op. Your account is ready.');
    }
}
