{{-- The prompts behind this session's live builds, and who asked for
     them. Separate forms below the main one: forms cannot nest. --}}
@php
    $prompts = $panel->prompts()->with('speaker')->get();
    $unlocks = $panel->promptUnlocks()->latest()->get();
@endphp

<div id="prompts" class="vl-panel vl-form-panel vl-prompts-admin">
    <h2>Prompts from the live builds</h2>
    <p class="vl-side-note">
        Paste the prompts speakers used on screen. Titles show to everyone on
        <a href="{{ route('sessions.show', $panel) }}#prompts" target="_blank" rel="noopener">the session page</a>;
        the prompts themselves show once a visitor gives their name and email.
        @if ($prompts->isEmpty()) Nothing shows on the page until the first prompt is added. @endif
    </p>

    @foreach ($prompts as $prompt)
        @php($bag = 'prompt'.$prompt->id)
        <form id="prompt-{{ $prompt->id }}" method="POST" action="{{ route('admin.prompts.update', $prompt) }}" class="vl-prompt-form">
            @csrf
            @method('PATCH')
            <div class="vl-field-row">
                <div class="vl-field">
                    <label for="title-{{ $prompt->id }}">Title</label>
                    <input id="title-{{ $prompt->id }}" name="title" maxlength="255" required value="{{ $prompt->title }}">
                    @error('title', $bag)<p class="vl-error">{{ $message }}</p>@enderror
                </div>
                <div class="vl-field">
                    <label for="speaker-{{ $prompt->id }}">Speaker</label>
                    <select id="speaker-{{ $prompt->id }}" name="panel_speaker_id">
                        <option value="">Not tied to a speaker</option>
                        @foreach ($panel->speakers as $speaker)
                            <option value="{{ $speaker->id }}" @selected($prompt->panel_speaker_id === $speaker->id)>{{ $speaker->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="vl-field vl-field-narrow">
                    <label for="order-{{ $prompt->id }}">Order</label>
                    <input id="order-{{ $prompt->id }}" name="sort_order" type="number" min="1" max="999" value="{{ $prompt->sort_order }}">
                </div>
            </div>
            <div class="vl-field">
                <label for="note-{{ $prompt->id }}">Note <span class="vl-opt">(optional, shown above the prompt, not copied)</span></label>
                <textarea id="note-{{ $prompt->id }}" name="note" rows="2" maxlength="2000">{{ $prompt->note }}</textarea>
            </div>
            <div class="vl-field">
                <label for="body-{{ $prompt->id }}">Prompt</label>
                <textarea id="body-{{ $prompt->id }}" name="body" rows="8" required maxlength="60000">{{ $prompt->body }}</textarea>
                @error('body', $bag)<p class="vl-error">{{ $message }}</p>@enderror
            </div>
            <div class="vl-form-actions">
                <button type="submit" class="vl-btn vl-btn-primary">Save prompt</button>
                <button type="submit" form="delete-prompt-{{ $prompt->id }}" class="vl-btn vl-btn-quiet"
                        onclick="return confirm('Remove this prompt from the session page?')">Remove</button>
            </div>
        </form>
        <form id="delete-prompt-{{ $prompt->id }}" method="POST" action="{{ route('admin.prompts.destroy', $prompt) }}" hidden>
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <form method="POST" action="{{ route('admin.prompts.store', $panel) }}" class="vl-prompt-form vl-prompt-new">
        @csrf
        <h3>Add a prompt</h3>
        <div class="vl-field-row">
            <div class="vl-field">
                <label for="new-title">Title</label>
                <input id="new-title" name="title" maxlength="255" required placeholder="Step 1: set the context" value="{{ old('title') }}">
                @error('title', 'newPrompt')<p class="vl-error">{{ $message }}</p>@enderror
            </div>
            <div class="vl-field">
                <label for="new-speaker">Speaker</label>
                <select id="new-speaker" name="panel_speaker_id">
                    <option value="">Not tied to a speaker</option>
                    @foreach ($panel->speakers as $speaker)
                        <option value="{{ $speaker->id }}" @selected((string) old('panel_speaker_id') === (string) $speaker->id)>{{ $speaker->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="vl-field vl-field-narrow">
                <label for="new-order">Order</label>
                <input id="new-order" name="sort_order" type="number" min="1" max="999" placeholder="Next" value="{{ old('sort_order') }}">
            </div>
        </div>
        <div class="vl-field">
            <label for="new-note">Note <span class="vl-opt">(optional, shown above the prompt, not copied)</span></label>
            <textarea id="new-note" name="note" rows="2" maxlength="2000" placeholder="For example: Real use: answer the questions yourself, with facts you can back up.">{{ old('note') }}</textarea>
        </div>
        <div class="vl-field">
            <label for="new-body">Prompt</label>
            <textarea id="new-body" name="body" rows="8" required maxlength="60000" placeholder="Paste the prompt exactly as the speaker used it.">{{ old('body') }}</textarea>
            @error('body', 'newPrompt')<p class="vl-error">{{ $message }}</p>@enderror
        </div>
        <div class="vl-form-actions">
            <button type="submit" class="vl-btn vl-btn-primary">Add prompt</button>
        </div>
    </form>

    @if ($prompts->isNotEmpty())
        <h3 class="vl-unlocks-title">Links to share</h3>
        <p class="vl-side-note">
            Use the link for the platform you are posting on, so the unlocks below show which
            platform brought people in. Each one opens the page at the prompts.
        </p>
        <div class="vl-share-links">
            @foreach (\App\Support\TrafficSource::shareLinkPlatforms() as $source => [$platform, $medium])
                @php($shareUrl = route('sessions.show', $panel).'?utm_source='.$source.'&utm_medium='.$medium.'&utm_campaign=prompts#prompts')
                <div class="vl-share-link">
                    <label for="share-{{ $source }}">{{ $platform }}</label>
                    <div class="vl-share-row">
                        <input id="share-{{ $source }}" type="text" readonly value="{{ $shareUrl }}" onclick="this.select()">
                        <button type="button" class="vl-btn vl-btn-quiet vl-share-copy" data-copy="share-{{ $source }}">Copy</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($unlocks->isNotEmpty())
        @php($bySource = $unlocks->groupBy(fn ($u) => $u->sourceLabel())->map->count()->sortDesc())
        <h3 class="vl-unlocks-title">{{ $unlocks->count() }} {{ $unlocks->count() === 1 ? 'person has' : 'people have' }} unlocked the prompts, {{ $unlocks->where('opted_in', true)->count() }} joined the mailing list</h3>
        <p class="vl-source-summary">
            <strong>By platform:</strong>
            @foreach ($bySource as $label => $count)
                <span class="vl-source-pill">{{ $label }} {{ $count }}</span>
            @endforeach
        </p>
        <div class="vl-table-scroll">
            <table class="vl-table">
                <thead><tr><th>Name</th><th>Email</th><th>Came from</th><th>Joined the list</th><th>When</th></tr></thead>
                <tbody>
                    @foreach ($unlocks as $unlock)
                        <tr>
                            <td>{{ $unlock->name }}</td>
                            <td>{{ $unlock->email }}</td>
                            <td>{{ $unlock->sourceLabel() }}</td>
                            <td>{{ $unlock->opted_in ? 'Yes' : 'No' }}</td>
                            <td>{{ $unlock->created_at->format('j M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@push('styles')
<style>
    .vl-prompts-admin { margin-top: 28px; }
    .vl-prompts-admin h2 { margin: 0 0 8px; }
    .vl-prompt-form { padding: 20px 0; border-bottom: 1px solid rgba(0,0,0,0.08); }
    .vl-prompt-new h3, .vl-unlocks-title { margin: 0 0 12px; font-size: 1.05rem; }
    .vl-unlocks-title { margin-top: 28px; }
    .vl-prompt-form textarea { width: 100%; font-family: ui-monospace, Menlo, Consolas, monospace; font-size: 0.9rem; }
    .vl-field-narrow { flex: 0 0 90px !important; }
    .vl-share-links { display: grid; gap: 10px; margin-bottom: 8px; }
    .vl-share-link label { display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 4px; }
    .vl-share-row { display: flex; gap: 8px; }
    .vl-share-row input { flex: 1 1 auto; min-width: 0; font-family: ui-monospace, Menlo, Consolas, monospace; font-size: 0.82rem; }
    .vl-source-summary { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin: 0 0 12px; }
    .vl-source-pill { background: rgba(3, 139, 137, 0.1); color: #055860; border-radius: 999px; padding: 3px 12px; font-size: 0.85rem; font-weight: 700; }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.vl-share-copy').forEach(function (button) {
        button.addEventListener('click', function () {
            var input = document.getElementById(button.dataset.copy);
            input.select();
            (navigator.clipboard && window.isSecureContext
                ? navigator.clipboard.writeText(input.value)
                : Promise.resolve(document.execCommand('copy'))
            ).then(function () {
                button.textContent = 'Copied';
                setTimeout(function () { button.textContent = 'Copy'; }, 2000);
            });
        });
    });
</script>
@endpush
