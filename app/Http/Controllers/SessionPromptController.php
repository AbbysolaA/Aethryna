<?php

namespace App\Http\Controllers;

use App\Models\PanelSession;
use App\Models\PromptUnlock;
use App\Models\SessionRegistration;
use App\Services\EmailOctopusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

/**
 * Name and email in, the session's prompts shown straight away.
 *
 * Shown on the page rather than emailed, so getting them never waits on
 * an inbox. Joining the mailing list is a separate, unticked choice: only
 * people who tick it join the contacts bank and EmailOctopus.
 */
class SessionPromptController extends Controller
{
    /** Remembers unlocked sessions so a returning visitor is not asked again. */
    public const COOKIE = 'sc_prompts';

    public function __construct(protected EmailOctopusService $emailOctopus)
    {
    }

    /** @return array<int, int> Session ids this visitor has unlocked. */
    public static function unlockedIds(Request $request): array
    {
        $ids = json_decode((string) $request->cookie(self::COOKIE), true);

        return is_array($ids) ? array_values(array_filter($ids, 'is_int')) : [];
    }

    public function unlock(Request $request, PanelSession $panelSession): RedirectResponse
    {
        $back = route('sessions.show', $panelSession).'#prompts';

        // Same honeypot convention as the other public forms.
        if ($request->filled('pr_reference')) {
            Log::info('Prompt unlock honeypot triggered', ['ip' => $request->ip()]);

            return redirect()->to($back);
        }

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255'],
            'keep_posted' => ['nullable', 'boolean'],
        ], [
            'name.required'  => 'Please tell us your name.',
            'email.required' => 'Please give an email address.',
        ]);

        abort_if($panelSession->prompts()->doesntExist(), 404);

        $email = strtolower(trim($validated['email']));
        $optedIn = (bool) ($validated['keep_posted'] ?? false);

        $unlock = PromptUnlock::firstOrNew([
            'panel_session_id' => $panelSession->id,
            'email'            => $email,
        ]);
        $unlock->name = $validated['name'];
        // Once opted in, a later unlock without the tick does not undo it:
        // withdrawing is a reply to us, not a missed checkbox.
        $unlock->opted_in = $unlock->opted_in || $optedIn;
        $unlock->save();

        if ($optedIn) {
            $this->joinTheBank($validated['name'], $email, $panelSession);
        }

        $ids = array_values(array_unique([...self::unlockedIds($request), $panelSession->id]));
        Cookie::queue(self::COOKIE, json_encode($ids), 60 * 24 * 365);

        return redirect()->to($back)->with('prompts_unlocked', true);
    }

    /** Into the contacts bank, so future recaps and sessions reach them. */
    private function joinTheBank(string $name, string $email, PanelSession $panelSession): void
    {
        if (! SessionRegistration::whereRaw('lower(email) = ?', [$email])->exists()) {
            SessionRegistration::create([
                'panel_session_id' => null,
                'name'             => $name,
                'email'            => $email,
                'interest_type'    => 'curious',
                'notes'            => 'Asked for the prompts: '.$panelSession->tagline,
                'consented_at'     => now(),
            ]);
        }

        $parts = preg_split('/\s+/', trim($name), 2);
        $this->emailOctopus->subscribe(
            $email,
            ['FirstName' => $parts[0] ?? '', 'LastName' => $parts[1] ?? ''],
            ['sessions', 'prompts'],
        );
    }
}
