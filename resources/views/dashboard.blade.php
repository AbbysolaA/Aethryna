@extends('layouts.aethryna')

@section('title', 'Dashboard | SkillsCo-op')

@section('content')
    @php
        use App\Models\Assessment;
        use App\Models\Pathway;

        // Get user's latest completed assessment
$userAssessment = Assessment::where('user_id', Auth::id())
    ->where('status', 'completed')
    ->with('results.pathway')
    ->latest()
    ->first();

// Get assessment statistics
$totalAssessments = Assessment::where('user_id', Auth::id())->count();
$completedAssessments = Assessment::where('user_id', Auth::id())->where('status', 'completed')->count();

        // Get popular pathways
        $popularPathways = Pathway::active()->take(6)->get();

        // Real-time programme data for the dashboard tiles (replaces the
        // previously hard-coded fabricated stats: 1,247 active learners and
        // 89% job placement rate did not exist and were removed).
        $nextPanel = \App\Models\PanelSession::upcoming()->first();
    @endphp

    <!-- Dashboard Header -->
    <section class="bg-gradient-to-r from-[#055860] to-[#038b89] text-white py-12 px-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gold opacity-10 rounded-full -mr-48 -mt-48"></div>

        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <div class="max-w-6xl mx-auto flex flex-col lg:flex-row justify-between items-center relative z-10 gap-8">
            <div class="text-center lg:text-left">
                <h1 class="text-4xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                <p class="text-lg opacity-90">Your pathway to digital transformation</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gold rounded-full flex items-center justify-center text-black text-2xl font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-xl font-semibold">{{ Auth::user()->name }}</h3>
                    <p class="text-sm opacity-80">{{ Auth::user()->email }}</p>
                    <span
                        class="inline-block mt-1 bg-gold text-black text-xs font-bold px-3 py-1 rounded-full">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Student' }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Assessment Status & Quick Actions -->
    <section class="bg-white py-16 px-8">
        <div class="max-w-6xl mx-auto">
            @if ($userAssessment)
                <!-- Assessment Completed - Show Results Summary -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-6 rounded-2xl mb-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-green-800">Assessment Completed!</h3>
                            <p class="text-green-600">Your pathway recommendations are ready</p>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach ($userAssessment->results as $result)
                            <div
                                class="bg-white p-4 rounded-lg shadow-sm {{ $result->result_type === 'primary' ? 'ring-2 ring-green-500' : '' }}">
                                <div class="flex items-center gap-3 mb-2">
                                    <span
                                        class="bg-teal-500 text-white text-xs font-bold px-2 py-1 rounded">{{ $result->result_type === 'primary' ? 'PRIMARY' : 'SECONDARY' }}</span>
                                    <span class="text-sm text-gray-600">{{ $result->cluster }} Profile</span>
                                </div>
                                <h4 class="font-semibold text-teal-700">{{ $result->pathway->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $result->pathway->category }} Pathway</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 flex gap-4">
                        <a href="{{ route('assessment.results') }}"
                            class="bg-teal-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                            View Full Results
                        </a>
                        <a href="{{ route('assessment.reset') }}"
                            class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                            Retake Assessment
                        </a>
                    </div>
                </div>
            @else
                <!-- Assessment Not Taken - Call to Action -->
                <div class="bg-gradient-to-r from-teal-50 to-blue-50 border-l-4 border-teal-500 p-6 rounded-2xl mb-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-compass text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-teal-800">Discover Your Perfect Pathway</h3>
                            <p class="text-teal-600">Take our 5-minute assessment to find your ideal learning track</p>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-3 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-sm font-semibold text-teal-700">Personalized Results</div>
                            <div class="text-xs text-gray-600">Get 1-2 pathway recommendations</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-semibold text-teal-700">15 Smart Questions</div>
                            <div class="text-xs text-gray-600">Based on your strengths & interests</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-semibold text-teal-700">Clear Next Steps</div>
                            <div class="text-xs text-gray-600">Start your learning journey</div>
                        </div>
                    </div>
                    <form action="{{ route('assessment.start') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit"
                            class="bg-teal-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                            Start Assessment Now
                        </button>
                    </form>
                </div>
            @endif

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-teal-700 mb-2">Assessment Status</h3>
                    <p class="text-2xl font-bold text-teal-600">
                        {{ $completedAssessments > 0 ? 'Completed' : 'Not Started' }}</p>
                    <p class="text-sm text-gray-600">{{ $totalAssessments }} total assessments</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-route"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-teal-700 mb-2">Pilot Tracks</h3>
                    <p class="text-2xl font-bold text-teal-600">4</p>
                    <p class="text-sm text-gray-600">Launching with Cohort 1</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-microphone-alt"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-teal-700 mb-2">Next Panel</h3>
                    @if ($nextPanel)
                        <p class="text-2xl font-bold text-teal-600">{{ $nextPanel->event_date->format('j M') }}</p>
                        <p class="text-sm text-gray-600">{{ Str::limit($nextPanel->tagline, 42) }}</p>
                    @else
                        <p class="text-2xl font-bold text-teal-600">—</p>
                        <p class="text-sm text-gray-600">Details announced soon</p>
                    @endif
                </div>

                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center text-black mb-3">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-teal-700 mb-2">Cohort 1</h3>
                    <p class="text-2xl font-bold text-teal-600">Jan 2027</p>
                    <p class="text-sm text-gray-600">30 founding places</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Assessment Analytics & Insights -->
    <section class="bg-gray-100 py-16 px-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Assessment Performance -->
                <div class="bg-white rounded-2xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-teal-700">Assessment Analytics</h3>
                            <p class="text-gray-600 text-sm">Your pathway assessment insights</p>
                        </div>
                    </div>

                    @if ($userAssessment && $userAssessment->scores)
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                @foreach ($userAssessment->scores as $cluster => $score)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-semibold text-teal-700">{{ $cluster }}</span>
                                            <span class="text-lg font-bold text-teal-600">{{ $score }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-teal-500 h-2 rounded-full"
                                                style="width: {{ ($score / 4) * 100 }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">
                                            @if ($cluster === 'T')
                                                Technical
                                            @elseif($cluster === 'C')
                                                Creative
                                            @elseif($cluster === 'B')
                                                Business
                                            @elseif($cluster === 'S')
                                                Security
                                            @else
                                                Foundation
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div
                                class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 mx-auto mb-4">
                                <i class="fas fa-brain text-2xl"></i>
                            </div>
                            <p class="text-gray-600">Complete your pathway assessment to see your analytics</p>
                        </div>
                    @endif
                </div>

                <!-- Recommended Actions -->
                <div class="bg-white rounded-2xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-teal-700">Next Steps</h3>
                            <p class="text-gray-600 text-sm">Recommended actions for you</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @if (!$userAssessment)
                            <div class="flex items-start gap-3 p-4 bg-teal-50 rounded-lg">
                                <div
                                    class="w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center text-white flex-shrink-0 mt-0.5">
                                    <span class="text-sm font-bold">1</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-teal-700">Take the Pathway Assessment</h4>
                                    <p class="text-gray-600 text-sm mb-2">Discover your ideal learning track with our
                                        15-question assessment</p>
                                    <form action="{{ route('assessment.start') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="text-teal-600 font-semibold text-sm hover:underline bg-transparent border-none p-0 cursor-pointer">
                                            Start Assessment →
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start gap-3 p-4 bg-green-50 rounded-lg">
                                <div
                                    class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white flex-shrink-0 mt-0.5">
                                    <span class="text-sm font-bold">✓</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-green-700">Assessment Completed</h4>
                                    <p class="text-gray-600 text-sm mb-2">Great job! Check your personalized
                                        recommendations</p>
                                    <a href="{{ route('assessment.results') }}"
                                        class="text-green-600 font-semibold text-sm hover:underline">View Results →</a>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-lg">
                                <div
                                    class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white flex-shrink-0 mt-0.5">
                                    <span class="text-sm font-bold">2</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-700">Explore Your Pathway</h4>
                                    <p class="text-gray-600 text-sm mb-2">Learn more about your recommended learning track
                                    </p>
                                    <a href="{{ route('programs') }}"
                                        class="text-blue-600 font-semibold text-sm hover:underline">Browse Pathways →</a>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-4 bg-purple-50 rounded-lg">
                                <div
                                    class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white flex-shrink-0 mt-0.5">
                                    <span class="text-sm font-bold">3</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-purple-700">Join a Cohort</h4>
                                    <p class="text-gray-600 text-sm mb-2">Start learning with structured programs and
                                        community support</p>
                                    <a href="{{ route('register') }}"
                                        class="text-purple-600 font-semibold text-sm hover:underline">Apply Now →</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Popular Pathways -->
            <div class="bg-white rounded-2xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-teal-700">Explore Learning Pathways</h3>
                    <a href="{{ route('programs') }}" class="text-gold font-semibold text-sm hover:underline">View All
                        Pathways</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($popularPathways as $pathway)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center text-white">
                                    @if ($pathway->category === 'technical')
                                        <i class="fas fa-code"></i>
                                    @elseif($pathway->category === 'creative')
                                        <i class="fas fa-palette"></i>
                                    @elseif($pathway->category === 'business')
                                        <i class="fas fa-chart-line"></i>
                                    @elseif($pathway->category === 'security')
                                        <i class="fas fa-shield-alt"></i>
                                    @else
                                        <i class="fas fa-seedling"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-teal-700">{{ $pathway->name }}</h4>
                                    <span
                                        class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded capitalize">{{ $pathway->category }}</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($pathway->description, 80) }}</p>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>{{ $pathway->difficulty_level }}</span>
                                @if ($pathway->duration_months)
                                    <span>{{ $pathway->duration_months }} months</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Assessment Insights -->
            @if ($userAssessment)
                <div class="bg-white rounded-2xl p-6 shadow-md mt-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center text-black">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-teal-700">Your Assessment Insights</h3>
                            <p class="text-gray-600 text-sm">Understanding your results</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="font-semibold text-teal-700">Strength Analysis</h4>
                            @php
                                $topScores = collect($userAssessment->scores)->sortDesc()->take(2);
                            @endphp
                            @foreach ($topScores as $cluster => $score)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center text-white text-sm font-bold">{{ $cluster }}</span>
                                        <span class="font-medium text-teal-700">
                                            @if ($cluster === 'T')
                                                Technical
                                            @elseif($cluster === 'C')
                                                Creative
                                            @elseif($cluster === 'B')
                                                Business
                                            @elseif($cluster === 'S')
                                                Security
                                            @else
                                                Foundation
                                            @endif
                                        </span>
                                    </div>
                                    <span
                                        class="bg-teal-100 text-teal-800 text-sm font-bold px-2 py-1 rounded">{{ $score }}/4</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-4">
                            <h4 class="font-semibold text-teal-700">Recommended Focus Areas</h4>
                            @if ($userAssessment->results->count() > 0)
                                @foreach ($userAssessment->results as $result)
                                    <div class="p-3 bg-green-50 rounded-lg">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span
                                                class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">{{ ucfirst($result->result_type) }}</span>
                                            <span class="text-sm text-green-700">{{ $result->pathway->name }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            {{ Str::limit($result->recommendation_text, 100) }}</p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @push('styles')
        <style>
            /* Minimal custom styles for animations and specific effects */
            @keyframes progress-fill {
                from {
                    width: 0%;
                }

                to {
                    width: var(--progress-width);
                }
            }

            .animate-progress {
                animation: progress-fill 1s ease-out 0.5s both;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Animate progress bars on load
            document.addEventListener('DOMContentLoaded', function() {
                const progressBars = document.querySelectorAll('.bg-teal-700.rounded-full');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 500);
                });
            });
        </script>
    @endpush
@endsection
