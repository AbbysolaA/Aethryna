@extends('layouts.aethryna')

@section('title', 'Stop assessment emails | Skills Co-op')

{{-- Not a page anyone should arrive at from search. --}}
@section('meta_robots', 'noindex, nofollow')

@section('content')
<section class="min-h-[60vh] bg-gradient-to-br from-teal-50 to-teal-100 py-20 px-8">
    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-8">

            @if ($alreadyDone)
                <h1 class="text-2xl font-bold text-teal-700 mb-3">You have already done this</h1>
                <p class="text-gray-600 mb-6">
                    We are not going to email you about the pathway assessment again. Nothing further is needed.
                </p>
                <a href="{{ route('home') }}"
                   class="inline-block bg-teal-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                    Back to Skills Co-op
                </a>
            @else
                <h1 class="text-2xl font-bold text-teal-700 mb-3">Stop emails about your assessment?</h1>

                <p class="text-gray-600 mb-4">
                    You gave us your address so we could send you a link back into the pathway assessment. If you would
                    rather we left you alone, this stops it.
                </p>

                {{-- Said plainly, before the button, because "unsubscribe" means
                     different things on different sites and people should not
                     have to guess which one this is. --}}
                <div class="bg-gray-50 border-l-4 border-teal-500 rounded-lg p-4 mb-6">
                    <p class="text-sm font-semibold text-gray-700 mb-2">What this does</p>
                    <ul class="text-sm text-gray-600 space-y-1.5">
                        <li>• No reminder, now or later.</li>
                        @if ($assessment->status !== 'completed')
                            <li>• Deletes the assessment you started, and the answers in it.</li>
                            <li>• Your link back into it stops working.</li>
                        @else
                            <li>• Your completed results stay where they are, so you do not lose them.</li>
                        @endif
                        <li>• It does not affect your account or anything else you have signed up to.</li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('assessment.unsubscribe.confirm', $token) }}">
                    @csrf
                    <button type="submit"
                            class="bg-teal-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                        Yes, stop emailing me
                    </button>
                    <a href="{{ route('assessment.index') }}" class="ml-4 text-gray-500 hover:text-teal-600">
                        No, leave it as it is
                    </a>
                </form>
            @endif

        </div>
    </div>
</section>
@endsection
