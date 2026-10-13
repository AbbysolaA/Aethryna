<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PanelMedia;
use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'linkedin_url' => ['nullable', 'url', 'max:255'],

            // A speaker added by hand usually arrives with a session in mind
            // and a title for their slot, so both are captured here rather
            // than on a second screen.
            'panel_session_id' => ['nullable', 'exists:panel_sessions,id'],
            'topic'            => ['nullable', 'string', 'max:255'],
            'photo'            => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ], [
            'name.unique' => 'There is already a speaker with that name. Add them to the panel from the list instead.',
            'photo.max'   => 'Keep photos under 8MB. They are resized on upload anyway.',
        ]);

        $speaker = PanelSpeaker::create(
            collect($validated)->only(['name', 'title', 'company', 'bio', 'linkedin_url'])->all()
        );

        if ($request->hasFile('photo')) {
            $published = \App\Support\SpeakerPhotos::publish(
                (string) file_get_contents($request->file('photo')->getRealPath()),
                $speaker->name
            );

            if ($published) {
                $speaker->forceFill(['photo_path' => $published])->save();
            }
        }

        if (! empty($validated['panel_session_id'])) {
            $panel = \App\Models\PanelSession::findOrFail($validated['panel_session_id']);

            $panel->speakers()->syncWithoutDetaching([
                $speaker->id => [
                    'topic'      => $validated['topic'] ?? null,
                    'sort_order' => ((int) $panel->speakers()->max('panel_session_speakers.sort_order')) + 1,
                ],
            ]);

            return back()->with('status', $speaker->name.' added and on the bill for '.$panel->tagline.'.');
        }

        return back()->with('status', 'Speaker added. Put them on a panel from their row below.');
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
            'speakers'       => PanelSpeaker::withCount('sessions')->with(['application', 'sessions'])->orderBy('name')->get(),
            // For each row's add-to-a-panel control: returning speakers are
            // the series' whole model, so putting someone from Panel 3 onto
            // the next panel must not mean hunting through the panel form.
            'upcomingPanels' => \App\Models\PanelSession::upcoming()->get(),
        ]);
    }

    /**
     * Put an existing speaker on a panel from their row on the speakers
     * screen. syncWithoutDetaching, so pressing it twice cannot double them.
     */
    public function attachSpeakerToPanel(Request $request, PanelSpeaker $speaker): RedirectResponse
    {
        $validated = $request->validate([
            'panel_session_id' => ['required', 'exists:panel_sessions,id'],
            'topic'            => ['nullable', 'string', 'max:255'],
        ]);

        $panel = \App\Models\PanelSession::findOrFail($validated['panel_session_id']);

        $panel->speakers()->syncWithoutDetaching([
            $speaker->id => [
                'topic'      => $validated['topic'] ?? null,
                'sort_order' => ((int) $panel->speakers()->max('panel_session_speakers.sort_order')) + 1,
            ],
        ]);

        return back()->with('status', $speaker->name.' added to '.$panel->tagline.'. Set their topic and running order in the panel form whenever you like.');
    }

    /**
     * Set a speaker's published photo from the speakers screen: an upload,
     * or one click to reuse the headshot from their pitch. Photos used to be
     * SSH-only (the speakers:photo command plus a hand-typed path), which in
     * practice meant initials avatars everywhere.
     */
    public function speakerPhoto(Request $request, PanelSpeaker $speaker): RedirectResponse
    {
        if ($request->boolean('from_pitch')) {
            $application = $speaker->application;

            abort_unless($application?->hasHeadshot(), 404);

            $bytes = (string) Storage::disk(\App\Models\SpeakerApplication::HEADSHOT_DISK)
                ->get($application->headshot_path);
        } else {
            $request->validate([
                'photo' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            ], [
                'photo.max'   => 'Keep photos under 8MB. They are resized on upload anyway.',
                'photo.mimes' => 'Use a JPEG, PNG or WebP.',
            ]);

            $bytes = (string) file_get_contents($request->file('photo')->getRealPath());
        }

        $published = \App\Support\SpeakerPhotos::publish($bytes, $speaker->name);

        if (! $published) {
            return back()->with('error', 'That file could not be read as a picture. Try exporting it as a JPEG and uploading again.');
        }

        \App\Support\SpeakerPhotos::delete($speaker->photo_path);
        $speaker->forceFill(['photo_path' => $published])->save();

        return back()->with('status', $speaker->name.'\'s photo is live on their panel pages.');
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

            // One agenda item per line; an optional "time |" prefix renders
            // as the schedule column on the panel page.
            'itinerary_text'     => ['nullable', 'string', 'max:2000'],
            'accessibility_note' => ['nullable', 'string', 'max:500'],

            'banner'        => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'banner_remove' => ['nullable', 'boolean'],
        ], [
            'tagline.required' => 'The tagline is the topic shown as the headline on the page.',
            'banner.max'       => 'Keep the banner under 8MB. It is resized on upload anyway.',
        ]);

        $validated['itinerary'] = collect(preg_split('/\R/', (string) ($validated['itinerary_text'] ?? '')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all() ?: null;

        unset($validated['itinerary_text']);

        if ($request->boolean('banner_remove') && $panel?->banner_path) {
            Storage::disk('panel_images')->delete($panel->banner_path);
            $validated['banner_path'] = null;
        }

        if ($request->hasFile('banner')) {
            $stored = $this->storeBanner($request->file('banner'));

            if ($stored) {
                if ($panel?->banner_path) {
                    Storage::disk('panel_images')->delete($panel->banner_path);
                }

                $validated['banner_path'] = $stored;
            }
        }

        unset($validated['banner'], $validated['banner_remove']);

        return $validated;
    }

    /**
     * Decode, shrink and write the banner as a fresh JPEG. Re-encoded, never
     * trusted, same discipline as blog pictures: whatever arrives is decoded
     * with GD and written back out, so a "picture" carrying anything else
     * does not survive, and a 6MB flyer export comes out page-sized.
     */
    private function storeBanner(\Illuminate\Http\UploadedFile $upload): ?string
    {
        $info = @getimagesize($upload->getRealPath());

        if (! $info) {
            return null;
        }

        [$width, $height, $type] = $info;

        $needed = (int) ($width * $height * 4 * 1.8) + memory_get_usage(true) + 32 * 1024 * 1024;
        if ($needed > $this->memoryLimitBytes()) {
            @ini_set('memory_limit', (string) ceil($needed / 1048576).'M');
        }

        $image = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($upload->getRealPath()),
            IMAGETYPE_PNG  => @imagecreatefrompng($upload->getRealPath()),
            IMAGETYPE_WEBP => @imagecreatefromwebp($upload->getRealPath()),
            default        => null,
        };

        if (! $image) {
            return null;
        }

        if ($width > 1600) {
            $scaled = imagescale($image, 1600);
            imagedestroy($image);
            $image = $scaled;
        }

        $name = 'banner-'.Str::lower(Str::random(10)).'.jpg';

        ob_start();
        imagejpeg($image, null, 84);
        $bytes = ob_get_clean();
        imagedestroy($image);

        Storage::disk('panel_images')->put($name, $bytes);

        return $name;
    }

    private function memoryLimitBytes(): int
    {
        $limit = ini_get('memory_limit');

        if ($limit === false || $limit === '-1') {
            return PHP_INT_MAX;
        }

        $value = (int) $limit;

        return match (strtoupper(substr($limit, -1))) {
            'G'     => $value * 1073741824,
            'M'     => $value * 1048576,
            'K'     => $value * 1024,
            default => $value,
        };
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
