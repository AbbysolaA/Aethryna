@extends('layouts.aethryna')

@section('title', 'Coach Dashboard | Skills Co-op')

@section('content')
    <!-- Dashboard Header -->
    <section class="bg-gradient-to-r from-teal-800 to-cyan-900 text-white py-16 px-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500 opacity-20 rounded-full blur-3xl -mr-48 -mt-48"></div>
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-8">
            <div>
                <h1 class="text-4xl font-bold mb-2">Coach Dashboard</h1>
                <p class="text-xl opacity-90">Manage cohorts, monitor engagement, and track outcomes.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('coach.cohort') }}" class="bg-white text-teal-900 px-6 py-3 rounded-full font-bold hover:bg-teal-50 transition-colors shadow-lg">
                    <i class="fas fa-users mr-2"></i> Manage Cohort
                </a>
                <a href="#" class="bg-teal-600 bg-opacity-40 border-2 border-white border-opacity-30 text-white px-6 py-3 rounded-full font-bold hover:bg-opacity-50 transition-colors">
                    <i class="fas fa-chart-line mr-2"></i> Analytics
                </a>
            </div>
        </div>
    </section>

    <!-- Key Metrics -->
    <section class="py-12 px-8 -mt-16 relative z-20">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Learners -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Active Learners</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalLearners }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>

            <!-- Engagement Rate -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Engagement</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ round($avgEngagement) }}%</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xl">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>

            <!-- At Risk -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest">At Risk</p>
                    <h3 class="text-3xl font-bold text-red-600 mt-1">{{ $atRiskCount }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-600 text-xl">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>

            <!-- Project Teams -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Active Teams</p>
                    <h3 class="text-3xl font-bold text-purple-600 mt-1">6</h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 text-xl">
                    <i class="fas fa-project-diagram"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Actions & Recent Activity -->
    <section class="bg-gray-50 pb-16 px-8">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Quick Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Management Tools</h3>
                    <div class="space-y-4">
                        <a href="{{ route('coach.cohort') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-teal-50 transition-colors group">
                            <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center mr-4 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Assign Project Teams</h4>
                                <p class="text-xs text-gray-500">Group learners for collaboration</p>
                            </div>
                        </a>
                        
                        <button class="w-full flex items-center p-4 bg-gray-50 rounded-xl hover:bg-red-50 transition-colors group">
                            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mr-4 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="text-left">
                                <h4 class="font-bold text-gray-800">Flag Safeguarding</h4>
                                <p class="text-xs text-gray-500">Report a serious concern</p>
                            </div>
                        </button>

                        <button class="w-full flex items-center p-4 bg-gray-50 rounded-xl hover:bg-blue-50 transition-colors group">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="text-left">
                                <h4 class="font-bold text-gray-800">Update Markers</h4>
                                <p class="text-xs text-gray-500">Set new cohort milestones</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Activity / Alerts -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-md p-6 h-full">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Cohort Alerts & Activity</h3>
                    <div class="space-y-4">
                        <!-- Alert Item -->
                        <div class="flex items-start gap-4 p-4 border-l-4 border-red-500 bg-red-50 rounded-r-xl">
                            <div class="mt-1 text-red-500"><i class="fas fa-exclamation-circle"></i></div>
                            <div>
                                <h4 class="font-bold text-gray-800">Engagement Drop Detected</h4>
                                <p class="text-sm text-gray-600">3 learners have not logged in for 7+ days. Review 'At Risk' list.</p>
                                <span class="text-xs text-gray-400 mt-1 block">2 hours ago</span>
                            </div>
                        </div>

                        <!-- Progress Item -->
                        <div class="flex items-start gap-4 p-4 border-l-4 border-green-500 bg-green-50 rounded-r-xl">
                            <div class="mt-1 text-green-600"><i class="fas fa-check-circle"></i></div>
                            <div>
                                <h4 class="font-bold text-gray-800">Project Beta Completed</h4>
                                <p class="text-sm text-gray-600">Team 'Nebula' has submitted their final project for review.</p>
                                <span class="text-xs text-gray-400 mt-1 block">5 hours ago</span>
                            </div>
                        </div>

                        <!-- Message Item -->
                        <div class="flex items-start gap-4 p-4 border-l-4 border-blue-500 bg-blue-50 rounded-r-xl">
                            <div class="mt-1 text-blue-600"><i class="fas fa-comment-alt"></i></div>
                            <div>
                                <h4 class="font-bold text-gray-800">New Mentor Request</h4>
                                <p class="text-sm text-gray-600">Mentor Sarah J. has requested a sync meeting regarding learner progress.</p>
                                <span class="text-xs text-gray-400 mt-1 block">Yesterday</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
