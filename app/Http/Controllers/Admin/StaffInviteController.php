<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StaffInvitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Inviting people who cannot sign themselves up.
 *
 * Admins, coaches, mentors and the safeguarding lead all hold roles that grant
 * access to other people's records, so none of them can be self-served. Until
 * now the only way to create one was a developer running tinker, which meant
 * onboarding a safeguarding lead required SSH access.
 *
 * The admin never types or sees a password. The account is created with an
 * unusable one and the invitee sets their own through a signed link.
 */
class StaffInviteController extends Controller
{
    /** Broker with the seven-day window, not the sixty-minute reset one. */
    private const BROKER = 'invites';

    public function index(): View
    {
        return view('admin.staff.index', [
            'staff'      => User::whereIn('role', array_keys(User::staffRoles()))
                ->orderBy('role')
                ->orderBy('name')
                ->get(),
            'staffRoles' => User::staffRoles(),
        ]);
    }

    public function create(): View
    {
        return view('admin.staff.create', [
            'staffRoles' => User::staffRoles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:' . User::class . ',email'],
            'role'  => ['required', Rule::in(array_keys(User::staffRoles()))],
        ], [
            'email.unique' => 'That address already has an account. Change their role from the list instead of inviting them again.',
        ]);

        $user = User::create([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            // Unusable by design. The only way in is the invite link, and the
            // only person who ever knows the password is the invitee.
            'password' => Str::random(64),
            'role'     => $validated['role'],
            // The invite link proves they control the address, so a separate
            // verification round trip would be asking the same question twice.
            'email_verified_at' => now(),
        ]);

        return $this->dispatchInvite($user, 'Invitation sent to ' . $user->email . '.');
    }

    /**
     * Send the link again. For an expired invite, or one that never arrived.
     */
    public function resend(User $user): RedirectResponse
    {
        abort_unless($this->isStaff($user), 404);

        return $this->dispatchInvite($user, 'Invitation resent to ' . $user->email . '.');
    }

    /**
     * Change what someone can reach. Kept here rather than in user management
     * because these are the roles that carry access to other people's records.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($this->isStaff($user), 404);

        $validated = $request->validate([
            'role' => ['required', Rule::in(array_keys(User::staffRoles()))],
        ]);

        // Removing your own admin access mid-session locks you out of the
        // screen you are standing on, so it is refused rather than explained
        // afterwards.
        if ($user->id === Auth::id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'You cannot change your own role. Ask another admin.');
        }

        $user->forceFill(['role' => $validated['role']])->save();

        return back()->with('status', $user->name . ' is now ' . User::staffRoles()[$validated['role']] . '.');
    }

    private function dispatchInvite(User $user, string $success): RedirectResponse
    {
        $token = Password::broker(self::BROKER)->createToken($user);

        try {
            Mail::to($user->email)->send(new StaffInvitation(
                user: $user,
                acceptUrl: route('staff.invite.show', ['token' => $token, 'email' => $user->email]),
                invitedBy: Auth::user()?->name ?? 'Skills Co-op',
            ));
        } catch (\Throwable $e) {
            Log::error('Staff invitation email failed to send.', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'error'   => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.staff.index')
                ->with('error', 'The account was created but the invitation email could not be sent. Use Resend once mail is working.');
        }

        return redirect()->route('admin.staff.index')->with('status', $success);
    }

    private function isStaff(User $user): bool
    {
        return array_key_exists($user->role, User::staffRoles());
    }
}
