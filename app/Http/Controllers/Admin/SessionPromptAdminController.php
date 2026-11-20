<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PanelSession;
use App\Models\SessionPrompt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** The prompts behind a session's live builds, managed from its edit page. */
class SessionPromptAdminController extends Controller
{
    public function store(Request $request, PanelSession $panel): RedirectResponse
    {
        $panel->prompts()->create($this->validated($request, $panel, 'newPrompt'));

        return redirect()->to(route('admin.panels.edit', $panel).'#prompts')
            ->with('status', 'Prompt added. It shows on the session page straight away.');
    }

    public function update(Request $request, SessionPrompt $prompt): RedirectResponse
    {
        $prompt->update($this->validated($request, $prompt->panelSession, 'prompt'.$prompt->id));

        return redirect()->to(route('admin.panels.edit', $prompt->panel_session_id).'#prompt-'.$prompt->id)
            ->with('status', 'Prompt saved.');
    }

    public function destroy(SessionPrompt $prompt): RedirectResponse
    {
        $panelId = $prompt->panel_session_id;
        $prompt->delete();

        return redirect()->to(route('admin.panels.edit', $panelId).'#prompts')
            ->with('status', 'Prompt removed.');
    }

    /**
     * Scoped error bag per form: the edit page holds one form per prompt,
     * and a page-wide bag would show one prompt's error under all of them.
     */
    private function validated(Request $request, PanelSession $panel, string $bag): array
    {
        return $request->validateWithBag($bag, [
            'title'            => ['required', 'string', 'max:255'],
            'body'             => ['required', 'string', 'max:60000'],
            'panel_speaker_id' => ['nullable', Rule::in($panel->speakers()->pluck('panel_speakers.id')->all())],
            'sort_order'       => ['nullable', 'integer', 'min:1', 'max:999'],
        ], [
            'title.required' => 'Give the prompt a short title, for example "Step 1: set the context".',
            'body.required'  => 'Paste the prompt itself.',
        ]) + ['sort_order' => $request->integer('sort_order') ?: ($panel->prompts()->max('sort_order') + 1)];
    }
}
