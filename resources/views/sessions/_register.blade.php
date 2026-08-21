{{-- Registration block, shared by /sessions and /sessions/{slug}.
     Expects $session: the panel being registered for, or null when
     nothing is scheduled. The hidden panel_session_id is what ties a
     registration to the panel the person was actually looking at. --}}
<section id="register-section" class="ss-register">
    <div class="ath-container">
        <div class="ss-register-grid">
            <div class="ss-register-info">
                <span class="ath-sub">The Sessions</span>
                <h2>{{ $namedPanel ?? false ? 'Register for this panel' : 'Register for the next panel' }}</h2>
                <p>The Skills Co-op Sessions are free, online, and open to everyone. Register here and we will email you the details for the next panel.</p>
                <div class="ss-register-pills">
                    <span class="ss-register-pill">Free</span>
                    <span class="ss-register-pill">Online</span>
                    <span class="ss-register-pill">Open to everyone</span>
                </div>

                <div class="ss-register-cards">
                    <div class="ss-register-card">
                        <span class="ss-register-card-icon"><i class="fas fa-info-circle"></i></span>
                        <div>
                            <strong>This is the panel, not the programme</strong>
                            <p>Registering here books you a seat at the session. If you came for the training, <a href="{{ route('pathway') }}">start with the pathways</a>.</p>
                        </div>
                    </div>

                    <div class="ss-register-card ss-register-card-speak">
                        <span class="ss-register-card-icon"><i class="fas fa-microphone-alt"></i></span>
                        <div>
                            <strong>Want to speak on a future panel?</strong>
                            {{-- The full pitch page, not just the checkbox below.
                                 A checkbox captures a maybe; the page captures a
                                 talk we can actually book. --}}
                            <p><a href="{{ route('speakers.apply') }}">Pitch your talk &rarr;</a></p>
                        </div>
                    </div>
                </div>
                @if($session && $session->eventbrite_url)
                    <p class="ss-register-alt">Prefer Eventbrite? <a href="{{ $session->eventbrite_url }}" target="_blank" rel="noopener">Register there instead &rarr;</a></p>
                @endif
            </div>

            <div class="ss-register-form-wrap">
                @if(session('success'))
                    <div class="ss-success">
                        <i class="fas fa-check-circle"></i>
                        <h3>You are registered</h3>
                        <p>{{ session('success') }}</p>
                        <a href="{{ route('home') }}" class="ss-btn ss-btn-primary">Back to home</a>
                    </div>
                @else
                    <form action="{{ route('sessions.register') }}" method="POST" class="ss-form">
                        @csrf
                        @if($session)
                            <input type="hidden" name="panel_session_id" value="{{ $session->id }}">
                        @endif
                        <div class="ss-form-group">
                            <label for="name">Full name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Your full name">
                            @error('name')<span class="ss-form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="ss-form-group">
                            <label for="email">Email address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                            @error('email')<span class="ss-form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="ss-form-group">
                            <label for="interest_type">I am joining as a</label>
                            <select id="interest_type" name="interest_type" required>
                                <option value="">Select one</option>
                                <option value="learner" {{ old('interest_type') == 'learner' ? 'selected' : '' }}>A learner or career changer</option>
                                <option value="mentor" {{ old('interest_type') == 'mentor' ? 'selected' : '' }}>A mentor or industry professional</option>
                                <option value="partner" {{ old('interest_type') == 'partner' ? 'selected' : '' }}>A partner or employer</option>
                                <option value="curious" {{ old('interest_type') == 'curious' ? 'selected' : '' }}>Just curious</option>
                            </select>
                            @error('interest_type')<span class="ss-form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="ss-form-group">
                            <label for="referral_source">How did you hear about us? <span class="ss-form-opt">(optional)</span></label>
                            <select id="referral_source" name="referral_source">
                                <option value="">Select one</option>
                                <option value="social_media" {{ old('referral_source') == 'social_media' ? 'selected' : '' }}>Social media</option>
                                <option value="word_of_mouth" {{ old('referral_source') == 'word_of_mouth' ? 'selected' : '' }}>Word of mouth</option>
                                <option value="search_engine" {{ old('referral_source') == 'search_engine' ? 'selected' : '' }}>Search engine</option>
                                <option value="community_org" {{ old('referral_source') == 'community_org' ? 'selected' : '' }}>Community organisation</option>
                                <option value="event" {{ old('referral_source') == 'event' ? 'selected' : '' }}>Event or workshop</option>
                                <option value="other" {{ old('referral_source') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="ss-speaker-block">
                            <label class="ss-check" for="wants_to_speak">
                                <input type="checkbox" id="wants_to_speak" name="wants_to_speak" value="1" {{ old('wants_to_speak') ? 'checked' : '' }}>
                                <span>I would be interested in speaking on a future panel</span>
                            </label>
                            <p class="ss-register-alt" style="margin-top:6px;">
                                Have a talk in mind already?
                                <a href="{{ route('speakers.apply') }}">Pitch it properly here &rarr;</a>
                            </p>
                            <div class="ss-form-group ss-speaker-topic" id="speaker-topic-group" @unless(old('wants_to_speak')) hidden @endunless>
                                <label for="speaker_topic">What would you speak about? <span class="ss-form-opt">(optional)</span></label>
                                <textarea id="speaker_topic" name="speaker_topic" rows="3" placeholder="A sentence is plenty. What do you work on, and what would you want to say?">{{ old('speaker_topic') }}</textarea>
                                @error('speaker_topic')<span class="ss-form-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <button type="submit" class="ss-btn ss-btn-primary ss-btn-full">
                            <i class="fas fa-paper-plane"></i> Register for this panel
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>
