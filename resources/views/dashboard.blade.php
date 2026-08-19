@extends('layouts.aethryna')

@section('title', 'Dashboard | Skills Co-op')

@section('content')
    @php
        use App\Models\Assessment;
        use App\Models\Pathway;

        $userAssessment = Assessment::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->with('results.pathway')
            ->latest()
            ->first();

        $totalAssessments = Assessment::where('user_id', Auth::id())->count();
        $completedAssessments = Assessment::where('user_id', Auth::id())->where('status', 'completed')->count();

        $popularPathways = Pathway::active()->take(6)->get();

        // Real programme data. The previous fabricated tiles (1,247 active
        // learners, 89% job placement) have been removed.
        $nextPanel = \App\Models\PanelSession::upcoming()->first();

        // Cluster scores are accumulated one point per question, so the
        // denominator is the number of questions answered, never a fixed 4.
        $totalQuestions = \App\Models\Question::active()->count() ?: 15;

        $clusterNames = [
            'T' => 'Technical',
            'C' => 'Creative',
            'B' => 'Business',
            'S' => 'Security',
            'F' => 'Foundation',
        ];
    @endphp

    <!-- Dashboard Header -->
    <section class="db-hero">
        <div class="db-container">
            <div class="db-hero-grid">
                <div>
                    <span class="db-eyebrow">Your dashboard</span>
                    <h1 class="db-hero-title">Welcome back, {{ Auth::user()->name }}.</h1>
                    <p class="db-hero-lede">Your pathway into digital work starts here.</p>
                </div>
                <div class="db-profile">
                    <div class="db-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div class="db-profile-body">
                        <h2 class="db-profile-name">{{ Auth::user()->name }}</h2>
                        <p class="db-profile-email">{{ Auth::user()->email }}</p>
                        <span class="db-role-pill">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Learner' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Assessment status -->
    <section class="db-section db-section-white">
        <div class="db-container">
            @if ($userAssessment)
                <div class="db-status db-status-done">
                    <div class="db-status-head">
                        <div class="db-status-icon db-icon-teal"><i class="fas fa-check"></i></div>
                        <div>
                            <h3>Assessment complete</h3>
                            <p>Your pathway recommendations are ready.</p>
                        </div>
                    </div>
                    <div class="db-result-grid">
                        @foreach ($userAssessment->results as $result)
                            <div class="db-result-card {{ $result->result_type === 'primary' ? 'is-primary' : '' }}">
                                <div class="db-result-tags">
                                    <span class="db-tag">{{ $result->result_type === 'primary' ? 'Primary' : 'Secondary' }}</span>
                                    <span class="db-tag-muted">{{ $clusterNames[$result->cluster] ?? $result->cluster }} profile</span>
                                </div>
                                <h4>{{ $result->pathway->name }}</h4>
                                <p>{{ ucfirst($result->pathway->category) }} pathway</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="db-status-actions">
                        <a href="{{ route('assessment.results') }}" class="db-btn db-btn-primary">View full results</a>
                        <form action="{{ route('assessment.reset') }}" method="POST">
                            @csrf
                            <button type="submit" class="db-btn db-btn-ghost">Retake assessment</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="db-status db-status-todo">
                    <div class="db-status-head">
                        <div class="db-status-icon db-icon-gold"><i class="fas fa-compass"></i></div>
                        <div>
                            <h3>Find your pathway</h3>
                            <p>A short assessment matches you to one of our four pilot tracks.</p>
                        </div>
                    </div>
                    <div class="db-mini-grid">
                        <div class="db-mini">
                            <strong>{{ $totalQuestions }} questions</strong>
                            <span>Based on your strengths and interests</span>
                        </div>
                        <div class="db-mini">
                            <strong>Personalised result</strong>
                            <span>One or two track recommendations</span>
                        </div>
                        <div class="db-mini">
                            <strong>Clear next steps</strong>
                            <span>Where to go once you have your match</span>
                        </div>
                    </div>
                    <form action="{{ route('assessment.start') }}" method="POST">
                        @csrf
                        <button type="submit" class="db-btn db-btn-primary">Start the assessment</button>
                    </form>
                </div>
            @endif

            <!-- Quick stats -->
            <div class="db-stat-grid">
                <div class="db-stat">
                    <div class="db-stat-icon db-icon-teal"><i class="fas fa-clipboard-check"></i></div>
                    <h3>Assessment</h3>
                    <p class="db-stat-value">{{ $completedAssessments > 0 ? 'Complete' : 'Not started' }}</p>
                    <p class="db-stat-note">{{ $totalAssessments }} {{ Str::plural('attempt', $totalAssessments) }} so far</p>
                </div>

                <div class="db-stat">
                    <div class="db-stat-icon db-icon-teal"><i class="fas fa-route"></i></div>
                    <h3>Pilot tracks</h3>
                    <p class="db-stat-value">4</p>
                    <p class="db-stat-note">Launching with Cohort 1</p>
                </div>

                <div class="db-stat">
                    <div class="db-stat-icon db-icon-teal"><i class="fas fa-microphone-alt"></i></div>
                    <h3>Next panel</h3>
                    @if ($nextPanel)
                        <p class="db-stat-value">{{ $nextPanel->event_date?->format('j M') ?? 'TBC' }}</p>
                        <p class="db-stat-note">{{ Str::limit($nextPanel->tagline, 38) }}</p>
                    @else
                        <p class="db-stat-value">TBC</p>
                        <p class="db-stat-note">Details announced soon</p>
                    @endif
                </div>

                <div class="db-stat">
                    <div class="db-stat-icon db-icon-gold"><i class="fas fa-flag-checkered"></i></div>
                    <h3>{{ config('organisation.cohort.name') }}</h3>
                    <p class="db-stat-value">{{ config('organisation.cohort.starts') }}</p>
                    <p class="db-stat-note">{{ config('organisation.cohort.places') }} founding places</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Analytics and next steps -->
    <section class="db-section db-section-light">
        <div class="db-container">
            <div class="db-two-col">
                <!-- Strength analysis -->
                <div class="db-panel">
                    <div class="db-panel-head">
                        <div class="db-status-icon db-icon-teal"><i class="fas fa-chart-simple"></i></div>
                        <div>
                            <h3>Strength profile</h3>
                            <p>How your answers mapped across the five clusters</p>
                        </div>
                    </div>

                    @if ($userAssessment && $userAssessment->scores)
                        <div class="db-score-list">
                            @foreach (collect($userAssessment->scores)->sortDesc() as $cluster => $score)
                                @php $pct = $totalQuestions > 0 ? min(100, round(($score / $totalQuestions) * 100)) : 0; @endphp
                                <div class="db-score">
                                    <div class="db-score-head">
                                        <span class="db-score-label">{{ $clusterNames[$cluster] ?? $cluster }}</span>
                                        <span class="db-score-value">{{ $score }} of {{ $totalQuestions }}</span>
                                    </div>
                                    <div class="db-bar"><div class="db-bar-fill" style="width: {{ $pct }}%"></div></div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="db-empty">
                            <div class="db-empty-icon"><i class="fas fa-chart-simple"></i></div>
                            <p>Complete the assessment to see your strength profile.</p>
                        </div>
                    @endif
                </div>

                <!-- Next steps -->
                <div class="db-panel">
                    <div class="db-panel-head">
                        <div class="db-status-icon db-icon-gold"><i class="fas fa-list-check"></i></div>
                        <div>
                            <h3>Next steps</h3>
                            <p>What to do from here</p>
                        </div>
                    </div>

                    <div class="db-steps">
                        @if (!$userAssessment)
                            <div class="db-step">
                                <span class="db-step-num">1</span>
                                <div>
                                    <h4>Take the pathway assessment</h4>
                                    <p>{{ $totalQuestions }} questions, about two minutes.</p>
                                    <form action="{{ route('assessment.start') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="db-link-btn">Start now &rarr;</button>
                                    </form>
                                </div>
                            </div>
                            <div class="db-step db-step-muted">
                                <span class="db-step-num">2</span>
                                <div>
                                    <h4>Explore your recommended track</h4>
                                    <p>Unlocks once you have your result.</p>
                                </div>
                            </div>
                            <div class="db-step db-step-muted">
                                <span class="db-step-num">3</span>
                                <div>
                                    <h4>Join a panel session</h4>
                                    <p>Meet the community before Cohort 1 begins.</p>
                                </div>
                            </div>
                        @else
                            <div class="db-step db-step-done">
                                <span class="db-step-num"><i class="fas fa-check"></i></span>
                                <div>
                                    <h4>Assessment complete</h4>
                                    <p>Your recommendations are ready to read.</p>
                                    <a href="{{ route('assessment.results') }}" class="db-link">View results &rarr;</a>
                                </div>
                            </div>
                            <div class="db-step">
                                <span class="db-step-num">2</span>
                                <div>
                                    <h4>Explore your track</h4>
                                    <p>See what the 25-week journey covers.</p>
                                    <a href="{{ route('pathway') }}" class="db-link">See the pathway &rarr;</a>
                                </div>
                            </div>
                            <div class="db-step">
                                <span class="db-step-num">3</span>
                                <div>
                                    <h4>Join the next panel</h4>
                                    <p>Free, online, open to everyone.</p>
                                    <a href="{{ route('sessions') }}" class="db-link">Reserve a spot &rarr;</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{--
                Where the application stands.

                The register page tells people that creating an account starts
                their application and that we will guide them through the
                assessment and next steps. Then the trail stopped: a learner
                could register, sit the assessment, read their result and still
                have no idea whether they had applied, whether anyone had seen
                it, or when they might hear. For an audience whose usual
                experience of institutions is being ignored, that silence is the
                worst possible note to end on.

                Says only what is true. The dates come from config, and where
                nothing is set the copy stays honest rather than inventing a
                timetable — a missed promise here costs more than a vague one.
            --}}
            @php
                $cohort   = config('organisation.cohort');
                $applied  = (bool) $userAssessment;
            @endphp
            <div class="db-panel db-panel-wide db-application">
                <div class="db-panel-head">
                    <h3>Your application</h3>
                    <p>{{ $cohort['name'] }} &middot; starts {{ $cohort['starts'] }}</p>
                </div>

                <div class="db-app-state {{ $applied ? 'is-complete' : 'is-progress' }}">
                    <div class="db-app-icon">
                        <i class="fas {{ $applied ? 'fa-circle-check' : 'fa-hourglass-half' }}"></i>
                    </div>
                    <div>
                        <h4>{{ $applied ? 'Everything we need, for now' : 'Started, not finished' }}</h4>
                        @if ($applied)
                            <p>
                                You have an account and a completed assessment, which is everything the
                                application asks for at this stage. Nothing else is needed from you today.
                            </p>
                        @else
                            <p>
                                Your account is created, so your application is open. The pathway assessment
                                is the one part still outstanding &mdash; it is how we know which track to
                                consider you for.
                            </p>
                        @endif
                    </div>
                </div>

                <ul class="db-app-facts">
                    <li>
                        <span>Places in {{ $cohort['name'] }}</span>
                        <strong>{{ $cohort['places'] }}</strong>
                    </li>
                    <li>
                        <span>Programme starts</span>
                        <strong>{{ $cohort['starts'] }}</strong>
                    </li>
                    <li>
                        <span>Applications close</span>
                        <strong>{{ $cohort['closes'] ?? 'Not yet announced' }}</strong>
                    </li>
                </ul>

                <p class="db-app-note">
                    @if ($cohort['decision_note'])
                        {{ $cohort['decision_note'] }}
                    @else
                        We are still setting the review timetable for {{ $cohort['name'] }}. When it is
                        fixed we will email you at <strong>{{ auth()->user()->email }}</strong> &mdash; you
                        do not need to check back or chase us.
                    @endif
                    Anything you want to ask in the meantime, write to
                    <a href="mailto:hello@skillscoop.org">hello@skillscoop.org</a> and a person will reply.
                </p>
            </div>

            <!-- Pathways -->
            <div class="db-panel db-panel-wide">
                <div class="db-panel-head db-panel-head-row">
                    <div>
                        <h3>Explore learning pathways</h3>
                        <p>The tracks available across the programme</p>
                    </div>
                    <a href="{{ route('programs') }}" class="db-link">View all &rarr;</a>
                </div>

                <div class="db-pathway-grid">
                    @foreach ($popularPathways as $pathway)
                        <a href="{{ route('programs.show', $pathway) }}" class="db-pathway">
                            <div class="db-pathway-head">
                                <div class="db-pathway-icon">
                                    @if ($pathway->category === 'technical')
                                        <i class="fas fa-code"></i>
                                    @elseif($pathway->category === 'creative')
                                        <i class="fas fa-palette"></i>
                                    @elseif($pathway->category === 'business')
                                        <i class="fas fa-chart-line"></i>
                                    @elseif($pathway->category === 'security')
                                        <i class="fas fa-shield-halved"></i>
                                    @else
                                        <i class="fas fa-seedling"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4>{{ $pathway->name }}</h4>
                                    <span class="db-pathway-cat">{{ ucfirst($pathway->category) }}</span>
                                </div>
                            </div>
                            <p class="db-pathway-desc">{{ Str::limit($pathway->description, 90) }}</p>
                            <div class="db-pathway-meta">
                                <span>{{ ucfirst($pathway->difficulty_level ?? 'Beginner') }}</span>
                                @if ($pathway->duration_months)
                                    <span>{{ $pathway->duration_months }} months</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            :root {
                --ath-teal: #038b89;
                --ath-gold: #ee9d1d;
                --ath-deep: #055860;
                --ath-navy: #0a2530;
                --ath-light: #F8FBFB;
                --ath-text: #404952;
                --ath-muted: #57616a;
                --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
            }

            .db-container { max-width: 1200px; margin: 0 auto; padding: 0 5%; }

            /* Hero */
            .db-hero {
                padding: 150px 0 70px;
                background: linear-gradient(180deg, var(--ath-deep) 0%, var(--ath-navy) 100%);
                color: #fff;
                position: relative;
                overflow: hidden;
            }
            .db-hero::after {
                content: '';
                position: absolute;
                top: -30%; right: -10%;
                width: 55%; height: 140%;
                background: radial-gradient(closest-side, rgba(238,157,29,0.14), transparent 70%);
                pointer-events: none;
            }
            .db-hero-grid {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 40px;
                flex-wrap: wrap;
                position: relative;
                z-index: 1;
            }
            .db-eyebrow {
                display: inline-block;
                font-family: var(--font-mono);
                font-size: 0.78rem;
                font-weight: 600;
                letter-spacing: 3px;
                text-transform: uppercase;
                color: var(--ath-gold);
                margin-bottom: 14px;
                padding-left: 12px;
                border-left: 3px solid var(--ath-gold);
            }
            .db-hero-title {
                font-family: 'Outfit', sans-serif;
                font-size: clamp(1.9rem, 4vw, 2.8rem);
                font-weight: 800;
                line-height: 1.1;
                margin: 0 0 10px;
            }
            .db-hero-lede { font-size: 1.05rem; opacity: 0.85; margin: 0; }

            /* Profile card */
            .db-profile {
                display: flex;
                align-items: center;
                gap: 16px;
                background: rgba(255,255,255,0.07);
                border: 1px solid rgba(255,255,255,0.14);
                border-radius: 18px;
                padding: 18px 24px 18px 18px;
                backdrop-filter: blur(6px);
            }
            .db-avatar {
                width: 58px; height: 58px;
                flex-shrink: 0;
                border-radius: 50%;
                background: var(--ath-gold);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Outfit', sans-serif;
                font-size: 1.5rem;
                font-weight: 800;
            }
            .db-profile-name {
                font-family: 'Outfit', sans-serif;
                font-size: 1.15rem;
                font-weight: 700;
                color: #fff;
                margin: 0 0 2px;
            }
            .db-profile-email { font-size: 0.87rem; color: rgba(255,255,255,0.7); margin: 0 0 8px; }
            .db-role-pill {
                display: inline-block;
                font-family: var(--font-mono);
                font-size: 0.68rem;
                font-weight: 600;
                letter-spacing: 1.4px;
                text-transform: uppercase;
                background: rgba(238,157,29,0.2);
                color: var(--ath-gold);
                border: 1px solid rgba(238,157,29,0.35);
                padding: 4px 12px;
                border-radius: 100px;
            }

            /* Sections */
            .db-section { padding: 60px 0; }
            .db-section-white { background: #fff; }
            .db-section-light { background: var(--ath-light); }

            /* Status block */
            .db-status {
                border-radius: 22px;
                padding: 32px;
                margin-bottom: 40px;
                border: 1px solid rgba(3,139,137,0.14);
            }
            .db-status-done { background: rgba(3,139,137,0.05); border-left: 5px solid var(--ath-teal); }
            .db-status-todo { background: rgba(238,157,29,0.06); border-left: 5px solid var(--ath-gold); }

            /* Application status. Same visual language as the assessment
               status block above it, so the two read as one story rather than
               two unrelated cards. */
            .db-application { border-top: 3px solid var(--ath-teal); }
            .db-app-state {
                display: flex;
                gap: 16px;
                align-items: flex-start;
                padding: 20px;
                border-radius: 16px;
                margin: 4px 0 22px;
            }
            .db-app-state.is-complete { background: rgba(3,139,137,0.06); }
            .db-app-state.is-progress { background: rgba(238,157,29,0.07); }
            .db-app-icon {
                flex: 0 0 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 1rem;
            }
            .db-app-state.is-complete .db-app-icon { background: var(--ath-teal); }
            .db-app-state.is-progress .db-app-icon { background: var(--ath-gold); }
            .db-app-state h4 { margin: 0 0 6px; color: var(--ath-deep); font-size: 1.05rem; }
            .db-app-state p { margin: 0; line-height: 1.6; }

            .db-app-facts {
                list-style: none;
                margin: 0 0 20px;
                padding: 0;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 14px;
            }
            .db-app-facts li {
                padding: 14px 16px;
                border: 1px solid rgba(3,139,137,0.14);
                border-radius: 14px;
            }
            .db-app-facts span {
                display: block;
                font-size: 0.72rem;
                letter-spacing: 1.2px;
                text-transform: uppercase;
                color: var(--ath-muted, #6b7480);
                margin-bottom: 4px;
            }
            .db-app-facts strong { color: var(--ath-deep); font-size: 1.05rem; }

            .db-app-note { margin: 0; line-height: 1.7; font-size: 0.95rem; }
            .db-app-note a { color: var(--ath-teal); }
            .db-status-head { display: flex; align-items: center; gap: 16px; margin-bottom: 22px; }
            .db-status-head h3 {
                font-family: 'Outfit', sans-serif;
                font-size: 1.3rem;
                font-weight: 800;
                color: var(--ath-deep);
                margin: 0 0 4px;
            }
            .db-status-head p { color: var(--ath-muted); margin: 0; font-size: 0.97rem; }
            .db-status-icon {
                width: 46px; height: 46px;
                flex-shrink: 0;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                color: #fff;
            }
            .db-icon-teal { background: var(--ath-teal); }
            .db-icon-gold { background: var(--ath-gold); }

            .db-result-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 22px; }
            .db-result-card {
                background: #fff;
                border: 1px solid rgba(0,0,0,0.06);
                border-radius: 14px;
                padding: 18px 20px;
            }
            .db-result-card.is-primary { border-color: var(--ath-teal); box-shadow: 0 0 0 1px var(--ath-teal); }
            .db-result-tags { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap; }
            .db-tag {
                font-family: var(--font-mono);
                font-size: 0.65rem;
                font-weight: 600;
                letter-spacing: 1.2px;
                text-transform: uppercase;
                background: var(--ath-teal);
                color: #fff;
                padding: 3px 9px;
                border-radius: 100px;
            }
            .db-tag-muted { font-size: 0.82rem; color: var(--ath-muted); }
            .db-result-card h4 {
                font-family: 'Outfit', sans-serif;
                font-size: 1.05rem;
                font-weight: 800;
                color: var(--ath-deep);
                margin: 0 0 3px;
            }
            .db-result-card p { font-size: 0.87rem; color: var(--ath-muted); margin: 0; }

            .db-status-actions { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
            .db-mini-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 22px; }
            .db-mini { background: #fff; border-radius: 12px; padding: 16px 18px; border: 1px solid rgba(0,0,0,0.05); }
            .db-mini strong { display: block; color: var(--ath-deep); font-size: 0.95rem; margin-bottom: 3px; }
            .db-mini span { font-size: 0.85rem; color: var(--ath-muted); line-height: 1.5; }

            /* Buttons */
            .db-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 26px;
                border-radius: 100px;
                font-family: 'Outfit', sans-serif;
                font-weight: 700;
                font-size: 0.96rem;
                text-decoration: none;
                cursor: pointer;
                border: 2px solid transparent;
                transition: all 0.2s;
            }
            .db-btn-primary { background: var(--ath-gold); color: #fff; }
            .db-btn-primary:hover { background: var(--ath-teal); color: #fff; }
            .db-btn-ghost { background: transparent; color: var(--ath-deep); border-color: var(--ath-deep); }
            .db-btn-ghost:hover { background: var(--ath-deep); color: #fff; }
            .db-link {
                color: var(--ath-teal);
                font-weight: 700;
                font-size: 0.92rem;
                text-decoration: none;
            }
            .db-link:hover { color: var(--ath-gold); }
            .db-link-btn {
                background: none;
                border: none;
                padding: 0;
                color: var(--ath-teal);
                font-family: inherit;
                font-weight: 700;
                font-size: 0.92rem;
                cursor: pointer;
            }
            .db-link-btn:hover { color: var(--ath-gold); }

            /* Stat tiles */
            .db-stat-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 20px; }
            .db-stat {
                background: var(--ath-light);
                border: 1px solid rgba(3,139,137,0.1);
                border-radius: 18px;
                padding: 24px;
                transition: transform 0.25s, border-color 0.25s;
            }
            .db-stat:hover { transform: translateY(-3px); border-color: var(--ath-teal); }
            .db-stat-icon {
                width: 42px; height: 42px;
                border-radius: 11px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                color: #fff;
                margin-bottom: 14px;
            }
            .db-stat h3 {
                font-family: 'Outfit', sans-serif;
                font-size: 0.95rem;
                font-weight: 700;
                color: var(--ath-muted);
                margin: 0 0 6px;
            }
            .db-stat-value {
                font-family: 'Outfit', sans-serif;
                font-size: 1.6rem;
                font-weight: 800;
                color: var(--ath-deep);
                margin: 0 0 4px;
                line-height: 1.15;
            }
            .db-stat-note { font-size: 0.87rem; color: var(--ath-muted); margin: 0; line-height: 1.5; }

            /* Panels */
            .db-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
            .db-panel {
                background: #fff;
                border: 1px solid rgba(3,139,137,0.1);
                border-radius: 22px;
                padding: 28px;
            }
            .db-panel-head { display: flex; align-items: center; gap: 14px; margin-bottom: 22px; }
            .db-panel-head-row { justify-content: space-between; }
            .db-panel-head h3 {
                font-family: 'Outfit', sans-serif;
                font-size: 1.15rem;
                font-weight: 800;
                color: var(--ath-deep);
                margin: 0 0 3px;
            }
            .db-panel-head p { font-size: 0.88rem; color: var(--ath-muted); margin: 0; }

            /* Score bars */
            .db-score-list { display: grid; gap: 16px; }
            .db-score-head { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 7px; }
            .db-score-label { font-weight: 700; color: var(--ath-deep); font-size: 0.95rem; }
            .db-score-value { font-family: var(--font-mono); font-size: 0.82rem; color: var(--ath-muted); }
            .db-bar { height: 9px; background: rgba(3,139,137,0.1); border-radius: 100px; overflow: hidden; }
            .db-bar-fill { height: 100%; background: linear-gradient(90deg, var(--ath-teal), var(--ath-gold)); border-radius: 100px; }

            .db-empty { text-align: center; padding: 34px 0; }
            .db-empty-icon {
                width: 56px; height: 56px;
                margin: 0 auto 14px;
                border-radius: 50%;
                background: rgba(3,139,137,0.08);
                color: var(--ath-teal);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.3rem;
            }
            .db-empty p { color: var(--ath-muted); margin: 0; }

            /* Steps */
            .db-steps { display: grid; gap: 14px; }
            .db-step {
                display: flex;
                gap: 14px;
                padding: 16px 18px;
                background: var(--ath-light);
                border-radius: 14px;
                border-left: 3px solid var(--ath-teal);
            }
            .db-step-done { border-left-color: var(--ath-gold); }
            .db-step-muted { opacity: 0.55; border-left-color: rgba(3,139,137,0.3); }
            .db-step-num {
                width: 28px; height: 28px;
                flex-shrink: 0;
                border-radius: 50%;
                background: var(--ath-teal);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Outfit', sans-serif;
                font-size: 0.85rem;
                font-weight: 800;
            }
            .db-step-done .db-step-num { background: var(--ath-gold); }
            .db-step h4 {
                font-family: 'Outfit', sans-serif;
                font-size: 0.99rem;
                font-weight: 800;
                color: var(--ath-deep);
                margin: 0 0 3px;
            }
            .db-step p { font-size: 0.87rem; color: var(--ath-muted); margin: 0 0 6px; line-height: 1.5; }

            /* Pathways */
            .db-panel-wide { padding: 28px; }
            .db-pathway-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 18px; }
            .db-pathway {
                display: block;
                border: 1px solid rgba(0,0,0,0.07);
                border-radius: 16px;
                padding: 20px;
                text-decoration: none;
                transition: border-color 0.25s, transform 0.25s, box-shadow 0.25s;
            }
            .db-pathway:hover {
                border-color: var(--ath-teal);
                transform: translateY(-3px);
                box-shadow: 0 14px 40px rgba(3,139,137,0.09);
            }
            .db-pathway-head { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
            .db-pathway-icon {
                width: 38px; height: 38px;
                flex-shrink: 0;
                border-radius: 10px;
                background: rgba(3,139,137,0.1);
                color: var(--ath-teal);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.95rem;
            }
            .db-pathway h4 {
                font-family: 'Outfit', sans-serif;
                font-size: 0.99rem;
                font-weight: 800;
                color: var(--ath-deep);
                margin: 0 0 2px;
            }
            .db-pathway-cat {
                font-family: var(--font-mono);
                font-size: 0.66rem;
                font-weight: 600;
                letter-spacing: 1.1px;
                text-transform: uppercase;
                color: var(--ath-muted);
            }
            .db-pathway-desc { font-size: 0.87rem; color: var(--ath-muted); line-height: 1.6; margin: 0 0 12px; }
            .db-pathway-meta {
                display: flex;
                justify-content: space-between;
                font-family: var(--font-mono);
                font-size: 0.72rem;
                color: var(--ath-muted);
                padding-top: 10px;
                border-top: 1px solid rgba(0,0,0,0.05);
            }

            @media (max-width: 992px) {
                .db-two-col { grid-template-columns: 1fr; }
                .db-stat-grid { grid-template-columns: 1fr 1fr; }
                .db-pathway-grid { grid-template-columns: 1fr 1fr; }
                .db-result-grid { grid-template-columns: 1fr; }
                .db-mini-grid { grid-template-columns: 1fr; }
            }
            @media (max-width: 640px) {
                .db-hero { padding: 120px 0 50px; }
                .db-stat-grid { grid-template-columns: 1fr; }
                .db-pathway-grid { grid-template-columns: 1fr; }
                .db-profile { width: 100%; }
                .db-status { padding: 24px 20px; }
                .db-panel { padding: 22px 20px; }
            }
        </style>
    @endpush
@endsection
