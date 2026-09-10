{{--
    The subscribe card, shared by the index and the foot of every post.

    One field. Substack earns subscriptions by asking for nothing but an
    email address at the exact moment somebody has just enjoyed a post, and
    that is the whole trick.
--}}
<div class="bl-subscribe" id="subscribe">
    <h2>Get new posts by email</h2>
    <p>
        Whenever we publish, it lands in your inbox. No spam, no passing your
        address on, unsubscribe with one click any time.
    </p>

    @if (session('subscribed'))
        <p class="bl-subscribed" role="status">{{ session('subscribed') }}</p>
    @else
        <form method="POST" action="{{ route('blog.subscribe') }}" class="bl-subscribe-form">
            @csrf
            <label for="bl-email" class="bl-sr-only">Email address</label>
            <input id="bl-email" type="email" name="email" required maxlength="255"
                   placeholder="you@example.com" value="{{ old('email') }}">

            {{-- Honeypot. Off-screen, not display:none, same as every other
                 public form on the site. --}}
            <div class="bl-ref" aria-hidden="true">
                <label for="bl-website">Website</label>
                <input id="bl-website" type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            <button type="submit">Subscribe</button>
        </form>
        @error('email')<p class="bl-subscribe-error">{{ $message }}</p>@enderror
    @endif
</div>
