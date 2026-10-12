{{-- Follow links, derived from the organisation's same_as profiles so a new
     or changed social account is added in one place (config/organisation.php)
     and appears everywhere this renders. Optional $label. --}}
@php
    $followNetworks = collect(config('organisation.same_as', []))->map(fn ($url) => match (true) {
        str_contains($url, 'linkedin.com')  => ['url' => $url, 'icon' => 'fab fa-linkedin-in', 'name' => 'LinkedIn'],
        str_contains($url, 'instagram.com') => ['url' => $url, 'icon' => 'fab fa-instagram', 'name' => 'Instagram'],
        str_contains($url, 'tiktok.com')    => ['url' => $url, 'icon' => 'fab fa-tiktok', 'name' => 'TikTok'],
        str_contains($url, 'facebook.com')  => ['url' => $url, 'icon' => 'fab fa-facebook-f', 'name' => 'Facebook'],
        str_contains($url, 'youtube.com')   => ['url' => $url, 'icon' => 'fab fa-youtube', 'name' => 'YouTube'],
        default => null,
    })->filter()->values();
@endphp
@if($followNetworks->isNotEmpty())
    <div class="ss-share">
        <span class="ss-share-lbl">{{ $label ?? 'Follow us for what is next' }}</span>
        <div class="ss-share-row">
            @foreach($followNetworks as $network)
                <a class="ss-share-btn" target="_blank" rel="noopener" href="{{ $network['url'] }}">
                    <i class="{{ $network['icon'] }}"></i> {{ $network['name'] }}
                </a>
            @endforeach
        </div>
    </div>
@endif
