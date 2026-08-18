<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PanelMedia;
use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Creating and maintaining the Skills Co-op Sessions panels.
 *
 * Panels used to be seeder files: adding one meant a code change and a
 * deploy, which is why Panel 3 sat as a "coming soon" placeholder for weeks
 * after its details were known. They are ordinary database rows, so they
 * belong here.
 *
 * Speakers are managed on the same screen as the panel they spoke at. They
 * are a separate table because people return across panels, but nobody
 * thinks of them as a standalone thing to administer.
 */
class PanelAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.panels.index', [
            'panels' => PanelSession::withCount(['speakers', 'registrations'])
                ->orderByDesc('sort_order')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.panels.form', [
            'panel'    => new PanelSession([
                'status'      => 'upcoming',
                'format'      => 'Online',
                'duration'    => '60 minutes',
                'sort_order'  => (PanelSession::max('sort_order') ?? 0) + 1,
            ]),
            'speakers' => PanelSpeaker::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($validated['tagline'], $validated['sort_order']);

        $panel = PanelSession::create($validated);
        $this->syncSpeakers($request, $panel);
        $this->syncRecording($panel);

        return redirect()
            ->route('admin.panels.index')
            ->with('status', 'Panel created. It is live at ' . route('sessions.show', $panel) . '.');
    }

    public function edit(PanelSession $panel): View
    {
        return view('admin.panels.form', [
            'panel'    => $panel->load('speakers'),
            'speakers' => PanelSpeaker::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, PanelSession $panel): RedirectResponse
    {
        // The slug is deliberately not regenerated on edit. It is the shared
        // link for the panel, and changing it breaks every post pointing here.
        $panel->update($this->validated($request, $panel));
        $this->syncSpeakers($request, $panel);
        $this->syncRecording($panel);

        return redirect()
            ->route('admin.panels.index')
            ->with('status', 'Panel updated.');
    }

    /**
     * Deletion is refused once anyone has registered. Those are real people
     * who asked to attend, and the registration rows point here. Mark the
     * panel past instead, which archives it and keeps the record.
     */
    public function destroy(PanelSession $panel): RedirectResponse
    {
        if ($panel->registrations()->exists()) {
            return redirect()
                ->route('admin.panels.index')
                ->with('error', 'That panel has registrations against it, so it cannot be deleted. Mark it past instead.');
        }

        $panel->speakers()->detach();
        $panel->media()->delete();
        $panel->delete();

        return redirect()
            ->route('admin.panels.index')
            ->with('status', 'Panel deleted.');
    }

    // ── Speakers ─────────────────────────────────────────────────────────────

    public function storeSpeaker(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255', Rule::unique('panel_speakers', 'name')],
            'title'        => ['required', 'string', 'max:255'],
            'company'      => ['nullable', 'string', 'max:255'],
            'bio'          => ['nullable', 'string', 'max:2000'],
            'photo_path'   => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
        ], [
            'name.unique' => 'There is already a speaker with that name. Add them to the panel from the list instead.',
            'title.required' => 'The job title only, without the employer — the company has its own field.',
        ]);

        PanelSpeaker::create($validated);

        return back()->with('status', 'Speaker added. Tick them on a panel to put them on the bill.');
    }

    public function destroySpeaker(PanelSpeaker $speaker): RedirectResponse
    {
        if ($speaker->sessions()->exists()) {
            return redirect()
                ->route('admin.speakers.index')
                ->with('error', 'That speaker is on a panel, so they cannot be deleted. Remove them from the panel first.');
        }

        $speaker->delete();

        return redirect()->route('admin.speakers.index')->with('status', 'Speaker deleted.');
    }

    public function speakers(): View
    {
        return view('admin.panels.speakers', [
            'speakers' => PanelSpeaker::withCount('sessions')->orderBy('name')->get(),
        ]);
    }

    // ── Internals ────────────────────────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?PanelSession $panel = null): array
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'tagline'        => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string', 'max:4000'],
            // Nullable so a panel can be announced before a date is fixed,
            // rather than publishing a date nobody has committed to.
            'event_date'     => ['nullable', 'date'],
            'duration'       => ['nullable', 'string', 'max:100'],
            'format'         => ['nullable', 'string', 'max:100'],
            'eventbrite_url' => ['nullable', 'url', 'max:255'],
            'recording_url'  => ['nullable', 'url', 'max:255'],
            'status'         => ['required', Rule::in(['upcoming', 'live', 'past'])],
            'sort_order'     => ['required', 'integer', 'min:1'],
        ], [
            'tagline.required' => 'The tagline is the topic shown as the headline on the page.',
        ]);

        return $validated;
    }

    /**
     * Attach the ticked speakers, carrying the topic and running order from
     * the form. sync() is authoritative: unticking someone removes them.
     */
    private function syncSpeakers(Request $request, PanelSession $panel): void
    {
        $selected = $request->input('speakers', []);
        $topics   = $request->input('speaker_topic', []);
        $orders   = $request->input('speaker_order', []);

        $sync = [];
        foreach ($selected as $i => $speakerId) {
            $sync[(int) $speakerId] = [
                'topic'      => $topics[$speakerId] ?? null,
                'sort_order' => (int) ($orders[$speakerId] ?? $i + 1),
            ];
        }

        $panel->speakers()->sync($sync);
    }

    /**
     * Keep the archive video in step with recording_url.
     *
     * The sessions page embeds from panel_media, not from the column, so a
     * recording added here would otherwise save without ever appearing.
     */
    private function syncRecording(PanelSession $panel): void
    {
        if (! $panel->recording_url) {
            $panel->media()->where('type', 'video')->delete();
            return;
        }

        $panel->media()->where('type', 'video')
            ->where('url', '!=', $panel->recording_url)
            ->delete();

        PanelMedia::updateOrCreate(
            [
                'panel_session_id' => $panel->id,
                'type'             => 'video',
                'url'              => $panel->recording_url,
            ],
            [
                'caption'    => 'Full recording: ' . $panel->tagline,
                'sort_order' => 1,
            ]
        );
    }

    private function uniqueSlug(string $tagline, int $sortOrder): string
    {
        $base = 'panel-' . $sortOrder . '-' . Str::slug($tagline);
        $slug = $base;
        $n    = 2;

        while (PanelSession::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }
}
