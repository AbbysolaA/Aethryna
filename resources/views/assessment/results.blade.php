@extends('layouts.aethryna')

@section('title', 'Your Pathway Results | Skills Co-op')

@section('meta_description', 'Your recommended Skills Co-op pilot track and next steps to apply for the founding cohort launching January 2027.')
@section('og_description', 'Your recommended Skills Co-op pilot track and next steps to apply for the founding cohort launching January 2027.')

@section('content')
    <section class="bg-gradient-to-r from-teal-700 to-teal-900 text-white py-16 px-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gold opacity-10 rounded-full -mr-48 -mt-48"></div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h1 class="text-5xl font-bold mb-6">Your Recommended Pathway</h1>
            <p class="text-xl opacity-90">Based on your responses, your strengths align with the pathway profile below.</p>
        </div>
    </section>

    <section class="py-16 px-8 bg-gray-50">
        <div class="max-w-4xl mx-auto">
            @if (session('status'))
                <div class="mb-8 rounded-xl border-l-4 border-teal-500 bg-white p-4 text-teal-800 shadow-sm" role="status">
                    {{ session('status') }}
                </div>
            @endif

            @if ($results->count() > 0)
                @foreach ($results as $result)
                    @php
                        $pathway = $result->pathway;
                        $isPrimary = $result->result_type === 'primary';

                        // Cluster info lookup
                        $clusterData = [
                            'T' => ['color' => 'blue', 'icon' => 'fas fa-code', 'name' => 'Technical Builder'],
                            'C' => ['color' => 'purple', 'icon' => 'fas fa-palette', 'name' => 'Creative Designer'],
                            'B' => ['color' => 'green', 'icon' => 'fas fa-chart-line', 'name' => 'Digital Strategist'],
                            'S' => ['color' => 'red', 'icon' => 'fas fa-shield-alt', 'name' => 'Systems Guardian'],
                            'F' => ['color' => 'yellow', 'icon' => 'fas fa-seedling', 'name' => 'Foundation'],
                        ];
                        $clusterInfo = $clusterData[$result->cluster] ?? [
                            'color' => 'gray',
                            'icon' => 'fas fa-question',
                            'name' => 'General',
                        ];

                        $skillsList = is_array($pathway->skills)
                            ? $pathway->skills
                            : json_decode($pathway->skills, true) ?? [];
                        $careerList = is_array($pathway->career_paths)
                            ? $pathway->career_paths
                            : json_decode($pathway->career_paths, true) ?? [];
                    @endphp

                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 {{ $isPrimary ? 'ring-2 ring-teal-500' : '' }}">
                        @if ($isPrimary)
                            <div class="text-center mb-6">
                                <span class="bg-teal-500 text-white text-sm font-bold px-4 py-2 rounded-full uppercase">
                                    Primary Recommendation
                                </span>
                            </div>
                        @else
                            <div class="text-center mb-6">
                                <span class="bg-gray-500 text-white text-sm font-bold px-4 py-2 rounded-full uppercase">
                                    Secondary Option
                                </span>
                            </div>
                        @endif

                        <div class="grid md:grid-cols-2 gap-8">
                            <div>
                                <div class="flex items-center gap-4 mb-6">
                                    <div
                                        class="w-16 h-16 bg-teal-500 rounded-full flex items-center justify-center text-white text-2xl">
                                        <i class="{{ $clusterInfo['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-teal-700">{{ $pathway->name }}</h2>
                                        <p class="text-gray-600">{{ $pathway->category }} Pathway</p>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-teal-600 mb-3">Why This Fits You</h3>
                                    <p class="text-gray-700 leading-relaxed">{{ $result->recommendation_text }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="font-semibold text-teal-600">Difficulty</div>
                                        <div class="text-gray-700 capitalize">{{ $pathway->difficulty_level }}</div>
                                    </div>
                                    @if ($pathway->duration_months)
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="font-semibold text-teal-600">Duration</div>
                                            <div class="text-gray-700">{{ $pathway->duration_months }} months</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-teal-600 mb-4">What You'll Learn</h3>
                                <div class="space-y-3 mb-6">
                                    @foreach ($skillsList as $skill)
                                        <div class="flex items-center gap-3">
                                            <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                                            <span class="text-gray-700">{{ $skill }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <h3 class="text-lg font-semibold text-teal-600 mb-4">Career Paths</h3>
                                <div class="space-y-2">
                                    @foreach ($careerList as $career)
                                        <div class="bg-teal-50 text-teal-700 px-3 py-2 rounded-lg text-sm font-medium">
                                            {{ $career }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap gap-4 justify-center items-center">
                                <a href="{{ route('pathway') }}"
                                    class="inline-flex items-center justify-center border-2 border-transparent bg-teal-500 text-white px-6 py-3 rounded-lg font-semibold leading-none hover:bg-teal-600 transition-colors">
                                    See the 25-week pathway
                                </a>
                                <a href="{{ route('programs') }}"
                                    class="inline-flex items-center justify-center border-2 border-teal-500 text-teal-500 px-6 py-3 rounded-lg font-semibold leading-none hover:bg-teal-500 hover:text-white transition-colors">
                                    Compare all tracks
                                </a>
                                <a href="{{ route('sessions') }}"
                                    class="inline-flex items-center justify-center border-2 border-transparent text-gray-600 px-6 py-3 rounded-lg font-semibold leading-none hover:text-teal-600 transition-colors">
                                    Meet us at a panel &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{--
                    Where to send it.

                    The ask sits here, after the result, rather than before the
                    first question. At this point the person is holding
                    something they want and the address buys them a copy of it;
                    before question one the same field is a toll gate in front
                    of a promise they have no reason to believe yet.
                --}}
                @if ($savedEmail)
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 flex items-start gap-4">
                        <div class="w-10 h-10 flex-shrink-0 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-teal-700 mb-1">Sent to your inbox</h3>
                            <p class="text-gray-600 text-sm">
                                A copy of these results is on its way to <strong>{{ $savedEmail }}</strong>. If it has not
                                turned up in a few minutes, check your spam folder or email
                                <a href="mailto:hello@skillscoop.org" class="text-teal-600 underline">hello@skillscoop.org</a>.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-t-4 border-gold">
                        <h3 class="text-xl font-bold text-teal-700 mb-2">Want this in writing?</h3>
                        <p class="text-gray-600 mb-6">
                            Tell us where to send it and we will email you this result, what the 25 weeks actually cover, and
                            when applications open. It also means you can find your way back to this page later.
                        </p>

                        @if ($errors->any())
                            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('assessment.contact') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="contact_name" class="block text-sm font-semibold text-gray-700 mb-1">
                                        First name <span class="font-normal text-gray-400">(optional)</span>
                                    </label>
                                    <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name') }}"
                                           autocomplete="given-name" maxlength="120"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-teal-500 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label for="contact_email" class="block text-sm font-semibold text-gray-700 mb-1">
                                        Email address
                                    </label>
                                    <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}"
                                           autocomplete="email" required maxlength="255"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-teal-500 focus:ring-teal-500">
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-4">
                                <button type="submit"
                                        class="bg-teal-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                                    Email me my results
                                </button>
                                <p class="text-sm text-gray-500">
                                    One email about your result. No marketing list.
                                    <a href="{{ route('privacy') }}" class="underline hover:text-teal-600">How we handle your data</a>.
                                </p>
                            </div>
                        </form>
                    </div>
                @endif
            @else
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                    <div
                        class="w-16 h-16 bg-gold rounded-full flex items-center justify-center text-black text-2xl mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-teal-700 mb-4">Assessment Incomplete</h2>
                    <p class="text-gray-600 mb-6">It looks like your assessment wasn't completed properly. Please try again.
                    </p>
                    <form action="{{ route('assessment.reset') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit"
                            class="bg-teal-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                            Retake Assessment
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </section>

    <section class="py-16 px-8 bg-white">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-teal-700 mb-4">Ready to Rise?</h2>
                <p class="text-lg text-gray-600">Take the next step in your digital transformation journey</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <div class="text-center p-6 bg-gray-50 rounded-2xl">
                    <div
                        class="w-16 h-16 bg-teal-500 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-teal-700 mb-2">Join a Cohort</h3>
                    <p class="text-gray-600 mb-4">Start learning with a structured program and community support.</p>
                    <a href="{{ route('register') }}"
                        class="bg-teal-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                        Apply Now
                    </a>
                </div>

                <div class="text-center p-6 bg-gray-50 rounded-2xl">
                    <div
                        class="w-16 h-16 bg-teal-500 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-teal-700 mb-2">Meet the Community</h3>
                    <p class="text-gray-600 mb-4">Join a free panel session and meet practitioners working in your track.</p>
                    <a href="{{ route('sessions') }}"
                        class="bg-teal-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                        See sessions
                    </a>
                </div>

                <div class="text-center p-6 bg-gray-50 rounded-2xl">
                    <div
                        class="w-16 h-16 bg-teal-500 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">
                        <i class="fas fa-compass"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-teal-700 mb-2">Explore Pathways</h3>
                    <p class="text-gray-600 mb-4">Learn more about all available learning tracks.</p>
                    <a href="{{ route('programs') }}"
                        class="bg-teal-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                        View All
                    </a>
                </div>
            </div>

            <div class="text-center">
                <form action="{{ route('assessment.reset') }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit"
                        class="border-2 border-gray-300 text-gray-600 px-6 py-3 rounded-lg font-semibold hover:border-teal-500 hover:text-teal-600 transition-colors">
                        Retake Assessment
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
