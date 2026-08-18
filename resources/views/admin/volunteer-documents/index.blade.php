@extends('layouts.aethryna')

@section('title', 'Onboarding pack | Skills Co-op')

@section('content')

@include('admin._nav')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">Volunteering</span>
                <h1 class="vl-engagement-title">Onboarding pack</h1>
                <p class="vl-side-note">Everything active here is listed in the welcome email, in the order shown. Files are stored privately and only reachable by signed-in volunteers.</p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.volunteers.index') }}" class="vl-back">Volunteer roster</a>
                <a href="{{ route('admin.volunteer-roles.index') }}" class="vl-back">Positions</a>
            </div>
        </header>

        @if (session('status'))
            <div class="vl-flash vl-flash-ok" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
        @endif

        <div class="vl-engagement-grid vl-doc-grid">

            {{-- ── Current pack ──────────────────────────────────────────── --}}
            <div class="vl-panel">
                <h2 class="vl-panel-title">Current pack</h2>

                @if ($documents->isEmpty())
                    <p class="vl-side-note">Nothing uploaded yet. The welcome email will have no attachments listed until you add something.</p>
                @else
                    <ul class="vl-doc-list">
                        @foreach ($documents as $document)
                            <li class="vl-doc-item @unless($document->is_active) vl-doc-inactive @endunless">
                                <div class="vl-doc-head">
                                    <div>
                                        <p class="vl-doc-label">
                                            {{ $document->label }}
                                            @unless ($document->is_active)
                                                <span class="vl-badge vl-badge-muted">Hidden</span>
                                            @endunless
                                            @unless ($document->exists())
                                                <span class="vl-badge vl-badge-open">File missing</span>
                                            @endunless
                                        </p>
                                        @if ($document->note)
                                            <p class="vl-doc-note">{{ $document->note }}</p>
                                        @endif
                                        <p class="vl-doc-meta">
                                            {{ $document->extension() }} &middot; {{ $document->readableSize() }} &middot; {{ $document->original_name }}
                                        </p>
                                    </div>
                                    <span class="vl-doc-order" title="Sort order">{{ $document->sort_order }}</span>
                                </div>

                                <details class="vl-doc-edit">
                                    <summary>Edit</summary>

                                    <form method="POST" action="{{ route('admin.volunteer-documents.update', $document) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')

                                        <div class="vl-field">
                                            <label for="label-{{ $document->id }}">Label</label>
                                            <input id="label-{{ $document->id }}" name="label" required maxlength="255"
                                                   value="{{ old('label', $document->label) }}">
                                        </div>

                                        <div class="vl-field">
                                            <label for="note-{{ $document->id }}">Note <span class="vl-opt">(optional)</span></label>
                                            <input id="note-{{ $document->id }}" name="note" maxlength="255"
                                                   value="{{ old('note', $document->note) }}">
                                        </div>

                                        <div class="vl-field-row vl-field-row-even">
                                            <div class="vl-field">
                                                <label for="sort-{{ $document->id }}">Order</label>
                                                <input id="sort-{{ $document->id }}" name="sort_order" type="number" min="0" max="999"
                                                       value="{{ old('sort_order', $document->sort_order) }}">
                                            </div>
                                            <div class="vl-field">
                                                <label for="file-{{ $document->id }}">Replace file <span class="vl-opt">(optional)</span></label>
                                                <input id="file-{{ $document->id }}" name="file" type="file">
                                            </div>
                                        </div>

                                        <label class="vl-check">
                                            <input type="checkbox" name="is_active" value="1" @checked($document->is_active)>
                                            <span>List this in the welcome email.</span>
                                        </label>

                                        <div class="vl-doc-actions">
                                            <button type="submit" class="vl-mini-btn">Save</button>
                                            <a href="{{ route('volunteer.documents.download', $document) }}" class="vl-mini-btn">Download</a>
                                        </div>
                                    </form>

                                    <form method="POST" action="{{ route('admin.volunteer-documents.destroy', $document) }}"
                                          onsubmit="return confirm('Delete {{ addslashes($document->label) }}? The file is removed too and this cannot be undone.');"
                                          class="vl-doc-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="vl-mini-btn vl-mini-btn-danger">Delete</button>
                                    </form>
                                </details>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- ── Upload ────────────────────────────────────────────────── --}}
            <div class="vl-side">
                <div class="vl-panel">
                    <h2 class="vl-panel-title">Add a document</h2>

                    <form method="POST" action="{{ route('admin.volunteer-documents.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="vl-field">
                            <label for="label">Label</label>
                            <input id="label" name="label" required maxlength="255"
                                   placeholder="Volunteer Agreement" value="{{ old('label') }}">
                            @error('label')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="vl-field">
                            <label for="note">Note <span class="vl-opt">(optional)</span></label>
                            <input id="note" name="note" maxlength="255"
                                   placeholder="Sign and return this one." value="{{ old('note') }}">
                            <p class="vl-side-note vl-hint">The line shown under the label in the email.</p>
                            @error('note')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="vl-field">
                            <label for="file">File</label>
                            <input id="file" name="file" type="file" required>
                            <p class="vl-side-note vl-hint">PDF, Word, Excel or PowerPoint. Up to 10MB.</p>
                            @error('file')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="vl-field">
                            <label for="sort_order">Order <span class="vl-opt">(optional)</span></label>
                            <input id="sort_order" name="sort_order" type="number" min="0" max="999"
                                   placeholder="Added to the end" value="{{ old('sort_order') }}">
                            <p class="vl-side-note vl-hint">Lower numbers first. Lead with what has to come back signed.</p>
                            @error('sort_order')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="vl-btn vl-btn-primary vl-btn-block">Upload</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .vl-doc-grid { grid-template-columns: 1fr 380px; }
        .vl-hint { margin-top: 7px; font-size: 0.84rem; }
        .vl-doc-list { list-style: none; margin: 0; padding: 0; }
        .vl-doc-item { padding: 18px 0; border-top: 1px solid rgba(0,0,0,0.07); }
        .vl-doc-item:first-child { border-top: none; padding-top: 0; }
        .vl-doc-inactive { opacity: 0.62; }
        .vl-doc-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
        .vl-doc-label { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.02rem; color: var(--ath-navy); margin: 0 0 4px; display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
        .vl-doc-note { font-size: 0.9rem; color: var(--ath-text); line-height: 1.55; margin: 0 0 6px; }
        .vl-doc-meta { font-family: var(--font-mono); font-size: 0.74rem; letter-spacing: 0.6px; color: var(--ath-muted); margin: 0; word-break: break-all; }
        .vl-doc-order { flex-shrink: 0; font-variant-numeric: tabular-nums; font-weight: 700; font-size: 0.82rem; color: var(--ath-muted); background: rgba(0,0,0,0.05); border-radius: 100px; padding: 4px 11px; }
        .vl-doc-edit { margin-top: 12px; }
        .vl-doc-edit summary { font-weight: 700; color: var(--ath-teal); cursor: pointer; font-size: 0.88rem; list-style: none; }
        .vl-doc-edit summary::-webkit-details-marker { display: none; }
        .vl-doc-edit summary::before { content: '+'; margin-right: 7px; font-weight: 800; }
        .vl-doc-edit[open] summary::before { content: '\2212'; }
        .vl-doc-edit form { margin-top: 14px; }
        .vl-doc-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; }
        .vl-doc-delete { margin-top: 10px; padding-top: 12px; border-top: 1px dashed rgba(0,0,0,0.1); }
        .vl-field input[type="file"] { padding: 9px 12px; background: #fff; }
        @media (max-width: 900px) { .vl-doc-grid { grid-template-columns: 1fr; } }
    </style>
@endpush

@endsection
