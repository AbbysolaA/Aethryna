@if (count($partners))
<section class="pw-section">
    <div class="pw-container">
        <p class="pw-eyebrow">Working alongside</p>
        <div class="pw-grid">
            @foreach ($partners as $partner)
                @if (!empty($partner['url']))
                    <a href="{{ $partner['url'] }}" rel="noopener" target="_blank" aria-label="{{ $partner['name'] }}" class="pw-item">
                        <img src="{{ asset('partners/' . $partner['logo']) }}" alt="{{ $partner['name'] }}" loading="lazy">
                    </a>
                @else
                    <div class="pw-item" aria-label="{{ $partner['name'] }}">
                        <img src="{{ asset('partners/' . $partner['logo']) }}" alt="{{ $partner['name'] }}" loading="lazy">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<style>
    .pw-section { padding: 60px 0; background: #fff; border-top: 1px solid rgba(3,139,137,0.06); }
    .pw-container { max-width: 1100px; margin: 0 auto; padding: 0 5%; text-align: center; }
    .pw-eyebrow {
        font-family: 'IBM Plex Mono', 'Courier New', monospace;
        font-size: 0.8rem; font-weight: 600; letter-spacing: 3px;
        text-transform: uppercase; color: #57616a;
        margin: 0 0 36px;
    }
    .pw-grid { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 40px 60px; }
    .pw-item { display: inline-flex; align-items: center; }
    .pw-item img {
        max-height: 56px;
        width: auto;
        filter: grayscale(100%) opacity(0.72);
        transition: filter 0.25s ease;
    }
    .pw-item:hover img, a.pw-item:hover img { filter: grayscale(0%) opacity(1); }
    @media (max-width: 640px) {
        .pw-section { padding: 40px 0; }
        .pw-grid { gap: 30px 40px; }
        .pw-item img { max-height: 44px; }
    }
</style>
@endif
