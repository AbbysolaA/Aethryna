{{-- The prompts behind a session's live builds. Titles are public, so
     visitors and search engines can see what is on offer; the prompts
     themselves show once someone gives a name and email. Expects $session
     and $promptsUnlocked. --}}
<section id="prompts" class="ss-prompts">
    <div class="ath-container">
        <div class="ss-prompts-header">
            <span class="ath-sub">Try it yourself</span>
            <h2>The prompts from the live builds</h2>
            <p>The exact prompts our speakers used on screen, so you can build the same thing yourself. Every build used fictional or sample data only.</p>
        </div>

        @if ($promptsUnlocked)
            @if (session('prompts_unlocked'))
                <p class="ss-prompts-thanks" role="status"><i class="fas fa-check-circle"></i> Here they are. Copy any prompt with its button.</p>
            @endif

            <div class="ss-prompt-list">
                @foreach ($session->prompts as $prompt)
                    <article class="ss-prompt">
                        <header class="ss-prompt-head">
                            <div>
                                <h3>{{ $prompt->title }}</h3>
                                @if ($prompt->speaker)
                                    <p class="ss-prompt-by">From {{ $prompt->speaker->name }}'s build</p>
                                @endif
                            </div>
                            <button type="button" class="ss-btn ss-btn-sm ss-prompt-copy" data-copy-target="prompt-{{ $prompt->id }}">
                                <i class="far fa-copy"></i> <span>Copy</span>
                            </button>
                        </header>
                        @if ($prompt->note)
                            {{-- Outside the copied block on purpose: pasted into an AI
                                 tool, guidance would become part of the instruction. --}}
                            <p class="ss-prompt-note">{{ $prompt->note }}</p>
                        @endif
                        <pre id="prompt-{{ $prompt->id }}" class="ss-prompt-body">{{ $prompt->body }}</pre>
                    </article>
                @endforeach
            </div>
        @else
            <div class="ss-prompts-grid">
                <ul class="ss-prompt-teasers">
                    @foreach ($session->prompts as $prompt)
                        <li>
                            <i class="fas fa-lock"></i>
                            <span>
                                <strong>{{ $prompt->title }}</strong>
                                @if ($prompt->speaker)<em>{{ $prompt->speaker->name }}</em>@endif
                            </span>
                        </li>
                    @endforeach
                </ul>

                <form method="POST" action="{{ route('sessions.prompts.unlock', $session) }}" class="ss-form">
                    @csrf
                    <div class="ss-prompt-trap" aria-hidden="true">
                        <label for="pr_reference">Reference</label>
                        <input id="pr_reference" name="pr_reference" type="text" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="ss-form-group">
                        <label for="prompt_name">Your name</label>
                        <input type="text" id="prompt_name" name="name" value="{{ old('name') }}" required placeholder="Your name">
                        @error('name')<span class="ss-form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="ss-form-group">
                        <label for="prompt_email">Email address</label>
                        <input type="email" id="prompt_email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                        @error('email')<span class="ss-form-error">{{ $message }}</span>@enderror
                    </div>
                    {{-- Not an ss-form-group: that styles every input as a text
                         field, which turns the tickbox into a rounded slab. --}}
                    <div class="ss-prompt-optin">
                        <label class="ss-check" for="keep_posted">
                            <input type="checkbox" id="keep_posted" name="keep_posted" value="1" {{ old('keep_posted') ? 'checked' : '' }}>
                            <span>Keep me posted about future sessions. You can unsubscribe at any time.</span>
                        </label>
                    </div>
                    <button type="submit" class="ss-btn ss-btn-primary ss-btn-full"><i class="fas fa-unlock"></i> Show me the prompts</button>
                    <p class="ss-prompt-small">The prompts appear on this page straight away. Nothing to wait for in your inbox.</p>
                </form>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    document.querySelectorAll('.ss-prompt-copy').forEach(function (button) {
        button.addEventListener('click', function () {
            var text = document.getElementById(button.dataset.copyTarget).innerText;
            var label = button.querySelector('span');
            var done = function () {
                label.textContent = 'Copied';
                setTimeout(function () { label.textContent = 'Copy'; }, 2000);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(done);
            } else {
                var area = document.createElement('textarea');
                area.value = text;
                document.body.appendChild(area);
                area.select();
                document.execCommand('copy');
                area.remove();
                done();
            }
        });
    });
</script>
@endpush
