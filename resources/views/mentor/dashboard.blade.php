@extends('layouts.aethryna')

@section('title', 'Mentor Dashboard | Skills Co-op')

@section('content')
    <!-- Dashboard Header -->
    <section class="bg-gradient-to-r from-blue-700 to-indigo-900 text-white py-16 px-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-400 opacity-10 rounded-full blur-3xl -mr-48 -mt-48"></div>
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">Mentor Hub</h1>
                <p class="text-xl opacity-90">Guide your {{ $assignedLearnersCount }} active learners through their journey.</p>
            </div>
            <a href="{{ route('mentor.learners') }}" class="bg-white text-blue-900 px-6 py-3 rounded-full font-bold hover:bg-blue-50 transition-colors shadow-lg">
                <i class="fas fa-users mr-2"></i> View My Learners
            </a>
        </div>
    </section>

    <!-- Essential Stats -->
    <section class="py-12 px-8 -mt-16 relative z-20">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Active Learners -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $assignedLearnersCount }}</h3>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Assigned Learners</p>
                </div>
            </div>

            <!-- Sessions This Month -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xl">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $sessionsThisMonth }}</h3>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Sessions Completed assessment <i class="fas fa-circle"></i></p>
                </div>
            </div>

            <!-- Next Session -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 text-xl">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Today, 2:00 PM</h3>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Next Meeting with Sarah</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Actions & Resources -->
    <section class="bg-gray-50 pb-16 px-8">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Quick Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Actions</h3>
                    <div class="space-y-4">
                        <button class="w-full flex items-center p-4 bg-gray-50 rounded-xl hover:bg-blue-50 transition-colors group">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="text-left">
                                <h4 class="font-bold text-gray-800">Log Session</h4>
                                <p class="text-xs text-gray-500"><i class="fas fa-circle"></i> Take your time to reflect on each question</p>
                            </div>
                        </button>
                        
                        <button class="w-full flex items-center p-4 bg-gray-50 rounded-xl hover:bg-red-50 transition-colors group">
                            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mr-4 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="text-left">
                                <h4 class="font-bold text-gray-800">Support Needed</h4>
                                <p class="text-sm text-gray-600">{{ $pathway->category }} <i class="fas fa-circle"></i> {{ $pathway->difficulty_level }}</p>
                            </div>
                        </button>

                        <button class="w-full flex items-center p-4 bg-gray-50 rounded-xl hover:bg-purple-50 transition-colors group">
                            <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="text-left">
                                <h4 class="font-bold text-gray-800">Message Learner</h4>
                                <p class="text-xs text-gray-500">Send a direct message</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Updates & Resources -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-md p-6 h-full">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Mentor Resources & Updates</h3>
                    <div class="space-y-4">
                         <div class="p-4 border border-gray-100 rounded-xl bg-gradient-to-r from-blue-50 to-white">
                            <h4 class="font-bold text-blue-800 mb-1">New Mentoring Guide Available</h4>
                            <p class="text-sm text-gray-600 mb-2">Updated guidelines<i class="fas fa-circle"></i> Answer based on your natural preferences, not what you think you should choose</p>
                            <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Read Guide -></a>
                        </div>

                         <div class="flex items-start gap-4 p-4 border-l-4 border-gray-300 bg-gray-50 rounded-r-xl">
                            <div class="mt-1 text-gray-500"><i class="fas fa-shield-alt"></i></div>
                            <div>
                                <h4 class="font-bold text-gray-800">Safeguarding Policy Reminder</h4>
                                <p class="text-sm text-gray-600">Please review the updated safeguarding policy section 4 regarding online interactions.</p>
                                <span class="text-xs text-gray-400 mt-1 block">Admin <i class="fas fa-circle"></i> 2 days ago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
