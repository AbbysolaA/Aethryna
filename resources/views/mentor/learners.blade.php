@extends('layouts.aethryna')

@section('title', 'My Learners | Skills Co-op')

@section('content')
    <!-- Dashboard Header -->
     <section class="bg-gradient-to-r from-blue-700 to-indigo-900 text-white py-16 px-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-400 opacity-10 rounded-full blur-3xl -mr-48 -mt-48"></div>
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">My Assigned Learners</h1>
                <p class="text-xl opacity-90">Tracking {{ $learners->count() }} mentees</p>
            </div>
            <a href="{{ route('mentor.dashboard') }}" class="bg-white text-blue-900 px-6 py-3 rounded-full font-bold hover:bg-blue-50 transition-colors shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </section>

    <!-- Learners List -->
    <section class="bg-gray-50 py-12 px-8">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($learners as $learner)
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition-shadow border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg shadow-sm">
                                    {{ substr($learner->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">{{ $learner->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $learner->email }}</p>
                                </div>
                            </div>
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Active</span>
                        </div>
                        
                        <div class="space-y-3 mb-6">
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Current Pathway</p>
                                <p class="text-sm font-semibold text-gray-700 bg-gray-50 p-2 rounded block">Technical: Web Development</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Last Meeting</p>
                                <p class="text-sm text-gray-600">3 days ago - "Sprint Review"</p>
                            </div>
                             <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Next Goal</p>
                                <p class="text-sm text-gray-600">Submit Project Beta by Friday</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 border-t border-gray-100 pt-4 mt-auto">
                        <button class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition-colors text-sm">
                            <i class="fas fa-video mr-1"></i> Meet
                        </button>
                        <button class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-200 transition-colors text-sm" onclick="alert('Logging session interface would open here.')">
                            <i class="fas fa-pen mr-1"></i> Log
                        </button>
                        <a href="{{ route('safeguarding.create', $learner->id) }}"
                            class="flex-none bg-red-50 text-red-500 px-4 py-2 rounded-lg font-bold hover:bg-red-100 transition-colors text-sm inline-flex items-center"
                            title="Raise a safeguarding concern">
                            <i class="fas fa-flag"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-12 text-center bg-white rounded-2xl shadow-sm">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                        <i class="fas fa-user-friends text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">No Learners Assigned</h3>
                    <p class="text-gray-500">You currently have no learners assigned to you.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
