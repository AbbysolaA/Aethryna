<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Accepting a staff invitation, and what the page says when it goes wrong.
 *
 * Written after the first real content writer hit "This password reset token
 * is invalid" on his welcome page: the flow was mechanically fine (an older
 * email after a resend), but the words described the plumbing rather than
 * his situation, and the greeting called him by his title.
 */
class StaffInviteAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    private function invitee(): User
    {
        return User::factory()->create([
            'name'  => 'Mr Adeoba',
            'email' => 'writer@example.com',
            'role'  => 'editor',
        ]);
    }

    public function test_a_valid_invite_sets_the_password_and_signs_them_in(): void
    {
        $user = $this->invitee();
        $token = Password::broker('invites')->createToken($user);

        $this->post('/staff/invite', [
            'token'                 => $token,
            'email'                 => 'writer@example.com',
            'password'              => 'a-proper-password-1',
            'password_confirmation' => 'a-proper-password-1',
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertAuthenticatedAs($user->fresh());
    }

    /**
     * A resend replaces the token, so the older email's link must fail, and
     * fail in words that tell the invitee what to actually do.
     */
    public function test_a_superseded_link_fails_in_invitation_words(): void
    {
        $user = $this->invitee();
        $oldToken = Password::broker('invites')->createToken($user);
        Password::broker('invites')->createToken($user);

        $response = $this->from('/staff/invite/'.$oldToken)->post('/staff/invite', [
            'token'                 => $oldToken,
            'email'                 => 'writer@example.com',
            'password'              => 'a-proper-password-1',
            'password_confirmation' => 'a-proper-password-1',
        ]);

        $response->assertSessionHasErrors('email');

        $message = session('errors')->first('email');
        $this->assertStringContainsString('invitation link', $message);
        $this->assertStringContainsString('newest email', $message);
        $this->assertStringNotContainsString('reset token', $message);
        $this->assertGuest();
    }

    public function test_the_greeting_uses_the_whole_name(): void
    {
        $user = $this->invitee();
        $token = Password::broker('invites')->createToken($user);

        $this->get('/staff/invite/'.$token.'?email=writer@example.com')
            ->assertOk()
            ->assertSee('Welcome, Mr Adeoba')
            ->assertDontSee('Welcome, Mr<');
    }

    public function test_a_wrong_email_fails_without_leaking_whether_it_exists(): void
    {
        $user = $this->invitee();
        $token = Password::broker('invites')->createToken($user);

        $this->post('/staff/invite', [
            'token'                 => $token,
            'email'                 => 'somebody-else@example.com',
            'password'              => 'a-proper-password-'.Str::random(4),
            'password_confirmation' => null,
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }
}
