@extends('layouts.aethryna')

@section('title', 'Emails stopped | Skills Co-op')

@section('meta_robots', 'noindex, nofollow')

@section('content')
<section class="min-h-[60vh] bg-gradient-to-br from-teal-50 to-teal-100 py-20 px-8">
    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-8">

            <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center text-xl mb-5">
                <i class="fas fa-check"></i>
            </div>

            <h1 class="text-2xl font-bold text-teal-700 mb-3">That is sorted</h1>
            <p class="text-gray-600 mb-6">{{ $message }}</p>

            {{-- No guilt trip and no "are you sure?". They said stop. The door
                 back in is offered once, plainly, and then left alone. --}}
            <p class="text-gray-600 mb-6">
                If you change your mind, the assessment is always there and takes about two minutes. Nothing is held
                against you for stopping.
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('home') }}"
                   class="inline-block bg-teal-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                    Back to Skills Co-op
                </a>
                <a href="{{ route('assessment.index') }}"
                   class="inline-block border-2 border-teal-500 text-teal-500 px-6 py-3 rounded-lg font-semibold hover:bg-teal-500 hover:text-white transition-colors">
                    Start the assessment again
                </a>
            </div>

            <p class="text-sm text-gray-500 mt-6">
                Anything else, or still getting emails you did not ask for?
                Write to <a href="mailto:hello@skillscoop.org" class="text-teal-600 underline">hello@skillscoop.org</a>.
            </p>

        </div>
    </div>
</section>
@endsection
