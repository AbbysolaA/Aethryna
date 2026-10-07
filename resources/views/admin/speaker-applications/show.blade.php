@extends('layouts.aethryna')

@section('title', 'Pitch: '.$application->name.' | Skills Co-op')

@section('content')

@include('admin._nav')
@include('admin._flash')
<section class="vl-engagement">
    <div class="ath-container sp-detail">

        <a href="{{ route('admin.speaker-applications.index') }}" class="vl-back sp-no-print">Back to pitches</a>

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">Speaker pitch</span>
                <h1 class="vl-engagement-title">{{ $application->name }}</h1>
                <p class="vl-side-note">
                    Submitted {{ $application->created_at->format('j F Y, g.ia') }}
                    &middot; {{ $application->statusLabel() }}
                    @if ($application->panelSpeaker)
                        &middot; On the speakers list
                    @endif
                </p>
            </div>
            <div class="vl-head-actions sp-no-print">
                <a href="{{ route('admin.speaker-applications.pitch', $application) }}" class="vl-btn vl-btn-primary">Download the pitch</a>
                @if ($application->hasHeadshot())
                    <a href="{{ route('admin.speaker-applications.headshot', $application) }}" class="vl-btn vl-btn-quiet">Download headshot</a>
                @endif
                <button type="button" class="vl-btn vl-btn-quiet sp-print-btn" onclick="window.print()">Print</button>
            </div>
        </header>

        <div class="sp-detail-grid">
            <div>
                <div class="vl-panel sp-panel">
                    <h2>The talk</h2>
                    <p class="sp-talk-title">{{ $application->talk_title }}</p>
                    <p class="sp-prose">{{ $application->talk_summary }}</p>

                    <dl class="sp-facts">
                        <div>
                            <dt>Format preference</dt>
                            <dd>{{ $application->formatLabel() ?: 'No preference' }}</dd>
                        </div>
                        <div>
                            <dt>Topic areas</dt>
                            <dd>{{ is_array($application->topic_areas) && $application->topic_areas ? implode(', ', $application->topic_areas) : 'Not stated' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="vl-panel sp-panel">
                    <h2>About {{ str($application->name)->before(' ') }}</h2>
                    <p class="sp-prose">{{ $application->bio }}</p>

                    @if ($application->prior_speaking)
                        <h3>Prior speaking</h3>
                        <p class="sp-prose">{{ $application->prior_speaking }}</p>
                    @endif

                    @if ($application->video_url)
                        <p><strong>Video:</strong> <a href="{{ $application->video_url }}" target="_blank" rel="noopener">{{ $application->video_url }}</a></p>
                    @endif
                </div>

                <div class="vl-panel sp-panel sp-no-print">
                    <h2>Write to {{ str($application->name)->before(' ') }}</h2>
                    <p class="vl-side-note" style="margin-top:-4px;">
                        Sends straight from this page, no copying addresses. It goes out from
                        our no reply address, their replies land at {{ config('organisation.email') }},
                        and a copy of what you send goes there too.
                    </p>

                    <form method="POST" action="{{ route('admin.speaker-applications.reply', $application) }}" class="sp-reply-form">
                        @csrf
                        <div class="vl-field">
                            <label for="reply_subject">Subject</label>
                            <input id="reply_subject" name="subject" required maxlength="150"
                                   value="{{ old('subject', str('Your pitch: '.$application->talk_title)->limit(140, '')->toString()) }}">
                            @error('subject')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="vl-field">
                            <label for="reply_message">Message</label>
                            <textarea id="reply_message" name="message" rows="9" required maxlength="5000">{{ old('message', 'Hi '.str($application->name)->before(' ').",\n\n\n\nWarm regards,\n".str(auth()->user()->name)->before(' ')."\nSkills Co-op") }}</textarea>
                            @error('message')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="vl-btn vl-btn-primary">Send email</button>
                    </form>

                    @if ($application->replies->isNotEmpty())
                        <h3>Sent so far</h3>
                        @foreach ($application->replies as $reply)
                            <div class="sp-reply">
                                <p class="sp-reply-meta">
                                    {{ $reply->created_at->format('j F Y, g.ia') }}
                                    &middot; {{ $reply->sender?->name ?? 'A former staff member' }}
                                    &middot; {{ $reply->subject }}
                                </p>
                                <p class="sp-reply-body">{{ $reply->body }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <aside>
                @if ($application->hasHeadshot())
                    <div class="vl-panel sp-panel sp-headshot">
                        <img src="{{ route('admin.speaker-applications.headshot-preview', $application) }}"
                             alt="Headshot of {{ $application->name }}">
                        <p class="vl-side-note">{{ $application->headshot_original_name }} &middot; {{ $application->headshotSizeForHumans() ?? '' }}</p>
                    </div>
                @endif

                <div class="vl-panel sp-panel">
                    <h2>Contact</h2>
                    <dl class="sp-facts">
                        <div><dt>Email</dt><dd><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></dd></div>
                        @if ($application->phone)<div><dt>Phone</dt><dd>{{ $application->phone }}</dd></div>@endif
                        @if ($application->organisation)<div><dt>Organisation</dt><dd>{{ $application->organisation }}</dd></div>@endif
                        @if ($application->job_title)<div><dt>Role</dt><dd>{{ $application->job_title }}</dd></div>@endif
                        @if ($application->location)<div><dt>Based in</dt><dd>{{ $application->location }}</dd></div>@endif
                        @if ($application->linkedin_url)<div><dt>LinkedIn</dt><dd><a href="{{ $application->linkedin_url }}" target="_blank" rel="noopener">Profile</a></dd></div>@endif
                        @if ($application->website_url)<div><dt>Website</dt><dd><a href="{{ $application->website_url }}" target="_blank" rel="noopener">{{ str($application->website_url)->after('//')->before('/') }}</a></dd></div>@endif
                        <div><dt>Recording consent</dt><dd>{{ $application->recording_consented_at ? 'Given '.$application->recording_consented_at->format('j M Y') : 'Not recorded' }}</dd></div>
                    </dl>
                </div>

                <div class="vl-panel sp-panel sp-no-print">
                    <h2>Decision</h2>
                    @if ($application->status === 'accepted')
                        @php
                            $assignedSessions = $application->panelSpeaker?->sessions ?? collect();
                            $assignablePanels = $upcomingPanels->whereNotIn('id', $assignedSessions->pluck('id'));
                        @endphp
                        <p class="vl-side-note">
                            Accepted and on the speakers list.
                            @if ($assignedSessions->isNotEmpty())
                                Speaking at {{ $assignedSessions->pluck('tagline')->join(' and ') }}.
                            @else
                                Not on a panel yet; they were told the session details follow.
                            @endif
                        </p>
                        @if ($application->panelSpeaker && $assignablePanels->isNotEmpty())
                            <form method="POST" action="{{ route('admin.speaker-applications.update', $application) }}" class="sp-decision" style="margin-top:12px;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <label for="panel_session_id" class="sp-panel-label">Add to a panel</label>
                                <select id="panel_session_id" name="panel_session_id" required>
                                    @foreach ($assignablePanels as $panel)
                                        <option value="{{ $panel->id }}">{{ $panel->tagline }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="sp-decision-btn sp-decision-accept">
                                    Add to this panel and email them the details
                                </button>
                            </form>
                        @endif
                    @else
                        <form method="POST" action="{{ route('admin.speaker-applications.update', $application) }}" class="sp-decision">
                            @csrf
                            @method('PATCH')

                            @if ($upcomingPanels->isNotEmpty())
                                <label for="panel_session_id" class="sp-panel-label">Accept onto a panel</label>
                                <select id="panel_session_id" name="panel_session_id">
                                    <option value="">Accept without assigning yet</option>
                                    @foreach ($upcomingPanels as $panel)
                                        <option value="{{ $panel->id }}">{{ $panel->tagline }}</option>
                                    @endforeach
                                </select>
                            @endif

                            <button type="submit" name="status" value="accepted" class="sp-decision-btn sp-decision-accept">
                                Accept
                            </button>
                            @foreach (['future', 'declined'] as $status)
                                <button type="submit" name="status" value="{{ $status }}"
                                        @class(['sp-decision-btn', 'is-current' => $application->status === $status])>
                                    {{ \App\Models\SpeakerApplication::STATUS_LABELS[$status] }}
                                </button>
                            @endforeach
                        </form>
                        <p class="vl-side-note" style="margin-top:12px;">
                            Every decision emails the speaker, once, so nobody waits in
                            silence. Accepting copies everything here onto the speakers list,
                            nothing to retype, and sends the session details if you chose a
                            panel above, or a note that the details follow if not. Keeping a
                            pitch for a future session says exactly that. Declining says not
                            this time, kindly, and invites another pitch.
                        </p>
                    @endif
                </div>
            </aside>
        </div>

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .sp-detail-grid { display: grid; grid-template-columns: 1.7fr 1fr; gap: 22px; align-items: start; }
        @media (max-width: 860px) { .sp-detail-grid { grid-template-columns: 1fr; } }
        .sp-panel { margin-bottom: 22px; }
        .sp-panel h2 { font-size: 1.05rem; color: var(--ath-deep, #055860); margin: 0 0 12px; }
        .sp-panel h3 { font-size: 0.95rem; color: var(--ath-deep, #055860); margin: 18px 0 8px; }
        .sp-talk-title { font-size: 1.3rem; font-weight: 700; color: var(--ath-deep, #055860); margin: 0 0 12px; }
        .sp-prose { line-height: 1.75; color: #2b333a; white-space: pre-wrap; margin: 0 0 8px; }
        .sp-facts { margin: 16px 0 0; display: grid; gap: 10px; }
        .sp-facts dt {
            font-family: var(--font-mono, ui-monospace, monospace);
            font-size: 0.62rem; letter-spacing: 1.3px; text-transform: uppercase;
            color: #8a939c; margin-bottom: 2px;
        }
        .sp-facts dd { margin: 0; color: #2b333a; overflow-wrap: anywhere; }
        .sp-headshot img { width: 100%; border-radius: 10px; display: block; }
        .sp-headshot .vl-side-note { margin-top: 8px; }
        .sp-decision { display: grid; gap: 8px; }
        .sp-panel-label {
            font-family: var(--font-mono, ui-monospace, monospace);
            font-size: 0.62rem; letter-spacing: 1.3px; text-transform: uppercase;
            color: #8a939c; margin-bottom: -2px;
        }
        .sp-decision select {
            padding: 10px 12px; border-radius: 9px; font: inherit;
            border: 1px solid rgba(3, 139, 137, 0.25); background: #fff; color: #2b333a;
        }
        .sp-decision-btn.sp-decision-accept { background: var(--ath-teal, #038b89); border-color: var(--ath-teal, #038b89); color: #fff; }
        .sp-decision-btn.sp-decision-accept:hover { background: var(--ath-deep, #055860); }
        .sp-decision-btn {
            padding: 10px 14px; border-radius: 9px; font: inherit; font-weight: 600;
            border: 1px solid rgba(3, 139, 137, 0.25); background: #fff;
            color: var(--ath-deep, #055860); cursor: pointer; text-align: left;
        }
        .sp-decision-btn:hover { background: rgba(3, 139, 137, 0.07); }
        .sp-decision-btn.is-current { background: var(--ath-teal, #038b89); border-color: var(--ath-teal, #038b89); color: #fff; }
        .sp-print-btn { background: none; border: none; font: inherit; cursor: pointer; }
        .sp-reply-form { display: grid; gap: 4px; margin-top: 14px; }
        .sp-reply-form textarea { resize: vertical; }
        .sp-reply-form .vl-btn { justify-self: start; }
        .sp-reply { border-top: 1px solid rgba(3, 139, 137, 0.15); padding: 14px 0 4px; }
        .sp-reply-meta {
            font-family: var(--font-mono, ui-monospace, monospace);
            font-size: 0.68rem; letter-spacing: 0.6px; color: #8a939c; margin: 0 0 6px;
        }
        .sp-reply-body { line-height: 1.7; color: #2b333a; white-space: pre-wrap; margin: 0; }
        @media print {
            .sp-no-print, .ad-nav, nav, footer, #navbar { display: none !important; }
            .sp-detail-grid { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@endsection
