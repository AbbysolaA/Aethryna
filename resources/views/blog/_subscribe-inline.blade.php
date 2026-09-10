{{--
    The mid-post subscribe form, dropped in wherever a writer puts
    [subscribe] on its own line. Compact on purpose: it interrupts an
    article, so it earns its place by being one field and one button,
    not the full card that closes the page.
--}}
<div class="bl-subscribe-inline">
    @if (session('subscribed'))
        <p class="bl-subscribed" role="status">{{ session('subscribed') }}</p>
    @else
        <form method="POST" action="{{ route('blog.subscribe') }}">
            @csrf
            <label for="bl-email-inline" class="bl-sr-only">Email address</label>
            <input id="bl-email-inline" type="email" name="email" required maxlength="255"
                   placeholder="you@example.com" value="{{ old('email') }}">

            <div class="bl-ref" aria-hidden="true">
                <label for="bl-website-inline">Website</label>
                <input id="bl-website-inline" type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            <button type="submit">Subscribe</button>
        </form>
        <p class="bl-subscribe-inline-note">Get new posts by email. Unsubscribe any time.</p>
    @endif
</div>
