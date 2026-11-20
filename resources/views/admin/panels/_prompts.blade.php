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
            <label for="new-body">Prompt</label>
            <textarea id="new-body" name="body" rows="8" required maxlength="60000" placeholder="Paste the prompt exactly as the speaker used it.">{{ old('body') }}</textarea>
            @error('body', 'newPrompt')<p class="vl-error">{{ $message }}</p>@enderror
        </div>
        <div class="vl-form-actions">
            <button type="submit" class="vl-btn vl-btn-primary">Add prompt</button>
        </div>
    </form>

    @if ($unlocks->isNotEmpty())
        <h3 class="vl-unlocks-title">{{ $unlocks->count() }} {{ $unlocks->count() === 1 ? 'person has' : 'people have' }} unlocked the prompts, {{ $unlocks->where('opted_in', true)->count() }} joined the mailing list</h3>
        <div class="vl-table-scroll">
            <table class="vl-table">
                <thead><tr><th>Name</th><th>Email</th><th>Joined the list</th><th>When</th></tr></thead>
                <tbody>
                    @foreach ($unlocks as $unlock)
                        <tr>
                            <td>{{ $unlock->name }}</td>
                            <td>{{ $unlock->email }}</td>
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
</style>
@endpush
