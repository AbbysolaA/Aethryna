<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Who registered for which panel.
 *
 * Until now this could not be answered from the site at all: the
 * registration form wrote to an email and an EmailOctopus tag and nothing
 * else, so the only record lived outside the organisation and mixed every
 * panel together under one tag.
 */
class SessionRegistrationAdminController extends Controller
{
    public function index(Request $request): View
    {
        $panelId = $request->query('panel');

        $registrations = SessionRegistration::with('panelSession')
            ->when($panelId, fn ($q) => $q->where('panel_session_id', $panelId))
            ->when($request->boolean('speakers'), fn ($q) => $q->wantsToSpeak())
            ->latest()
            ->paginate(50)
            ->withQueryString();

        return view('admin.registrations.index', [
            'registrations'  => $registrations,
            'panels'         => PanelSession::orderByDesc('sort_order')->get(),
            'selectedPanel'  => $panelId,
            'speakersOnly'   => $request->boolean('speakers'),
            'totalCount'     => SessionRegistration::count(),
            'speakerCount'   => SessionRegistration::wantsToSpeak()->count(),

            // The number the ad campaigns are judged on: registrations, not
            // clicks. Counted within the current panel filter so "how many of
            // the Discovery Session's places did the ads fill" is one glance.
            'campaignCount'  => SessionRegistration::fromCampaign()
                ->when($panelId, fn ($q) => $q->where('panel_session_id', $panelId))
                ->count(),
        ]);
    }

    /**
     * Streamed rather than built in memory: this list only grows, and a CSV
     * download should not depend on how big it has got.
     */
    public function export(Request $request): StreamedResponse
    {
        $panelId = $request->query('panel');
        $panel   = $panelId ? PanelSession::find($panelId) : null;

        $filename = 'skillscoop-registrations-'
            . ($panel ? \Illuminate\Support\Str::slug($panel->tagline) : 'all')
            . '.csv';

        $query = SessionRegistration::with('panelSession')
            ->when($panelId, fn ($q) => $q->where('panel_session_id', $panelId))
            ->when($request->boolean('speakers'), fn ($q) => $q->wantsToSpeak())
            ->oldest();

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');

            // The utm columns are raw values rather than the readable label so
            // a spreadsheet can pivot on source and campaign directly.
            fputcsv($out, [
                'Registered at', 'Panel', 'Name', 'Email',
                'Joining as', 'Heard about us', 'Wants to speak', 'Speaker topic',
                'UTM source', 'UTM medium', 'UTM campaign',
            ]);

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->created_at?->format('Y-m-d H:i'),
                        $r->panelSession?->tagline ?? '—',
                        $r->name,
                        $r->email,
                        $r->interestLabel(),
                        $r->referral_source,
                        $r->wants_to_speak ? 'Yes' : 'No',
                        $r->speaker_topic,
                        $r->utm_source,
                        $r->utm_medium,
                        $r->utm_campaign,
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
