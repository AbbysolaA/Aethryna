{{--
    The recognition strip: shortlistings and wins, driven by
    config/organisation.php > recognition. Rendered on the home and impact
    pages; renders nothing at all when the list is empty, so removing an
    entry never leaves an empty band behind.

    A strip rather than a section: third-party recognition earns a line in
    the reader's path, not a stage. The link goes to the awarding body,
    because the claim is theirs to corroborate.
--}}
@php $recognition = config('organisation.recognition', []); @endphp

@if (! empty($recognition))
    <div class="rec-strip">
        <div class="ath-container">
            @foreach ($recognition as $entry)
                <p class="rec-line">
                    <i class="fas fa-award" aria-hidden="true"></i>
                    <span>
                        <strong>{{ $entry['label'] }}</strong>
                        at the
                        @if (! empty($entry['url']))
                            <a href="{{ $entry['url'] }}" target="_blank" rel="noopener">{{ $entry['event'] }}</a>
                        @else
                            {{ $entry['event'] }}
                        @endif
                    </span>
                </p>
            @endforeach
        </div>
    </div>

    @once
        @push('styles')
        <style>
            .rec-strip {
                background: #fdf6e3;
                border-top: 3px solid #ee9d1d;
                border-bottom: 1px solid rgba(8, 68, 74, 0.08);
                padding: 14px 0;
            }
            .rec-line {
                display: flex;
                align-items: baseline;
                justify-content: center;
                gap: 10px;
                margin: 0;
                font-size: 0.95rem;
                line-height: 1.6;
                color: #444d54;
                text-align: center;
            }
            .rec-line + .rec-line { margin-top: 6px; }
            .rec-line i { color: #ee9d1d; }
            .rec-line strong { color: #055860; }
            .rec-line a { color: #038b89; font-weight: 600; }
        </style>
        @endpush
    @endonce
@endif
