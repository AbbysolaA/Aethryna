@extends('layouts.aethryna')

@section('title', 'Learner Cohort | Skills Co-op')

@section('content')
    <!-- Dashboard Header -->
    <section class="bg-gradient-to-r from-teal-800 to-cyan-900 text-white py-16 px-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500 opacity-20 rounded-full blur-3xl -mr-48 -mt-48"></div>
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">My Cohort</h1>
                <p class="text-xl opacity-90">Tracking {{ $learners->total() }} assigned learners</p>
            </div>
            <a href="{{ route('coach.dashboard') }}" class="bg-white text-teal-900 px-6 py-3 rounded-full font-bold hover:bg-teal-50 transition-colors shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </section>

    <!-- Filter & Sort -->
    <section class="bg-gray-50 py-8 px-8 border-b border-gray-200">
        <div class="max-w-6xl mx-auto flex gap-4">
            <select class="rounded-lg border-gray-300">
                <option>All Pathways</option>
                <option>Technical</option>
                <option>Creative</option>
            </select>
            <select class="rounded-lg border-gray-300">
                <option>Status: Active</option>
                <option>Status: At Risk</option>
                <option>Status: Inactive</option>
            </select>
        </div>
    </section>

    <!-- Learners Table -->
    <section class="bg-gray-50 py-12 px-8">
        <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-md overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider border-b border-gray-200">
                        <th class="p-6 font-semibold">Learner</th>
                        <th class="p-6 font-semibold">Pathway</th>
                        <th class="p-6 font-semibold">Engagement</th>
                        <th class="p-6 font-semibold">Last Active</th>
                        <th class="p-6 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($learners as $cohortRecord)
                        <tr class="hover:bg-teal-50 transition-colors border-b border-gray-100 last:border-0 group">
                            <td class="p-6 font-bold text-teal-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-400 to-blue-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                        {{ substr($cohortRecord->learner->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="group-hover:text-teal-700 transition-colors">{{ $cohortRecord->learner->name }}</div>
                                        <div class="text-xs text-gray-400 font-normal">{{ $cohortRecord->learner->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $cohortRecord->cohort_name ?? 'General Cohort' }}
                                </span>
                            </td>
                            <td class="p-6">
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 max-w-xs">
                                    <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $cohortRecord->engagement_score ?? 0 }}%"></div>
                                </div>
                                <span class="text-xs {{ ($cohortRecord->engagement_score ?? 0) >= 70 ? 'text-green-600' : 'text-orange-600' }} font-semibold mt-1 block">
                                    {{ ($cohortRecord->engagement_score ?? 0) >= 70 ? 'High' : 'Medium' }} ({{ $cohortRecord->engagement_score ?? 0 }}%)
                                </span>
                            </td>
                            <td class="p-6 text-sm text-gray-500">
                                {{ $cohortRecord->last_activity_date ? $cohortRecord->last_activity_date->diffForHumans() : 'No activity' }}
                            </td>
                            <td class="p-6 text-right">
                                <form action="{{ route('coach.flag', $cohortRecord->learner->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Flag this learner for safeguarding concern? This will alert admins.');">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors mx-2" title="Flag Concern">
                                        <i class="fas fa-flag"></i>
                                    </button>
                                </form>
                                <button class="text-gray-400 hover:text-blue-500 transition-colors mx-2" title="Assign Team">
                                    <i class="fas fa-users-cog"></i>
                                </button>
                                <button class="text-gray-400 hover:text-teal-600 transition-colors mx-2" title="View Profile">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">No learners found in this cohort.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="p-6 border-t border-gray-100">
                {{ $learners->links() }}
            </div>
        </div>
    </section>
@endsection
